const titles = ["Paste", "Share", "Analyse"];
let currentTitle = 0;
let speed = 30;
let pause = 3000;
const pasteArea = document.getElementById('paste');
const titleElement = document.querySelector('.title-verb');
const pasteSaveButtons = document.querySelectorAll('.paste-save');
const pasteHeader = document.querySelector('.paste-header');
const pasteFooter = document.querySelector('.paste-footer');

setTimeout(nextTitle, pause);
function nextTitle() {
    currentTitle++;
    if(typeof(titles[currentTitle]) === "undefined") {
        currentTitle = 0;
    }

    const title = titleElement.innerHTML;
    for (let i = 0; i < title.length - 1; i++) {
        setTimeout(function() {
            titleElement.innerHTML = titleElement.innerHTML.substring(0, titleElement.innerHTML.length - 1);
        }, i * speed);
    }

    const newTitle = titles[currentTitle];
    for (let i = 1; i <= newTitle.length; i++) {
        setTimeout(function(){
            titleElement.innerHTML = newTitle.substring(0, titleElement.innerHTML.length + 1);
        }, title.length * speed + i * speed);
    }

    setTimeout(nextTitle, title.length * speed + newTitle.length * speed + pause);
}

pasteArea.focus();

pasteSaveButtons.forEach(button => button.addEventListener('click', sendLog));

document.addEventListener('keydown', event => {
    if ((event.key.toLowerCase() === 's' && event.ctrlKey) || event.key.codePointAt(0) === 19) {
        void sendLog();
        event.preventDefault();
        return false;
    }

    return true;
})

/**
 * Save the log to the API
 * @returns {Promise<void>}
 */
async function sendLog() {
    if (pasteArea.value === "") {
        return;
    }

    pasteSaveButtons.forEach(button => button.classList.add("btn-working"));

    try {
        let log = pasteArea.value
            .substring(0, parseInt(pasteArea.dataset.maxLength))
            .split('\n').slice(0, parseInt(pasteArea.dataset.maxLines)).join('\n');

        const response = await fetch(`${location.protocol}//api.${location.host}/1/log`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                "content": log
            })
        });

        if (!response.ok) {
            handleUploadError(`${response.status} (${response.statusText})`);
            return;
        }

        let data = null;
        try {
            data = await response.json();
        }
        catch (e) {
            console.error("Failed to parse JSON returned by API", e);
            handleUploadError("API returned invalid JSON");
            return;
        }

        if (typeof data === 'object' && !data.success && data.error) {
            console.error(new Error("API returned an error"), data.error);
            handleUploadError(data.error);
            return;
        }

        if (typeof data !== 'object' || !data.success || !data.id) {
            console.error(new Error("API returned an invalid response"), data);
            handleUploadError("API returned an invalid response");
            return;
        }

        location.href = `/${data.id}`;
    }
    catch (e) {
        handleUploadError("Network error");
    }
}

/**
 * Show an error message and stop the loading animation
 * @param {string|null} reason
 * @return {void}
 */
function handleUploadError(reason = null) {
    showPasteError(reason ?? "Unknown error");
    pasteSaveButtons.forEach(button => button.classList.remove("btn-working"));
}

/**
 * show an error message in the paste header and footer
 * @param {string|null} message
 * @return {void}
 */
function showPasteError(message) {
    for (const pasteError of document.querySelectorAll('.paste-error')) {
        pasteError.remove();
    }

    for (const parent of [pasteHeader, pasteFooter]) {
        const pasteError = document.createElement('div');
        pasteError.classList.add('paste-error');
        pasteError.innerText = `Failed to save log: ${message}`;

        parent.insertBefore(pasteError, parent.querySelector('.paste-save'));
    }
}

let dropZone = document.getElementById('dropzone');
let fileSelectButton = document.getElementById('paste-select-file');
let windowDragCount = 0;
let dropZoneDragCount = 0;

function updateWindowDragCount(amount) {
    windowDragCount = Math.max(0, windowDragCount + amount);
    if (windowDragCount > 0) {
        dropZone.classList.add('window-dragover');
    } else {
        dropZone.classList.remove('window-dragover');
    }
}

function updateDropZoneDragCount(amount) {
    dropZoneDragCount = Math.max(0, dropZoneDragCount + amount);
    if (dropZoneDragCount > 0) {
        dropZone.classList.add('dragover');
    } else {
        dropZone.classList.remove('dragover');
    }
}

/**
 * @param {Blob} file
 * @return {Promise<Uint8Array>}
 */
function readFile(file) {
    return new Promise((resolve, reject) => {
        let reader = new FileReader();
        // noinspection JSCheckFunctionSignatures
        reader.onload = () => resolve(new Uint8Array(reader.result));
        reader.onerror = e => reject(e);
        reader.readAsArrayBuffer(file);
    });
}

async function handleDropEvent(e) {
    let files = e.dataTransfer.files;
    if (files.length !== 1) {
        return;
    }

    await loadFileContents(files[0]);
}

async function loadFileContents(file) {
    if (file.size > 1024 * 1024 * 100) {
        return;
    }
    let content = await readFile(file);
    if (file.name.endsWith('.gz')) {
        content = await unpackGz(content);
    }

    if (content.includes(0)) {
        return;
    }

    pasteArea.value = new TextDecoder().decode(content);
}

function loadScript(url) {
    return new Promise((resolve, reject) => {
        let elem = document.createElement('script');
        elem.addEventListener('load', resolve);
        elem.addEventListener('error', reject);
        elem.src = url;
        document.head.appendChild(elem);
    });
}

async function loadFflate() {
    if(typeof fflate === 'undefined') {
        await loadScript('https://unpkg.com/fflate');
    }
}

function selectLogFile() {
    let input = document.createElement('input');
    input.type = 'file';
    input.style.display = 'none';
    document.body.appendChild(input);
    input.onchange = async () => {
        if(input.files.length) {
            await loadFileContents(input.files[0]);
        }
    }
    input.click();
    document.body.removeChild(input);
}

/**
 * @param {Uint8Array} data
 * @return {Promise<Uint8Array>}
 */
async function unpackGz(data) {
    if(typeof DecompressionStream === 'undefined') {
        await loadFflate();
        return fflate.gunzipSync(data);
    }

    let inputStream = new ReadableStream({
        start: (controller) => {
            controller.enqueue(data);
            controller.close();
        }
    });
    const ds = new DecompressionStream('gzip');
    const decompressedStream = inputStream.pipeThrough(ds);
    return new Uint8Array(await new Response(decompressedStream).arrayBuffer());
}

window.addEventListener('dragover', e => e.preventDefault());
window.addEventListener('dragenter', e => {
    e.preventDefault();
    updateWindowDragCount(1);
});
window.addEventListener('dragleave', e => {
    e.preventDefault()
    updateWindowDragCount(-1);
});
window.addEventListener('drop', e => {
    e.preventDefault()
    updateWindowDragCount(-1);
});

dropZone.addEventListener('dragenter', e => {
    e.preventDefault();
    updateDropZoneDragCount(1);
});
dropZone.addEventListener('dragleave', e => {
    e.preventDefault();
    updateDropZoneDragCount(-1);
});
dropZone.addEventListener('drop', async e => {
    e.preventDefault();
    updateDropZoneDragCount(-1);
    await handleDropEvent(e);
});

fileSelectButton.addEventListener('click', selectLogFile);


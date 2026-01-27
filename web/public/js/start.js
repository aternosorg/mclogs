/* Title animation */
const titles = ["Paste", "Share", "Analyse"];
let currentTitle = 0;
let speed = 30;
let pause = 3000;
const titleElement = document.querySelector('.title-verb');

setTimeout(nextTitle, pause);

function nextTitle() {
    currentTitle++;
    if (typeof (titles[currentTitle]) === "undefined") {
        currentTitle = 0;
    }

    const title = titleElement.innerHTML;
    for (let i = 0; i < title.length - 1; i++) {
        setTimeout(function () {
            titleElement.innerHTML = titleElement.innerHTML.substring(0, titleElement.innerHTML.length - 1);
        }, i * speed);
    }

    const newTitle = titles[currentTitle];
    for (let i = 1; i <= newTitle.length; i++) {
        setTimeout(function () {
            titleElement.innerHTML = newTitle.substring(0, titleElement.innerHTML.length + 1);
        }, title.length * speed + i * speed);
    }

    setTimeout(nextTitle, title.length * speed + newTitle.length * speed + pause);
}

/* Paste area */
const pasteArea = document.getElementById('paste-text');
const pastePlaceholder = document.querySelector('.paste-placeholder');
const pasteSaveButtons = document.querySelectorAll('.paste-save');
const fileSelectButton = document.getElementById('paste-select-file');
const pasteClipboardButton = document.getElementById('paste-clipboard');
const pasteError = document.getElementById('paste-error');

pasteArea.focus();
pasteArea.addEventListener('input', reevaluateContentStatus);
pasteSaveButtons.forEach(button => button.addEventListener('click', sendLog));
fileSelectButton.addEventListener('click', selectLogFile);
pasteClipboardButton.addEventListener('click', pasteFromClipboard);

document.addEventListener('keydown', event => {
    if (event.key.toLowerCase() === 's' && (event.ctrlKey || event.metaKey)) {
        void sendLog();
        event.preventDefault();
        return false;
    }

    return true;
});

/**
 * Save the log to the API
 * @returns {Promise<void>}
 */
async function sendLog() {
    if (pasteArea.value === "") {
        return;
    }

    clearError();
    pasteSaveButtons.forEach(button => button.classList.add("btn-working"));

    try {
        let log = pasteArea.value
            .substring(0, parseInt(pasteArea.dataset.maxLength))
            .split('\n').slice(0, parseInt(pasteArea.dataset.maxLines)).join('\n');

        const bodyData = {
            "content": log,
            "source": location.host,
            "metadata": Array.isArray(self.METADATA) ? self.METADATA : []
        };

        let headers = {
            "Content-Type": "application/json"
        }

        let body = JSON.stringify(bodyData);
        if (isGzSupported()) {
            headers["Content-Encoding"] = "gzip";
            body = await packGz(body);
        }

        const response = await fetch(`/new`, {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json",
                "Content-Encoding": "gzip"
            },
            body
        });

        if (!response.ok) {
            showError(`${response.status} (${response.statusText})`);
            return;
        }

        let data = null;
        try {
            data = await response.json();
        } catch (e) {
            console.error("Failed to parse JSON returned by API", e);
            showError("API returned invalid JSON");
            return;
        }

        if (typeof data === 'object' && !data.success && data.error) {
            console.error(new Error("API returned an error"), data.error);
            showError(data.error);
            return;
        }

        if (typeof data !== 'object' || !data.success || !data.id) {
            console.error(new Error("API returned an invalid response"), data);
            showError("API returned an invalid response");
            return;
        }

        location.href = data.url;
    } catch (e) {
        showError("Network error");
    }
}

async function pasteFromClipboard() {
    try {
        let content = await navigator.clipboard.readText();
        if (!content || content.trim().length === 0) {
            showError("Clipboard is empty.");
            return;
        }
        reevaluateContentStatus();
    } catch (err) {
        showError("Clipboard is empty or not accessible.");
    }
}

function reevaluateContentStatus() {
    clearError();
    if (pasteArea.value.length > 0) {
        pastePlaceholder.style.display = 'none';
        pasteSaveButtons.forEach(button => button.removeAttribute("disabled"));
    } else {
        pastePlaceholder.style.display = 'block';
        pasteSaveButtons.forEach(button => button.setAttribute("disabled", "disabled"));
    }
}

function showError(message) {
    pasteError.innerText = message;
    pasteError.style.display = 'block';
}

function clearError() {
    pasteError.innerText = '';
    pasteError.style.display = 'none';
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

async function loadFileContents(file) {
    if (file.size > 1024 * 1024 * 100) {
        showError(`File is too large.`);
        return;
    }
    let content = await readFile(file);
    if (file.name.endsWith('.gz')) {
        if (!isGzSupported()) {
            showError(`Gzip files are not supported in this browser.`);
            return;
        }
        content = await unpackGz(content);
    }

    if (content.includes(0)) {
        showError(`This file is not supported.`);
        return;
    }

    pasteArea.value = new TextDecoder().decode(content);
    reevaluateContentStatus();
}

function selectLogFile() {
    let input = document.createElement('input');
    input.type = 'file';
    input.style.display = 'none';
    document.body.appendChild(input);
    input.onchange = async () => {
        if (input.files.length) {
            await loadFileContents(input.files[0]);
        }
    }
    input.click();
    document.body.removeChild(input);
}

function isGzSupported() {
    return (typeof CompressionStream !== 'undefined') && (typeof DecompressionStream !== 'undefined');
}

/**
 * @param {string} raw
 * @returns {Promise<Uint8Array>}
 */
async function packGz(raw) {
    let data = new TextEncoder().encode(raw);
    let inputStream = new ReadableStream({
        start: (controller) => {
            controller.enqueue(data);
            controller.close();
        }
    });
    const cs = new CompressionStream('gzip');
    const compressedStream = inputStream.pipeThrough(cs);
    return new Uint8Array(await new Response(compressedStream).arrayBuffer());
}

/**
 * @param {Uint8Array} data
 * @return {Promise<Uint8Array>}
 */
async function unpackGz(data) {
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

/* Drag and drop */
const dropZone = document.getElementById('dropzone');
let windowDragCount = 0;
let dropZoneDragCount = 0;

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

async function handleDropEvent(e) {
    let files = e.dataTransfer.files;
    if (files.length !== 1) {
        return;
    }

    await loadFileContents(files[0]);
}
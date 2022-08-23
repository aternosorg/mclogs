var titles = ["Paste", "Share", "Analyse"];
var currentTitle = 0;
var speed = 30;
var pause = 3000;
const pasteArea = document.getElementById('paste');

setTimeout(nextTitle, pause);
function nextTitle() {
    currentTitle++;
    if(typeof(titles[currentTitle]) === "undefined") {
        currentTitle = 0;
    }

    var title = $('.title-verb').text();
    for (var i = 0; i < title.length - 1; i++) {
        setTimeout(function() {
            $('.title-verb').text($('.title-verb').text().substr(0, $('.title-verb').text().length - 1));
        }, i * speed);
    }

    var newTitle = titles[currentTitle];
    for (var i = 1; i <= newTitle.length; i++) {
        setTimeout(function(){
            $('.title-verb').text(newTitle.substr(0, $('.title-verb').text().length + 1));
        }, title.length * speed + i * speed);
    }

    setTimeout(nextTitle, title.length * speed + newTitle.length * speed + pause);
}

$(pasteArea).focus();

$('.paste-save').click(sendLog);
$(document).keydown(function(event) {
    if (!(String.fromCharCode(event.which).toLowerCase() === 's' && event.ctrlKey) && !(event.which === 19)) return true;
    sendLog();
    event.preventDefault();
    return false;
});

function sendLog() {
    if($(pasteArea).val() === "") {
        return false;
    }

    $('.paste-save').addClass("btn-working");
    $.post('http'+((location.protocol === "https:") ? "s" : "")+'://api.'+location.host+'/1/log', {content: $('#paste').val()}, function(data){
        location.href = "/" + data.id;
    });
}

let dropZone = document.getElementById('dropzone');
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
    let file = files[0];

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


/* line numbers */
updateLineNumber(location.hash);

for (let line of document.querySelectorAll('.line-number')) {
    line.addEventListener("click", () =>
        updateLineNumber(line.attributes.getNamedItem("id").value));
}

function updateLineNumber(id) {
    if (id && id.startsWith('#')) {
        id = id.substring(1);
    }

    if (!id) {
        return;
    }

    let element = document.getElementById(id);
    if (element.classList.contains("line-number")) {
        for (const line of document.querySelectorAll(".line-active")) {
            line.classList.remove("line-active");
        }
        element.closest('.entry').classList.add('line-active');
    }
}

/* Scroll to top/bottom buttons */
const downButton = document.getElementById("down-button");
if (downButton) {
    downButton.addEventListener("click", () => scrollToHeight(document.body.scrollHeight));
}

const upButton = document.getElementById("up-button");
if (upButton) {
    upButton.addEventListener("click", () => scrollToHeight(0));
}

/**
 * Scroll to a specific height
 * Disable smooth scrolling for large pages
 * @param {number} top height to scroll to
 * @param {number} [smoothScrollLimit] only use smooth scrolling if the distance is less than this value
 */
function scrollToHeight(top, smoothScrollLimit = 10000) {
    const distance = Math.abs(document.documentElement.scrollTop - top);
    const behavior = (distance < smoothScrollLimit) ? "smooth" : "instant";
    window.scrollTo({left: 0, top, behavior});
}

/* error collapse toggle */
let showOnlyErrors = false;
const toggleErrorsButton = document.getElementById("error-toggle");
if (toggleErrorsButton) {
    toggleErrorsButton.addEventListener("click", () => {
        if (showOnlyErrors) {
            toggleErrorsButton.classList.replace("btn-black", "btn-red");
            document.querySelectorAll('.entry-no-error').forEach(line => line.hidden = false);
            document.querySelectorAll('.collapsed-lines').forEach(collapsed => collapsed.remove());
        } else {
            let firstNoErrorLine = false;
            toggleErrorsButton.classList.replace("btn-red", "btn-black");
            let lines = document.querySelectorAll('.log tr');
            let totalLines = lines.length;
            for (const [i, line] of lines.entries()) {
                let lineNumber = line.querySelector(".line-number").innerHTML;
                if (line.classList.contains("entry-no-error")) {
                    line.hidden = true;

                    if (firstNoErrorLine === false) {
                        firstNoErrorLine = lineNumber;
                    }

                    if (i + 1 === totalLines && firstNoErrorLine) {
                        line.insertAdjacentHTML("afterend", generateCollapsedLines(firstNoErrorLine, lineNumber));
                    }
                } else {
                    if (firstNoErrorLine) {
                        line.insertAdjacentHTML("beforebegin", generateCollapsedLines(firstNoErrorLine, lineNumber - 1));
                        firstNoErrorLine = false;
                    }
                }
            }
        }
        showOnlyErrors = !showOnlyErrors;

        for (const collapsed of document.querySelectorAll('.collapsed-lines-count')) {
            collapsed.addEventListener("click", () => {
                let positionElement = document.getElementById(`L${parseInt(collapsed.dataset.end) + 1}`);
                let position;
                if (positionElement) {
                    position = positionElement.getBoundingClientRect().top - window.scrollY;
                }
                for (let i = parseInt(collapsed.dataset.start); i <= parseInt(collapsed.dataset.end); i++) {
                    document.getElementById(`L${i}`).parentElement.parentElement.hidden = false;
                }
                if (positionElement) {
                    window.scrollTo({
                        left: 0,
                        top: positionElement.getBoundingClientRect().top - position - collapsed.offsetHeight,
                        behavior: "instant"
                    });
                }
                collapsed.remove();
            })
        }
    });
}

function generateCollapsedLines(start, end) {
    let count = end - start + 1;
    let string = count === 1 ? "line" : "lines";
    return '<tr class="collapsed-lines">' +
        '<td></td>' +
        '<td class="collapsed-lines-count" data-start="' + start + '" data-end="' + end + '">' +
        '<i class="fa fa-angle-up"></i> ' +
        count + " " + string +
        ' <i class="fa fa-angle-up"></i>' +
        '</td>' +
        '</tr>';
}

/* convert timestamps */
let timeElements = document.querySelectorAll('[data-time]');
for (const element of timeElements) {
    const timestamp = parseInt(element.dataset.time);
    if (isNaN(timestamp)) {
        continue;
    }
    const date = new Date(timestamp * 1000);
    element.innerHTML = date.toLocaleString();
}

/* settings */
const settingCheckboxes = document.querySelectorAll(".setting-checkbox");
settingCheckboxes.forEach(checkbox => checkbox.addEventListener("change", handleSettingChange))

function handleSettingChange(e) {
    let checkbox = e.target;
    let bodyClass = checkbox.dataset.bodyClass;
    if (checkbox.checked) {
        document.body.classList.add(bodyClass);
    } else {
        document.body.classList.remove(bodyClass);
    }
    saveSettings();
}

function saveSettings() {
    const data = {};
    for (const checkbox of settingCheckboxes) {
        data[checkbox.dataset.key] = checkbox.checked;
    }
    document.cookie = "MCLOGS_SETTINGS=" + encodeURIComponent(JSON.stringify(data)) + ";path=/;expires=" + new Date(new Date().getTime() + 100 * 365 * 24 * 60 * 60 * 1000).toUTCString();
}

/* copy to clipboard */
const copyButtons = document.querySelectorAll("[data-clipboard]");
copyButtons.forEach(button => button.addEventListener("click", handleCopyButtonClick));
const doneClassName = "fa-solid fa-check";

async function handleCopyButtonClick(e) {
    const button = e.currentTarget;
    const data = button.dataset.clipboard;
    await navigator.clipboard.writeText(data);

    const iconElement = button.querySelector("i");
    if (!iconElement) {
        return;
    }
    const originalClassName = iconElement.className;
    if (originalClassName === doneClassName) {
        return;
    }
    iconElement.className = doneClassName;
    setTimeout(() => {
        iconElement.className = originalClassName;
    }, 2000);
}
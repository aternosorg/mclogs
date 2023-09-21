updateLineNumber(location.hash);

for (let line of document.querySelectorAll('.line-number')) {
    line.addEventListener("click", () =>
        updateLineNumber(line.attributes.getNamedItem("id").value));
}

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
        element.classList.add('line-active');
    }
}

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
            '</td>'+
        '</tr>';
}

const wrapCheckbox = document.getElementById("wrap-checkbox");
if (wrapCheckbox) {
    wrapCheckbox.addEventListener("change", () => {
        if (wrapCheckbox.checked) {
            document.querySelector(".log-row .row-inner").classList.remove("no-wrap");
        } else {
            document.querySelector(".log-row .row-inner").classList.add("no-wrap");
        }
        wrapCheckbox.scrollIntoView({behavior: "instant"});
        document.cookie = "WRAP_LOG_LINES=" + wrapCheckbox.checked + ";path=/";
    })
}

<header>
    <a href="<?=htmlspecialchars(\IndifferentKetchup\IBLogs\Util\URL::getBase()->toString()); ?>" class="logo">
        <img src="img/broccoli.png" class="logo-icon" alt="IB Logs">
        <span class="logo-text"><?= htmlspecialchars(\IndifferentKetchup\IBLogs\Config\Config::getInstance()->getName()); ?></span>
    </a>
    <div class="tagline">
        <h1 class="tagline-main"><span class="title-verb">Paste</span> your logs.</h1>
        <div class="tagline-sub">Analyze your logs. Or don't. We don't care.</div>
    </div>
    <script>
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
    </script>
</header>

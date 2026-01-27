<header>
    <a href="/" class="logo">
        <svg class="logo-icon" width="41" height="42" viewBox="0 0 41 42" fill="none"
             xmlns="http://www.w3.org/2000/svg">
            <rect width="41" height="5" rx="2" fill="currentColor"/>
            <rect y="9.25" width="33" height="5" rx="2" fill="currentColor"/>
            <rect y="18.5" width="19" height="5" rx="2" fill="currentColor"/>
            <rect y="27.75" width="33" height="5" rx="2" fill="currentColor"/>
            <rect y="37" width="41" height="5" rx="2" fill="currentColor"/>
        </svg>
        <span class="logo-text"><?= \Aternos\Mclogs\Config\Config::getInstance()->getName(); ?></span>
    </a>
    <div class="tagline">
        <h1 class="tagline-main"><span class="title-verb">Paste</span> your logs.</h1>
        <div class="tagline-sub">Built for Minecraft & Hytale</div>
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
<?php

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Util\URL;

$config = Config::getInstance();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
       <?php include __DIR__ . '/parts/head.php'; ?>
       <title><?= URL::getBase()->getHost(); ?> - Paste, share & analyse your Minecraft logs</title>
    </head>
    <body>
        <div class="container">
            <?php include __DIR__ . '/parts/header.php'; ?>

            <main>
                <div class="paste-area" id="dropzone">
                    <div class="paste-placeholder">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <p>Paste or drop your log here</p>
                        <div class="paste-hints">
                            <span><kbd>Ctrl+V</kbd> paste</span>
                            <span><i class="fa-solid fa-file-arrow-up"></i> drop</span>
                            <button type="button" class="link-btn" id="paste-select-file"><i class="fa-solid fa-folder-open"></i> browse</button>
                        </div>
                    </div>
                    <textarea id="paste" data-max-length="10000000" data-max-lines="25000"></textarea>
                    <button type="button" class="btn-save paste-save" disabled><i class="fa-solid fa-share"></i> Share</button>
                </div>
            </main>
            <script>
                const paste = document.getElementById('paste');
                const btn = document.querySelector('.btn-save');
                paste.addEventListener('input', () => { btn.disabled = !paste.value; });
            </script>

            <?php include __DIR__ . '/parts/footer.php'; ?>
        </div>
        <script src="js/mclogs.js"></script>
    </body>
</html>

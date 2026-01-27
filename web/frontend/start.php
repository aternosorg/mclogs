<!DOCTYPE html>
<html lang="en">
    <head>
       <?php include __DIR__ . '/parts/head.php'; ?>
       <title><?= \Aternos\Mclogs\Util\URL::getBase()->getHost(); ?> - Paste, share & analyse your Minecraft logs</title>
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
                            <button type="button" class="link-btn" id="paste-clipboard"><i class="fa-solid fa-paste"></i> Paste</button>
                            <span><i class="fa-solid fa-file-arrow-up"></i> Drop</span>
                            <button type="button" class="link-btn" id="paste-select-file"><i class="fa-solid fa-folder-open"></i> Browse</button>
                        </div>
                    </div>
                    <textarea spellcheck="false" data-enable-grammarly="false" id="paste-text" data-max-length="10000000" data-max-lines="25000"></textarea>
                    <button type="button" class="btn-save paste-save" disabled><i class="fa-solid fa-share"></i> Share</button>
                </div>
            </main>
            <?php include __DIR__ . '/parts/footer.php'; ?>
        </div>
        <script src="js/start.js"></script>
    </body>
</html>

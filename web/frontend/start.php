<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__ . '/parts/head.php'; ?>
        <title><?= \Aternos\Mclogs\Config\Config::getInstance()->getName(); ?> - Paste, share & analyse your logs</title>
        <meta name="description" content="Easily paste your Minecraft & Hytale logs to share and analyse them." />
    </head>
    <body data-name="<?=\Aternos\Mclogs\Config\Config::getInstance()->getName(); ?>">
        <div class="container">
            <?php include __DIR__ . '/parts/header.php'; ?>

            <main>
                <div class="paste-area" id="dropzone">
                    <div class="paste-placeholder">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <p>Paste or drop your log here</p>
                        <div class="paste-hints">
                            <button type="button" class="btn btn-transparent" id="paste-clipboard"><i class="fa-solid fa-paste"></i> Paste</button>
                            <button type="button" class="btn btn-transparent" id="paste-select-file"><i class="fa-solid fa-folder-open"></i> Browse</button>
                            <span><i class="fa-solid fa-file-arrow-up"></i> Drop</span>
                        </div>
                    </div>
                    <textarea spellcheck="false" data-enable-grammarly="false" id="paste-text" data-max-length="10000000" data-max-lines="25000"></textarea>
                    <button type="button" class="btn-save btn paste-save" disabled><i class="fa-solid fa-save"></i> Save</button>
                    <div class="paste-error" id="paste-error"></div>
            </main>
            <?php include __DIR__ . '/parts/footer.php'; ?>
        </div>
        <script src="js/start.js"></script>
    </body>
</html>

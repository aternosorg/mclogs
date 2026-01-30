<?php
use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Frontend\Assets\AssetLoader;
use Aternos\Mclogs\Frontend\Assets\AssetType;
?><!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__ . '/parts/head.php'; ?>
        <title><?= htmlspecialchars(Config::getInstance()->getName()); ?> - Paste, share & analyse your logs</title>
        <meta name="description" content="Easily paste your Minecraft & Hytale logs to share and analyse them." />
    </head>
    <body data-name="<?=htmlspecialchars(Config::getInstance()->getName()); ?>">
    <?php include __DIR__ . '/parts/header.php'; ?>
            <main>
                <div class="paste-area" id="dropzone">
                    <div class="paste-placeholder">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <p>Paste or drop your log here</p>
                        <div class="paste-hints">
                            <button type="button" class="btn btn-transparent" title="Paste log" id="paste-clipboard"><i class="fa-solid fa-paste"></i> Paste</button>
                            <button type="button" class="btn btn-transparent" title="Browse on files" id="paste-select-file"><i class="fa-solid fa-folder-open"></i> Browse</button>
                            <span><i class="fa-solid fa-file-arrow-up" title="Drop file"></i> Drop</span>
                        </div>
                    </div>
                    <textarea aria-label="Paste or drop your log here" spellcheck="false" data-enable-grammarly="false" id="paste-text" data-max-length="10000000" data-max-lines="25000"></textarea>
                    <button type="button" class="btn-save btn paste-save" title="Save log" disabled><i class="fa-solid fa-save"></i> Save</button>
                    <div class="paste-error" id="paste-error"></div>
                </div>
            </main>
        <?php include __DIR__ . '/parts/footer.php'; ?>
        <?= AssetLoader::getInstance()->getHTML(AssetType::JS, "js/start.js"); ?>
    </body>
</html>

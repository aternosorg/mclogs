<?php

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Log;
use Aternos\Mclogs\Util\URL;

$config = Config::getInstance();

$id = new Id(substr($_SERVER['REQUEST_URI'], 1));
$log = Log::find($id);
$shouldWrapLogLines = filter_var($_COOKIE["WRAP_LOG_LINES"] ?? "true", FILTER_VALIDATE_BOOLEAN);

$title = "mclo.gs - Paste, share & analyse your Minecraft logs";
$description = "Easily paste your Minecraft logs to share and analyse them.";
if (!$log) {
    $title = "Log not found - mclo.gs";
    http_response_code(404);
} else {
    $codexLog = $log->getCodexLog();
    $analysis = $log->getAnalysis();
    $information = $analysis->getInformation();
    $problems = $analysis->getProblems();
    $title = $codexLog->getTitle() . " [#" . $id->get() . "]";
    $lineNumbers = $log->getLineCount();
    $lineString = $lineNumbers === 1 ? "line" : "lines";

    $errorCount = $log->getErrorCount();
    $errorString = $errorCount === 1 ? "error" : "errors";

    $description = $lineNumbers . " " . $lineString;
    if ($errorCount > 0) {
       $description .= " | " . $errorCount . " " . $errorString;
    }

    if (count($problems) > 0) {
        $problemString = "problems";
        if (count($problems) === 1) {
            $problemString = "problem";
        }
        $description .= " | " . count($problems) . " " . $problemString . " automatically detected";
    }
}
?><!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__ . '/parts/head.php'; ?>
        <title><?= URL::getBase()->getHost(); ?> - Paste, share & analyse your Minecraft logs</title>
    </head>
    <body class="log-body">
        
        <div class="container">
            <?php include __DIR__ . '/parts/header.php'; ?>

            <div class="row dark log-row">
                <div class="row-inner<?= $shouldWrapLogLines ? "" : " no-wrap"?>">
                    <?php if($log): ?>
                        <div class="log-info">
                            <div class="log-title">
                                <h1><i class="fas fa-file-lines"></i> <?=$codexLog->getTitle(); ?></h1>
                                <div class="log-id">#<?=$id->get(); ?></div>
                            </div>
                            <div class="log-info-actions">
                                <?php if($errorCount): ?>
                                    <div class="btn btn-red btn-small btn-no-margin" id="error-toggle">
                                        <i class="fa fa-exclamation-circle"></i>
                                        <?=$errorCount . " " . $errorString; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="btn btn-blue btn-small btn-no-margin" id="down-button">
                                    <i class="fa fa-arrow-circle-down"></i>
                                    <?=$lineNumbers . " " . $lineString; ?>
                                </div>
                                <a class="btn btn-white btn-small btn-no-margin" id="raw" target="_blank" href="<?=$log->getRawURL()->toString(); ?>">
                                    <i class="fa fa-arrow-up-right-from-square"></i>
                                    Raw
                                </a>
                            </div>
                        </div>
                        <?php if(count($analysis) > 0): ?>
                            <div class="analysis">
                                <div class="analysis-headline"><i class="fa fa-info-circle"></i> Analysis</div>
                                <?php if(count($information) > 0): ?>
                                    <div class="information-list">
                                        <?php foreach($information as $info): ?>
                                            <div class="information">
                                                <div class="information-label">
                                                    <?=$info->getLabel(); ?>:
                                                </div>
                                                <div class="information-value">
                                                    <?=$info->getValue(); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if(count($problems) > 0): ?>
                                    <div class="problem-list">
                                        <?php foreach($problems as $problem): ?>
                                            <div class="problem">
                                                <div class="problem">
                                                    <div class="problem-header">
                                                        <div class="problem-message">
                                                            <i class="fa fa-exclamation-triangle"></i> <?=htmlspecialchars($problem->getMessage()); ?>
                                                        </div>
                                                        <?php $number = $problem->getEntry()[0]->getNumber(); ?>
                                                        <a href="/<?=$id->get() . "#L" . $number; ?>" class="btn btn-blue btn-no-margin btn-small" onclick="updateLineNumber('#L<?=$number; ?>');">
                                                            <span class="hide-mobile"><i class="fa fa-arrow-right"></i> Line </span>#<?=$number; ?>
                                                        </a>
                                                    </div>
                                                    <div class="problem-body">
                                                        <div class="problem-solution-headline">
                                                            Solutions
                                                        </div>
                                                        <div class="problem-solution-list">
                                                            <?php foreach($problem->getSolutions() as $solution): ?>
                                                                <div class="problem-solution">
                                                                    <?=preg_replace("/'([^']+)'/", "'<strong>$1</strong>'", htmlspecialchars($solution->getMessage())); ?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="log">
                            <?php
                            $log->renew();
                            echo $log->getPrinter()->print();
                            ?>
                        </div>
                        <div class="log-bottom">
                            <div class="btn btn-blue btn-small btn-notext" id="up-button">
                                <i class="fa fa-arrow-circle-up"></i>
                            </div>
                            <div class="checkbox-container">
                                <input type="checkbox" id="wrap-checkbox"<?=$shouldWrapLogLines ? " checked" : ""?>/>
                                <label for="wrap-checkbox">Wrap log lines</label>
                            </div>
                        </div>
                        <div class="log-notice">
                            This log will be saved for 90 days from their last view.<br />
                            <a href="mailto:<?=$config->get(ConfigKey::LEGAL_ABUSE); ?>?subject=Report%20mclo.gs/<?=$id->get(); ?>">Report abuse</a>
                        </div>
                    <?php else: ?>
                        <div class="not-found">
                            <div class="not-found-title">404 - Log not found.</div>
                            <div class="not-found-text">The log you try to open does not exist (anymore).<br />We automatically delete all logs that weren't opened in the last 90 days.</div>
                            <div class="not-found-buttons">
                                <a href="/" class="btn btn-no-margin btn-blue btn-small">
                                    <i class="fa fa-home"></i> Paste a new log
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php include __DIR__ . '/parts/footer.php'; ?>

        </div>
        <script src="js/logview.js?v=130221"></script>
    </body>
</html>

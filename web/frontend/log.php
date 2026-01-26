<?php

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Log;
use Aternos\Mclogs\Util\URL;

/** @var Log $log */

$config = Config::getInstance();

$id = $log->getId();
$shouldWrapLogLines = filter_var($_COOKIE["WRAP_LOG_LINES"] ?? "true", FILTER_VALIDATE_BOOLEAN);

$title = "mclo.gs - Paste, share & analyse your Minecraft logs";
$description = "Easily paste your Minecraft logs to share and analyse them.";

$codexLog = $log->getCodexLog();
$analysis = $log->getAnalysis();
$allInformation = $analysis->getInformation();

$versionInfo = [];
$information = [];
$versionLabels = ['Minecraft version', 'Forge version', 'Java version'];

foreach ($allInformation as $info) {
    $label = $info->getLabel();
    if (in_array($label, $versionLabels)) {
        $versionInfo[] = $info;
    } else {
        $information[] = $info;
    }
}

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
?><!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__ . '/parts/head.php'; ?>
        <title><?= URL::getBase()->getHost(); ?> - Paste, share & analyse your Minecraft logs</title>
    </head>
    <body class="log-body">
        
        <div class="container">
            <?php include __DIR__ . '/parts/header.php'; ?>

            <main>
                <?php if($log): ?>
                    <div class="log-header">
                       <div class="log-header-inner">
                           <div class="left">
                               <div class="log-title">
                                   <h1>
                                       <i class="fas fa-file-lines"></i> 
                                       <?=$codexLog->getTitle(); ?>
                                       <span class="log-id-tag">#<?=$id->get(); ?></span>
                                   </h1>
                                   <?php if(count($versionInfo) > 0): ?>
                                       <div class="log-versions">
                                           <?php foreach($versionInfo as $version): ?>
                                               <span class="version-item">
                                                   <span class="version-label"><?=$version->getLabel(); ?>:</span>
                                                   <span class="version-value"><?=$version->getValue(); ?></span>
                                               </span>
                                           <?php endforeach; ?>
                                       </div>
                                   <?php endif; ?>
                               </div>
                           </div>
                           <div class="right">
                               <div class="details">
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
                           </div>
                       </div>
                    </div>
                    <?php if(count($analysis) > 0): ?>
                        <div class="log-analyse">
                            <div class="log-analyse-inner">
                                <div class="analysis">
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
                                        <div class="smart-analyse-list">
                                            <h3 class="detected-issues-headline">Detected Issues</h3>
                                            <?php foreach($problems as $problem): ?>
                                                <div class="smart-analyse">
                                                    <div class="smart-analyse-error-section">
                                                        <div class="smart-analyse-icon">
                                                            <i class="fa fa-exclamation-triangle"></i>
                                                        </div>
                                                        <div class="smart-analyse-message">
                                                            <?=htmlspecialchars($problem->getMessage()); ?>
                                                        </div>
                                                        <div class="smart-analyse-actions">
                                                            <?php $number = $problem->getEntry()[0]->getNumber(); ?>
                                                            <a href="/<?=$id->get() . "#L" . $number; ?>" class="btn btn-blue btn-no-margin btn-small" onclick="updateLineNumber('#L<?=$number; ?>');">
                                                                <span class="hide-mobile"><i class="fa fa-arrow-right"></i> Line </span>#<?=$number; ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php if(count($problem->getSolutions()) > 0): ?>
                                                        <div class="smart-analyse-solutions-section">
                                                            <div class="smart-analyse-solutions-label">Solutions</div>
                                                            <ul class="smart-analyse-solution-list">
                                                                <?php foreach($problem->getSolutions() as $solution): ?>
                                                                    <li class="smart-analyse-solution">
                                                                        <?=preg_replace("/'([^']+)'/", "'<strong>$1</strong>'", htmlspecialchars($solution->getMessage())); ?>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="log">
                        <div class="log-inner<?= $shouldWrapLogLines ? "" : " no-wrap"?>">
                            <?php
                            $log->renew();
                            echo $log->getPrinter()->print();
                            ?>
                        </div>
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
            </main>


            <?php include __DIR__ . '/parts/footer.php'; ?>

        </div>
        <script src="js/logview.js?v=130221"></script>
    </body>
</html>

<?php

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Log;

/** @var Log $log */

$shouldWrapLogLines = filter_var($_COOKIE["WRAP_LOG_LINES"] ?? "true", FILTER_VALIDATE_BOOLEAN);

?><!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__ . '/parts/head.php'; ?>
        <title><?=$log->getPageTitle(); ?></title>
        <meta name="description" content="<?=$log->getPageDescription(); ?>" />
    </head>
    <body class="log-body">
        
        <div class="container">
            <?php include __DIR__ . '/parts/header.php'; ?>

            <main>
                <div class="log-header">
                   <div class="log-header-inner">
                       <div class="left">
                           <div class="log-title">
                               <h1>
                                   <i class="fas fa-file-lines"></i>
                                   <?=$log->getCodexLog()->getTitle(); ?>
                                   <span class="log-id-tag">#<?=$log->getId()->get(); ?></span>
                               </h1>
                           </div>
                       </div>
                       <div class="right">
                           <div class="details">
                               <div class="log-info-actions">
                                   <?php if($log->hasErrors()): ?>
                                       <div class="btn btn-red btn-small btn-no-margin" id="error-toggle">
                                           <i class="fa fa-exclamation-circle"></i>
                                           <?=$log->getErrorsString(); ?>
                                       </div>
                                   <?php endif; ?>
                                   <div class="btn btn-blue btn-small btn-no-margin" id="down-button">
                                       <i class="fa fa-arrow-circle-down"></i>
                                       <?=$log->getLinesString(); ?>
                                   </div>
                                   <a class="btn btn-white btn-small btn-no-margin" id="raw" target="_blank" href="<?=$log->getRawURL()->toString(); ?>">
                                       <i class="fa fa-arrow-up-right-from-square"></i>
                                       Raw
                                   </a>
                               </div>
                           </div>
                       </div>
                   </div>
                   <?php $information = $log->getAnalysis()->getInformation(); ?>
                   <?php if(count($log->getVisibleMetadata()) > 0 || count($information) > 0): ?>
                       <div class="log-info-rows">
                           <?php if(count($log->getVisibleMetadata()) > 0): ?>
                               <div class="log-info-row">
                                   <div class="info-row-header">
                                       <i class="fa-solid fa-tags"></i>
                                       <span>Metadata</span>
                                   </div>
                                   <div class="info-row-items">
                                       <?php foreach($log->getVisibleMetadata() as $metadata): ?>
                                           <span class="info-item">
                                               <span class="info-label"><?=$metadata->getDisplayLabel(); ?>:</span>
                                               <span class="info-value"><?=$metadata->getDisplayValue(); ?></span>
                                           </span>
                                       <?php endforeach; ?>
                                   </div>
                               </div>
                           <?php endif; ?>
                           <?php if(count($information) > 0): ?>
                               <div class="log-info-row">
                                   <div class="info-row-header">
                                       <i class="fa-solid fa-cube"></i>
                                       <span>Detected</span>
                                   </div>
                                   <div class="info-row-items">
                                       <?php foreach($information as $info): ?>
                                           <span class="info-item">
                                               <span class="info-label"><?=$info->getLabel(); ?>:</span>
                                               <span class="info-value"><?=$info->getValue(); ?></span>
                                           </span>
                                       <?php endforeach; ?>
                                   </div>
                               </div>
                           <?php endif; ?>
                       </div>
                   <?php endif; ?>
                </div>
                <?php $problems = $log->getAnalysis()?->getProblems(); ?>
                <?php if(count($problems) > 0): ?>
                    <div class="issues-panel">
                        <div class="issues-header">
                            <span class="issues-count"><?=count($problems); ?></span>
                            <span class="issues-title"><?=count($problems) === 1 ? 'Issue' : 'Issues'; ?> detected</span>
                        </div>
                        <div class="issues-list">
                            <?php foreach($problems as $problem): ?>
                                <?php $number = $problem->getEntry()[0]->getNumber(); ?>
                                <div class="issue-item">
                                    <a href="/<?=$id->get() . "#L" . $number; ?>" class="issue-line" onclick="updateLineNumber('#L<?=$number; ?>');"><?=$number; ?></a>
                                    <div class="issue-content">
                                        <p class="issue-message"><?=htmlspecialchars($problem->getMessage()); ?></p>
                                        <?php if(count($problem->getSolutions()) > 0): ?>
                                            <div class="issue-solutions">
                                                <?php foreach($problem->getSolutions() as $i => $solution): ?>
                                                    <div class="issue-solution">
                                                        <span class="solution-num"><?=$i + 1; ?></span>
                                                        <span class="solution-text"><?=preg_replace("/'([^']+)'/", "'<strong>$1</strong>'", htmlspecialchars($solution->getMessage())); ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="log">
                    <div class="log-inner<?= $shouldWrapLogLines ? "" : " no-wrap"?>">
                        <?php
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
                    <div class="left">
                        <?php if ($source = $log->getSource()): ?>
                            <div class="source">
                                by <?=$source; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($created = $log->getCreated()?->toDateTime()->getTimestamp()): ?>
                            <div class="created-time">
                                <i class="fa-solid fa-clock"></i>
                                <span class="created" data-time="<?=$created; ?>">
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="right">
                        <a href="mailto:<?=Config::getInstance()->get(ConfigKey::LEGAL_ABUSE); ?>?subject=Report%20mclo.gs/<?=$id->get(); ?>" class="report-link">
                            <i class="fa-solid fa-flag"></i>
                            Report abuse
                        </a>
                    </div>
                </div>
            </main>

            <?php include __DIR__ . '/parts/footer.php'; ?>
        </div>
        <script src="js/log.js"></script>
    </body>
</html>

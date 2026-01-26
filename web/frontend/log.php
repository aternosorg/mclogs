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
                               <?php $information = $log->getAnalysis()->getInformation(); ?>
                               <?php if(count($information) > 0 || count($log->getVisibleMetadata()) > 0): ?>
                                   <div class="log-versions">
                                       <?php foreach($information as $info): ?>
                                           <span class="version-item">
                                               <span class="version-label"><?=$info->getLabel(); ?>:</span>
                                               <span class="version-value"><?=$info->getValue(); ?></span>
                                           </span>
                                       <?php endforeach; ?>
                                       <?php foreach($log->getVisibleMetadata() as $metadata): ?>
                                           <span class="version-item">
                                               <span class="version-label"><?=$metadata->getDisplayLabel(); ?>:</span>
                                               <span class="version-value"><?=$metadata->getValue(); ?></span>
                                           </span>
                                       <?php endforeach; ?>
                                   </div>
                               <?php endif; ?>
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
                </div>
                <?php if(count($log->getAnalysis()?->getProblems()) > 0): ?>
                    <div class="log-analyse">
                        <div class="log-analyse-inner">
                            <div class="analysis">
                                <?php $problems = $log->getAnalysis()?->getProblems(); ?>
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
                    <?php if ($source = $log->getSource()): ?>
                        <div class="source">
                            by <?=$source; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($created = $log->getCreated()?->toDateTime()->getTimestamp()): ?>
                        <div class="created" data-time="<?=$created; ?>">
                        </div>
                    <?php endif; ?>
                    This log will be saved for 90 days from their last view.<br />
                    <a href="mailto:<?=Config::getInstance()->get(ConfigKey::LEGAL_ABUSE); ?>?subject=Report%20mclo.gs/<?=$id->get(); ?>">Report abuse</a>
                </div>
            </main>

            <?php include __DIR__ . '/parts/footer.php'; ?>
        </div>
        <script src="js/log.js"></script>
    </body>
</html>

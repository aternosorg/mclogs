<?php

use Aternos\Mclogs\Log;
use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Frontend\Settings\Setting;
use Aternos\Mclogs\Frontend\Settings\Settings;

/** @var Log $log */

$settings = new Settings();
?><!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__ . '/parts/head.php'; ?>
        <title><?=$log->getPageTitle(); ?></title>
        <meta name="description" content="<?=$log->getPageDescription(); ?>" />
    </head>
    <body class="log-body<?=$settings->getBodyClassesString(); ?>">
        
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
                                       <div class="btn btn-danger btn-small" id="error-toggle">
                                           <i class="fa fa-exclamation-circle"></i>
                                           <?=$log->getErrorsString(); ?>
                                       </div>
                                   <?php endif; ?>
                                   <div class="btn btn-dark btn-small" id="down-button">
                                       <i class="fa fa-arrow-circle-down"></i>
                                       <?=$log->getLinesString(); ?>
                                   </div>
                                   <a class="btn btn-white btn-small" id="raw" target="_blank" href="<?=$log->getRawURL()->toString(); ?>">
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
                                                <?php foreach($problem->getSolutions() as $solution): ?>
                                                    <div class="issue-solution">
                                                        <i class="fa-solid fa-lightbulb"></i>
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
                    <div class="btn btn-small btn-dark" id="up-button">
                        <i class="fa fa-arrow-circle-up"></i>
                    </div>
                    <div class="settings-dropdown">
                        <button class="settings-trigger btn btn-small btn-dark" popovertarget="settings-overlay">
                            <i class="fas fa-cog"></i>
                            Settings
                        </button>
                        <div class="settings-overlay" id="settings-overlay" popover>
                            <?php foreach(Setting::cases() as $setting): ?>
                                <label class="setting" for="setting-<?=$setting->value; ?>">
                                    <span class="setting-label"><?=$setting->getLabel(); ?></span>
                                    <input type="checkbox"
                                           id="setting-<?=$setting->value; ?>"
                                           class="setting-checkbox"
                                           data-body-class="<?=$setting->getBodyClass() ?? ""; ?>"
                                           data-key="<?=$setting->value; ?>"
                                            <?=($settings->get($setting)) ? " checked" : ""; ?>/>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="log-notice">
                    <div class="left">
                        <?php if ($source = $log->getSource()): ?>
                            <div class="source" title="Source">
                                <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                <?=$source; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($created = $log->getCreated()?->toDateTime()->getTimestamp()): ?>
                            <div class="created-time" title="Created">
                                <i class="fa-solid fa-clock"></i>
                                <span class="created" data-time="<?=$created; ?>">
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($abuseEmail = Config::getInstance()->get(ConfigKey::LEGAL_ABUSE)): ?>
                    <div class="right">
                        <a href="mailto:<?=$abuseEmail; ?>?subject=Report%20mclo.gs/<?=$log->getId()->get(); ?>" class="report-link">
                            <i class="fa-solid fa-flag"></i>
                            Report abuse
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </main>

            <?php include __DIR__ . '/parts/footer.php'; ?>
        </div>
        <script src="js/log.js"></script>
    </body>
</html>

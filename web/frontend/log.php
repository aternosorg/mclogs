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
        <title><?=htmlspecialchars($log->getPageTitle()); ?></title>
        <meta name="description" content="<?=htmlspecialchars($log->getPageDescription()); ?>" />
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
                                   <?=htmlspecialchars($log->getCodexLog()->getTitle()); ?>
                               </h1>
                               <button class="log-url-btn" data-clipboard="<?=htmlspecialchars($log->getURL()->toString()); ?>" title="Copy log URL to clipboard">
                                   <span class="log-url"><?=htmlspecialchars($log->getDisplayURL()); ?></span>
                                   <i class="fa-solid fa-copy"></i>
                               </button>
                           </div>
                       </div>
                       <div class="right">
                           <div class="details">
                               <div class="log-info-actions">
                                   <?php if($log->hasErrors()): ?>
                                       <div class="btn btn-danger btn-small" id="error-toggle">
                                           <i class="fa fa-exclamation-circle"></i>
                                           <?=htmlspecialchars($log->getErrorsString()); ?>
                                       </div>
                                   <?php endif; ?>
                                   <div class="btn btn-dark btn-small" id="down-button">
                                       <i class="fa fa-arrow-circle-down"></i>
                                       <?=htmlspecialchars($log->getLinesString()); ?>
                                   </div>
                                   <a class="btn btn-dark btn-small" id="raw" target="_blank" title="Raw log" href="<?=$log->getRawURL()->toString(); ?>">
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
                                               <span class="info-label"><?=htmlspecialchars($metadata->getDisplayLabel()); ?>:</span>
                                               <span class="info-value"><?=htmlspecialchars($metadata->getDisplayValue()); ?></span>
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
                                               <span class="info-label"><?=htmlspecialchars($info->getLabel()); ?>:</span>
                                               <span class="info-value"><?=htmlspecialchars($info->getValue()); ?></span>
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
                    <div class="problems-panel">
                        <div class="problems-header">
                            <span class="problems-count"><?=count($problems); ?></span>
                            <span class="problems-title"><?=count($problems) === 1 ? 'Problem' : 'Problems'; ?> detected</span>
                        </div>
                        <div class="problems-list">
                            <?php foreach($problems as $problem): ?>
                                <?php $number = $problem->getEntry()[0]->getNumber(); ?>
                                <div class="problem-item">
                                    <a href="/<?=htmlspecialchars($log->getId()->get()) . "#L" . $number; ?>" class="problem-entry" onclick="updateLineNumber('#L<?=$number; ?>');">
                                        <span class="problem-label">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            Problem
                                        </span>
                                        <span class="problem-text"><?=htmlspecialchars($problem->getMessage()); ?></span>
                                        <span class="problem-line">Line <?=$number; ?></span>
                                    </a>
                                    <?php if(count($problem->getSolutions()) > 0): ?>
                                        <div class="problem-solutions">
                                            <span class="problem-solutions-label"><?=count($problem->getSolutions()) === 1 ? 'Solution:' : 'Solutions:'; ?></span>
                                            <?php foreach($problem->getSolutions() as $solution): ?>
                                                <div class="problem-solution">
                                                    <i class="fa-solid fa-lightbulb"></i>
                                                    <span><?=preg_replace("/'([^']+)'/", "'<strong>$1</strong>'", htmlspecialchars($solution->getMessage())); ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="log">
                    <?php
                    echo $log->getPrinter()->print();
                    ?>
                </div>
                <div class="log-bottom">
                    <div class="btn btn-small btn-dark" id="up-button" title="Scroll to top">
                        <i class="fa fa-arrow-circle-up"></i>
                    </div>
                    <div class="actions">
                        <div class="delete-wrapper popover-wrapper">
                            <button class="delete-trigger popover-trigger btn btn-small btn-danger" title="Delete log" popovertarget="delete-overlay">
                                <i class="fa-solid fa-trash"></i>
                                Delete
                            </button>
                            <div class="delete-overlay popover-content popover-danger" id="delete-overlay" popover>
                                <span class="delete-message">Delete this log permanently?</span>
                                <div class="popover-error">

                                </div>
                                <div class="delete-actions">
                                    <button class="btn btn-small btn-white" popovertarget="delete-overlay">Cancel</button>
                                    <button class="btn btn-small btn-danger delete-log-button">Delete</button>
                                </div>
                            </div>
                        </div>
                        <div class="settings-dropdown popover-wrapper">
                            <button class="settings-trigger popover-trigger btn btn-small btn-dark" title="Settings" popovertarget="settings-overlay">
                                <i class="fas fa-cog"></i>
                                Settings
                            </button>
                            <div class="settings-overlay popover-content" id="settings-overlay" popover>
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
                </div>
                <div class="log-details">
                <?php if($source = $log->getSource() || $created = $log->getCreated()?->toDateTime()->getTimestamp()): ?>
                    <div class="meta-data">
                        <?php if ($source = $log->getSource()): ?>
                            <div class="source" title="Source">
                                <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                <?=htmlspecialchars($source); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($created = $log->getCreated()?->toDateTime()->getTimestamp()): ?>
                            <div class="created-time" title="Created">
                                <i class="fa-solid fa-clock"></i>
                                <span class="created" data-time="<?=htmlspecialchars($created); ?>">
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                    <div class="delete-notice">
                        This log will be saved for <?= htmlspecialchars(\Aternos\Mclogs\Util\TimeInterval::getInstance()->format(Config::getInstance()->get(ConfigKey::STORAGE_TTL))); ?> from its last view.
                    </div>
                    <?php if ($abuseEmail = Config::getInstance()->get(ConfigKey::LEGAL_ABUSE)): ?>
                        <a href="mailto:<?=htmlspecialchars($abuseEmail); ?>?subject=Report%20<?=htmlspecialchars(rawurlencode(Config::getInstance()->getName())); ?>/<?=htmlspecialchars($log->getId()->get()); ?>" class="report-link">
                            <i class="fa-solid fa-flag"></i>
                            Report abuse
                        </a>
                    <?php endif; ?>
                </div>
            </main>

            <?php include __DIR__ . '/parts/footer.php'; ?>
        </div>
        <script src="js/log.js"></script>
    </body>
</html>

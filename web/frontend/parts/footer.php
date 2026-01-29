<?php
use Aternos\Mclogs\Config\Config;use Aternos\Mclogs\Config\ConfigKey;use Aternos\Mclogs\Util\URL;

$imprintUrl = Config::getInstance()->get(ConfigKey::LEGAL_IMPRINT);
$privacyUrl = Config::getInstance()->get(ConfigKey::LEGAL_PRIVACY);
?>
<footer>
    <?php if($imprintUrl || $privacyUrl): ?>
    <nav class="legal">
        <?php if ($imprintUrl): ?>
            <a href="<?=htmlspecialchars($imprintUrl); ?>" class="footer-link" title="Imprint" target="_blank">Imprint</a>
        <?php endif; ?>
        <?php if ($imprintUrl && $privacyUrl): ?>
            <span class="footer-separator"> - </span>
        <?php endif; ?>
        <?php if ($privacyUrl): ?>
            <a href="<?=htmlspecialchars($privacyUrl); ?>" class="footer-link" title="Privacy Policy" target="_blank">Privacy Policy</a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>
    <nav class="footer-nav">
        <a href="https://github.com/aternosorg/mclogs" title="mclo.gs on Github" target="_blank"><i class="fa-brands fa-github"></i>GitHub</a>
        <a href="https://modrinth.com/plugin/mclogs" title="Download mclo.gs Mod/Plugin" target="_blank"><i class="fa-solid fa-cube"></i>Mod/Plugin</a>
        <a href="<?=htmlspecialchars(URL::getApi()->toString()); ?>" title="mclo.gs API"><i class="fa-solid fa-code"></i>API</a>
    </nav>
    <span class="footer-text">developed by <a href="https://aternos.org" target="_blank" title="Aternos website">Aternos</a>
    </span>
</footer>

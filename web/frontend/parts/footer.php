<?php
use Aternos\Mclogs\Config\Config;use Aternos\Mclogs\Config\ConfigKey;use Aternos\Mclogs\Util\URL;
?>
<footer>
    <nav class="footer-nav">
        <a href="https://github.com/aternosorg/mclogs" target="_blank"><i class="fa-brands fa-github"></i> GitHub</a>
        <a href="https://modrinth.com/plugin/mclogs" target="_blank"><i class="fa-solid fa-cube"></i> Mod/Plugin</a>
        <a href="<?=URL::getApi()->toString(); ?>"><i class="fa-solid fa-code"></i> API</a>
    </nav>
    <span class="footer-text">developed by<a href="https://aternos.org" target="_blank"> Aternos</a>
        <?php if ($imprintUrl = Config::getInstance()->get(ConfigKey::LEGAL_IMPRINT)): ?>
            | <a href="<?=htmlspecialchars($imprintUrl); ?>" class="footer-link" target="_blank">Imprint</a>
        <?php endif; ?>
        <?php if ($privacyUrl = Config::getInstance()->get(ConfigKey::LEGAL_PRIVACY)): ?>
            | <a href="<?=htmlspecialchars($privacyUrl); ?>" class="footer-link" target="_blank">Privacy Policy</a>
        <?php endif; ?>
    </span>
</footer>
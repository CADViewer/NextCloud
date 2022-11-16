<?php

    style("cadviewer", "settings");
    style("cadviewer", "template");
    script("cadviewer", "settings");

?>
<div class="section section-cadviewer section-cadviewer-addr">
    <h2>
        Cadviewer
        <a target="_blank" class="icon-info svg" title="" href="https://github.com/kevmax1/cadviewer-nextcloud/blob/main/README.md" data-original-title="<?php p($l->t("Documentation")) ?>"></a>
    </h2>
    <h2><?php p($l->t("Common settings")) ?></h2>

    <div id="cadviewerAddrSettings">
        <p class="settings-hint"><?php p($l->t("Pasting in the Licence key input the portion of the cvlicense.js file")) ?></p>

        <p><?php p($l->t("Licence key")) ?></p>
        <p><input id="cadviewerLicenceKey" value="<?php p($_["licenceKey"]) ?>" placeholder="00110010 00110010 00110000 00110001 00110010 00110000 00110100 00110001 00110100 00111000 00110001 00110100 00110101 00110001 00110101 00110111 00110001 00110101 00111001 00110001 00110100 00111000 00110001 00110101 00110010 00110001 00110100 00110101 00110001 00110100 00110001 00110001 00110100 00110000 00110001 00111001 00110111 00110010 00110000 00110111 00110010 00110000 00110110 00110010 00110000 00110001 00110010 00110001 00110000 00110010 00110000 00111000 00110010 00110001 00110000 00110010 00110000 00111000 00110010 00110001 00110000 00110010 00110000 00110111 00110001 00111001 00111000 00110010 00110000 00110110 00110010 00110000 00111000 00110010 00110000 00110110 00110010 00110000 00110101 00110010 00110001 00110001 00110010 00110000 00111000 00110010 00110000 00110111 00110010 00110001 00110001 00110010 00110000 00110101 00110010 00110000 00110111 00110001 00111001 00111000 00110001 00110100 00110001 00110001 00110100 00110100 00110001 00110101 00111001 00110001 00110101 00110111 00110001 00110101 00110101" type="text"></p>

    </div>
    <br />
    <p><button id="cadviewerSave" class="button"><?php p($l->t("Save")) ?></button></p>
</div>
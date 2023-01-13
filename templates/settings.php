<?php

    style("cadviewer", "settings");
    style("cadviewer", "template");
    OCP\Util::addScript('cadviewer/settings', 'script' );

?>
<div class="section section-cadviewer section-cadviewer-addr">
    <h1>
        Cadviewer
        <a target="_blank" class="icon-info svg" title="" href="https://github.com/CADViewer/NextCloud/blob/main/README.md" data-original-title="<?php p($l->t("Documentation")) ?>"></a>
    </h1>

    <h2><?php p($l->t("Credentials for License Key")) ?></h2>
    <div class="content-verification">
        <div>
            <b>Content of verification</b>: 
        </div>
        <pre id="verifyOutput">
            <?php p($_["autoexchange"]["output"]) ?>
        </pre>
    </div>
    <br />
    <div class="content-url">
        <b>URL of the installation</b>: <span id="installationUrl"><?php p($_["autoexchange"]["domaine_url"]) ?></span>
    </div>
    <div class="content-url">
        <b>Nextcloud instance ID</b>: <span id="instanceID"><?php p($_["autoexchange"]["instance_id"]) ?></span>
    </div>
    
    <br />
    <p><button id="getLicenseKeyInfo" class="button"><?php p($l->t("Get Server Credentials for License Key")) ?></button></p>
    <br />

    <h2><?php p($l->t("AutoXchange license key")) ?></h2>

	<div>
        <div class="uploadButton">
            <label for="uploadaxlic"><span><?php p($l->t('License key file')) ?></span></label>
            <input id="uploadaxlic" class="fileupload" name="uploadaxlic" type="file" />
            <label for="uploadaxlic" class="button icon-upload svg" id="uploadaxlic" title="<?php p($l->t('Upload new axlic')) ?>"></label>
            <div data-toggle="tooltip" data-original-title="<?php p($l->t('Reset to default')); ?>" class="theme-undo icon icon-history"></div>
        </div>
        <div id="uploadaxlicName"></div>
        <br />
        <p><button id="cadviewerSaveAxlic" class="button"><?php p($l->t("Save")) ?></button></p>
        <br />
	</div>
    

    <!-- <h2><?php p($l->t("Common settings")) ?></h2>

    <div id="cadviewerAddrSettings">
        <p class="settings-hint"><?php p($l->t("Pasting in the Licence key input the portion of the cvlicense.js file")) ?></p>

        <p><?php p($l->t("Licence key")) ?></p>
        <p><input id="cadviewerLicenceKey" value="<?php p($_["licenceKey"]) ?>" placeholder="00110010 00110010 00110000 00110001 00110010 00110000 00110100 00110001 00110100 00111000 00110001 00110100 00110101 00110001 00110101 00110111 00110001 00110101 00111001 00110001 00110100 00111000 00110001 00110101 00110010 00110001 00110100 00110101 00110001 00110100 00110001 00110001 00110100 00110000 00110001 00111001 00110111 00110010 00110000 00110111 00110010 00110000 00110110 00110010 00110000 00110001 00110010 00110001 00110000 00110010 00110000 00111000 00110010 00110001 00110000 00110010 00110000 00111000 00110010 00110001 00110000 00110010 00110000 00110111 00110001 00111001 00111000 00110010 00110000 00110110 00110010 00110000 00111000 00110010 00110000 00110110 00110010 00110000 00110101 00110010 00110001 00110001 00110010 00110000 00111000 00110010 00110000 00110111 00110010 00110001 00110001 00110010 00110000 00110101 00110010 00110000 00110111 00110001 00111001 00111000 00110001 00110100 00110001 00110001 00110100 00110100 00110001 00110101 00111001 00110001 00110101 00110111 00110001 00110101 00110101" type="text"></p>
    </div>
    <br />
    <p><button id="cadviewerSave" class="button"><?php p($l->t("Save")) ?></button></p> -->
</div>
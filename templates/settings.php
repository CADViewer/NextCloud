<?php

style("cadviewer", "settings");
style("cadviewer", "template");
OCP\Util::addScript('cadviewer/settings', 'script');

?>
<div class="section section-cadviewer section-cadviewer-addr">
    <h1>
        <?= $_["name"] ?> <span style="font-size: 20px; font-weight: 500;"><?= $_["version"] ?></span>
        <a target="_blank" class="icon-info svg" title=""
            href="https://github.com/CADViewer/NextCloud/blob/main/README.md"
            data-original-title="<?php p($l->t("Documentation")) ?>"></a>
    </h1>

    <h2>
        <?php p($l->t("Licence Keys")) ?>
    </h2>

    <div>
        <div id="cadviewerAddrSettings">

            <p>
                <?php p($l->t("Cadviewer licence key")) ?>
            </p>
            <p><input id="cadviewerLicenceKey" value="<?php p($_["licenceKey"]) ?>"
                    placeholder="00110010 00110010 00110000 00110001 00110010 00110000 00110100 00110001" type="text">
            </p>
            <br />
            <p class="settings-hint">
                <?php p($l->t("Pasting in the Licence key input the portion of the cvlicense.js file")) ?>
            </p>
        </div>
        <p><button id="cadviewerSave" class="button">
                <?php p($l->t("Apply Key")) ?>
            </button></p>
        <br />
    </div>

    <h2>
        <?php p($l->t("Cadviewer icons Skin")) ?>
    </h2>
    <div>
        <div id="">

            <p>
                <label for="skin"><?php p($l->t("Cadviewer icons Skin")) ?></label>
            </p>
            <p>
                <select name="skin" id="skin" value="<?php p($_["skin"]) ?>">
                    <option value="deepblue">Deep Blue</option>
                    <option value="black">Black</option>
                    <option value="lightgray">Light Gray</option>
                    <!-- <option value="nextcloud" disabled>Current nextcloud colors</option> -->
                </select>
            </p>
            <br />
        </div>
        <p>
            <button id="cadviewerSkinSave" class="button">
                <?php p($l->t("Apply Skin")) ?>
            </button>
        </p>
        <br />
    </div>

    <!-- <h2><?php p($l->t("AutoXchange license key")) ?></h2> -->

    <div>
        <div class="uploadButton">
            <label for="uploadaxlic"><span>
                    <?php p($l->t('AutoXchange license key')) ?>
                </span></label>
            <input id="uploadaxlic" class="fileupload" name="uploadaxlic" type="file" />
            <label for="uploadaxlic" class="button icon-upload svg" id="uploadaxlic"
                title="<?php p($l->t('Upload new axlic')) ?>"></label>
            <div data-toggle="tooltip" data-original-title="<?php p($l->t('Reset to default')); ?>"
                class="theme-undo icon icon-history"></div>
        </div>
        <div id="uploadaxlicName"></div>
        <br />
        <p><button id="cadviewerSaveAxlic" class="button">
                <?php p($l->t("Apply Key")) ?>
            </button></p>
        <br />
    </div>

    <h2>
        <?php p($l->t("Credentials for License Key")) ?>
    </h2>
    <div id="verification-value" style="display: none;">
        <div class="content-verification">
            <div>
                <b><?php p($l->t("Content of verification")) ?></b>:
            </div>
            <pre id="verifyOutput">
                <?php p($_["autoexchange"]["output"]) ?>
            </pre>
        </div>
        <br />
        <div class="content-url">
            <b><?php p($l->t("URL of the installation")) ?></b>: <span id="installationUrl">
                <?php p($_["autoexchange"]["domaine_url"]) ?>
            </span>
        </div>
        <div class="content-url">
            <b><?php p($l->t("Nextcloud instance ID")) ?></b>: <span id="instanceID">
                <?php p($_["autoexchange"]["instance_id"]) ?>
            </span>
        </div>
        <br />
    </div>
    <p>
        <button id="getLicenseKeyInfo" class="button">
            <?php p($l->t("Get Server Credentials for License Key")) ?>
        </button>
    </p>
    <br />

    <h2>
        <?php p($l->t("Flush Cache")) ?>
    </h2>

    <div>
        <div>
            <p class="settings-hint">
                <?php p($l->t("Remove cached content, so all conversions will redone")) ?>
            </p>
        </div>
        <p>
            <button id="flushCache" class="button">
                <?php p($l->t("Flush cache")) ?>
            </button>
        </p>
        <br />
    </div>

    <h2>
        <?php p($l->t("Debug")) ?>
    </h2>

    <div>
        <div>
            <p class="settings-hint">
                <?php p($l->t("The Cadviewer Doctor button is an option created to facilitate debugging during the installation and configuration of Cadviewer. This tool will allow an analysis of the elements that are essential to the proper functioning of the application.")) ?>
            </p>
        </div>
        <div id="cadviewerDoctorResponse">
        </div>
        <p>
            <button id="cadviewerDoctor" class="button">
                <?php p($l->t("Cadviewer Doctor")) ?>
            </button>
        </p>
        <br />
    </div>
    <h2>
        <?php p($l->t("Api Conversion log")) ?>
    </h2>

    <div>
        <div>
            <p>
                <?php p($l->t("View the contents of the conversion API log file.")) ?>
            </p>
        </div>
        <br />
        <p>
            <button id="displayLog" class="button">
                <?php p($l->t("Display log")) ?>
            </button>
            <button id="downloadLog" class="button">
                <?php p($l->t("Download log")) ?>
            </button>
        </p>
        <br />
    </div>
    <h2>
        <?php p($l->t("Font Mapping Controls")) ?>
    </h2>
    <div>
        <div>
            <p>
                <?php p($l->t("See the documentation on how to control Font Mapping in AutoXchange CAD converter: ")) ?><a href="https://tailormade.com/ax2020techdocs/operation/fontmapping/" target="_blank"><?php p($l->t("Font Mapping")) ?></a>
            </p>
        </div>
        <br />
        <div>
            <textarea id="fontMap" style="width: 100%" rows="7"><?=p($_["ax_font_map"])?></textarea>
        </div>
        <br />
        <p>
            <button id="saveFontMap" class="button">
                <?php p($l->t("Save")) ?>
            </button>
        </p>
        <br />
    </div>
</div>

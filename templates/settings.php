<?php

style("cadviewer", "settings");
style("cadviewer", "template");
OCP\Util::addScript('cadviewer/settings', 'script');

?>
<div class="section section-cadviewer section-cadviewer-addr">
    <h1>
        <?= $_["name"] ?> <span style="font-size: 20px; font-weight: 500;"><?= $_["version"] ?></span>
        <a target="_blank" class="icon-info svg" title=""
            href="https://cadviewer.com/cadviewerusermanual/referenceapplications/nextcloud/admin"
            data-original-title="<?php p($l->t("Documentation")) ?>"></a>
    </h1>
    <h2>
        <?php p($l->t("Cadviewer icons Skin")) ?>
        <a target="_blank" class="icon-info svg" title=""
            href="https://cadviewer.com/cadviewerusermanual/referenceapplications/nextcloud/admin/#cadviewer-icons-skin"
            data-original-title="<?php p($l->t("Documentation")) ?>"></a>
    </h2>
    <div>
        <div id="">

            <p>
                <label for="skin"><?php p($l->t("Cadviewer icons Skin")) ?></label>
            </p>
            <p>
                <select name="skin" id="skin">
                    <option value="deepblue" <?php echo $_["skin"] == "deepblue" ? 'selected="selected"' : "" ?>><?php p($l->t("Deep Blue")); ?></option>
                    <option value="black" <?php echo $_["skin"] == "black" ? 'selected="selected"' : "" ?>><?php p($l->t("Black")); ?></option>
                    <option value="lightgray" <?php echo $_["skin"] == "lightgray" ? 'selected="selected"' : "" ?>><?php p($l->t("Light Gray")); ?></option>
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

    <h2>
        <?php p($l->t("Licence Keys")) ?>
        <a target="_blank" class="icon-info svg" title=""
            href="https://cadviewer.com/cadviewerusermanual/referenceapplications/nextcloud/admin/#apply-license-keys"
            data-original-title="<?php p($l->t("Documentation")) ?>"></a>
    </h2>

    <div>
        <div id="cadviewerAddrSettings">

            <div class="uploadButton">
                <label for="cadviewerLicenceKey"><span>
                    <?php p($l->t('License key cvlicense.js')) ?>
                </span></label>
                <input id="cadviewerLicenceKey" class="fileupload" name="cadviewerLicenceKey" type="file" />
                <label for="cadviewerLicenceKey" class="button icon-upload svg"
                    title="<?php p($l->t('Upload new cvlicense.js')) ?>"></label>
                <div data-toggle="tooltip" data-original-title="<?php p($l->t('Reset to default')); ?>"
                    class="theme-undo icon icon-history"></div>
            </div>
            <div id="uploadCvlicenseName"></div>
            <br />
        </div>
        <p>
            <button id="cadviewerSave" class="button">
                <?php p($l->t("Apply Key")) ?>
            </button>
        </p>
        <br />
    </div>

    <!-- <h2><?php p($l->t("AutoXchange license key")) ?></h2> -->

    <div>
        <div class="uploadButton">
            <label for="uploadaxlic"><span>
                    <?php p($l->t('AutoXchange license axlic.key')) ?>
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
        <div class="content-url">
            <b><?php p($l->t("Licensed to")) ?></b>: <span id="licensedTo"></span>
        </div>
        <div class="content-url" style="display: none;" id="expirationTime">
            <b><span><?php p($l->t("License key expires in xx Days, ")) ?></span> <a href="https://cadviewer.com/contact/" target="_blank" style="text-decoration: underline;"><?php p($l->t("Please renew")) ?></a></b>
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
        <a target="_blank" class="icon-info svg" title=""
            href="https://cadviewer.com/cadviewerusermanual/referenceapplications/nextcloud/admin/#flush-cache"
            data-original-title="<?php p($l->t("Documentation")) ?>"></a>
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
        <a target="_blank" class="icon-info svg" title=""
            href="https://cadviewer.com/cadviewerusermanual/referenceapplications/nextcloud/admin/#debug-installation-setup"
            data-original-title="<?php p($l->t("Documentation")) ?>"></a>
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
        <a target="_blank" class="icon-info svg" title=""
            href="https://cadviewer.com/cadviewerusermanual/referenceapplications/nextcloud/admin/#api-conversion-log"
            data-original-title="<?php p($l->t("Documentation")) ?>"></a>
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
        <a target="_blank" class="icon-info svg" title=""
            href="https://cadviewer.com/cadviewerusermanual/referenceapplications/nextcloud/admin/#font-mapping-controls"
            data-original-title="<?php p($l->t("Documentation")) ?>"></a>
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

    <h2>
        <?php p($l->t("Unmapped Fonts")) ?>
    </h2>
    <div>
        <div>
            <p>
                <?php p($l->t("Listing of unmapped fonts in last conversion:")) ?>
            </p>
        </div>
        <br />
        <div>
            <textarea style="width: 100%" rows="7"><?=p($_["ax_font_unmapped"])?></textarea>
        </div>
        <br />
    </div>


    <h2>
        <?php p($l->t("Upload SHX font files")) ?>
    </h2>

    <div>
        <div id="">

            <div class="uploadButton">
                <label for="shxFile"><span>
                    <?php p($l->t('upload *.shx files:')) ?>
                </span></label>
                <input id="shxFile" class="fileupload" name="shxFile" type="file" />
                <label for="shxFile" class="button icon-upload svg"
                    title="<?php p($l->t('upload *.shx files:')) ?>"></label>
                <div data-toggle="tooltip" data-original-title="<?php p($l->t('Reset to default')); ?>"
                    class="theme-undo icon icon-history"></div>
            </div>
            <div id="uploadShxFileName"></div>
            <br />
        </div>
        <p>
            <button id="cadviewerSaveShxFile" class="button">
                <?php p($l->t("Upload")) ?>
            </button>
        </p>
        <br />
    </div>
    <h2  style="display: flex; justify-content:space-between">
        <div>
            <?php p($l->t("Conversion parameters")) ?>
            <a target="_blank" class="icon-info svg" title=""
                href="https://cadviewer.com/cadviewerusermanual/referenceapplications/nextcloud/admin/#conversion-parameter-controls"
                data-original-title="<?php p($l->t("Documentation")) ?>"></a>
        </div>
        <button id="newLineParametersConversion" class="button">
            <?php p($l->t("New line")) ?>
        </button>
    </h2>
    <div style="max-width: 900px;">
        <div>
            <p>
                <?php p($l->t("Conversion parameters (User Controlled):")) ?>
            </p>
            <br />
        </div>
        <div id="conversion_control">
            <?php foreach ($_["parameters"] as $key => $value) { ?>
                <div class="grid_input_4">
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px"><?php p($l->t("Parameter:")) ?></span>
                        <input style="margin-left: 0px" id="parameter_conversion_<?=$key+1?>" value="<?=p($value["parameter_conversion"])?>" placeholder="" type="text">
                        
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 70px"><?php p($l->t("(Value):")) ?></span>
                        <input style="margin-left: 0px"  id="value_conversion_<?=$key+1?>" value="<?=p($value["value_conversion"])?>" placeholder="" type="text">
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px"><?php p($l->t("Folder:")) ?></span>
                        <input style="margin-left: 0px" id="folder_conversion_<?=$key+1?>" value="<?=p($value["folder_conversion"])?>" placeholder="/ or *" type="text">
                        <div class="exclude-block <?php if ($value["excluded_folder_conversion"]): ?> checked <?php endif; ?>">
                            <div class="exclude-block-checkbox">
                                <input type="checkbox"<?php if ($value["excluded_folder_conversion"]): ?> checked="checked"<?php endif; ?> id="checkbox_conversion_<?=$key+1?>" class="checkbox" />
                                <label for="checkbox_conversion_<?=$key+1?>"><?php p($l->t('Exclude folder ?'));?></label>
                            </div>
                            <div class="exclude-block-input">
                                <label style="min-width: 80px" for="excluded_folder_conversion_<?=$key+1?>"><?php p($l->t("Folder(s):")) ?></label>
                                <input style="margin-left: 0px" id="excluded_folder_conversion_<?=$key+1?>" value="<?= p($value["excluded_folder_conversion"]) ?>" placeholder="/folder_1,/folder_2" type="text">
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px"><?php p($l->t("User:")) ?></span>
                        <input style="margin-left: 0px" id="user_conversion_<?=$key+1?>" value="<?=p($value["user_conversion"])?>" placeholder="/user_001 or *" type="text">
                        <div class="exclude-block <?php if ($value["excluded_user_conversion"]): ?> checked <?php endif; ?>">
                            <div class="exclude-block-checkbox">
                                <input type="checkbox"<?php if ($value["excluded_user_conversion"]): ?> checked="checked"<?php endif; ?> id="checkbox_conversion_user_<?=$key+1?>" class="checkbox" />
                                <label for="checkbox_conversion_user_<?=$key+1?>"><?php p($l->t('Exclude user ?'));?></label>
                            </div>
                            <div class="exclude-block-input">
                                <label style="min-width: 80px" for="excluded_user_conversion_<?=$key+1?>"><?php p($l->t("User(s):")) ?></label>
                                <input style="margin-left: 0px" id="excluded_user_conversion_<?=$key+1?>" value="<?= p(($value["excluded_user_conversion"])) ?>" placeholder="/user_001,/user_002" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <br />
            <?php } ?>
        </div>
        <p>
            <button id="saveParameters" class="button">
                <?php p($l->t("Save")) ?>
            </button>
        </p>
        <br />
    </div>
    <h2  style="display: flex; justify-content:space-between">
        <div>
            <?php p($l->t("CADViewer Front-End Control Parameters:")) ?>
            <a target="_blank" class="icon-info svg" title=""
                    href="https://cadviewer.com/cadviewerusermanual/referenceapplications/nextcloud/admin/#cadviewer-front-end-control-parameters"
                    data-original-title="<?php p($l->t("Documentation")) ?>"></a>
        </div>
        <button id="newLineParametersFrontend" class="button">
            <?php p($l->t("New line")) ?>
        </button>
    </h2>
    <div style="max-width: 900px;">
        <div id="form_frontend_control">
            <?php foreach ($_["line_weight_factors"] as $key => $value) { ?>
                <div class="grid_input_4">
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px"><?php p($l->t("Parameter:")) ?></span>
                        <input style="margin-left: 0px" disabled id="parameter_frontend_<?=$key+1?>" value="LineWeightFactor" placeholder="" type="text">
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 70px"><?php p($l->t("(Value):")) ?></span>
                        <input style="margin-left: 0px"  id="value_frontend_<?=$key+1?>" value="<?=p($value["value_frontend"])?>" placeholder="100" type="number">
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px"><?php p($l->t("Folder:")) ?></span>
                        <input style="margin-left: 0px" id="folder_frontend_<?=$key+1?>" value="<?=p($value["folder_frontend"])?>" placeholder="/ or *" type="text">
                        <div class="exclude-block <?php if ($value["excluded_folder_frontend"]): ?> checked <?php endif; ?>">
                            <div class="exclude-block-checkbox">
                                <input type="checkbox"<?php if ($value["excluded_folder_frontend"]): ?> checked="checked"<?php endif; ?> id="checkbox_folder_frontend_<?=$key+1?>" class="checkbox" />
                                <label for="checkbox_folder_frontend_<?=$key+1?>"><?php p($l->t('Exclude folder ?'));?></label>
                            </div>
                            <div class="exclude-block-input">
                                <label style="min-width: 80px" for="excluded_folder_frontend_<?=$key+1?>"><?php p($l->t("Folder(s):")) ?></label>
                                <input style="margin-left: 0px" id="excluded_folder_frontend_<?=$key+1?>" value="<?= p($value["excluded_folder_frontend"]) ?>" placeholder="/folder_1,/folder_2" type="text">
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px"><?php p($l->t("User:")) ?></span>
                        <input style="margin-left: 0px" id="user_frontend_<?=$key+1?>" value="<?=p($value["user_frontend"])?>" placeholder="/user_001 or *" type="text">
                        <div class="exclude-block <?php if ($value["excluded_user_frontend"]): ?> checked <?php endif; ?>">
                            <div class="exclude-block-checkbox">
                                <input type="checkbox"<?php if ($value["excluded_user_frontend"]): ?> checked="checked"<?php endif; ?> id="checkbox_user_frontend_<?=$key+1?>" class="checkbox" />
                                <label for="checkbox_user_frontend_<?=$key+1?>"><?php p($l->t('Exclude user ?'));?></label>
                            </div>
                            <div class="exclude-block-input">
                                <label style="min-width: 80px" for="excluded_user_frontend_<?=$key+1?>"><?php p($l->t("User(s):")) ?></label>
                                <input style="margin-left: 0px" id="excluded_user_frontend_<?=$key+1?>" value="<?= p($value["excluded_user_frontend"]) ?>" placeholder="/user_001,/user_002" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <br />
            <?php } ?>
        </div>
        <p>
            <button id="saveParametersFrontend" class="button">
                <?php p($l->t("Save")) ?>
            </button>
        </p>
        <br />
    </div>
</div>

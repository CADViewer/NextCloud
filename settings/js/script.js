(function ($, OC) {
    var htaccessContent = "";
    function download(filename, text) {
        var element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
        element.setAttribute('download', filename);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }

    function checkAutoExchangeLicenceKey  () {
        $(".section-cadviewer").addClass("icon-loading");

        $.ajax({
            method: "GET",
            url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/autoexchange-verify"),
            success: function onSuccess(response) {
                $(".section-cadviewer").removeClass("icon-loading");
                if (response && (response.domaine_url != null)) {
                    document.getElementById("verification-value").style.display = "block"
                    let installationUrl = response.domaine_url;
                    let verifyOutput = response.output;
                    let instanceID = response.instance_id;
                    
                    $("#installationUrl").html(installationUrl);
                    $("#verifyOutput").html(verifyOutput);
                    $("#instanceID").html(instanceID);
                }
            }
        });
    }

    $(document).ready(function () {
        OCA.Cadviewer = _.extend({}, OCA.Cadviewer);

        if (!OCA.Cadviewer.AppName) {
            OCA.Cadviewer = {
                AppName: "cadviewer"
            };
        }

        $("#cadviewerSkinSave").click(function () {
            $(".section-cadviewer").addClass("icon-loading");

            var skin = $("#skin").val().trim();

            $.ajax({
                method: "PUT",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/skin"),
                data: {
                    skin: skin,
                },
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response && (response.skin != null)) {
                        $("#skin").val(response.skin);

                        var versionMessage = response.version ? (" (" + t(OCA.Cadviewer.AppName, "version") + " " + response.version + ")") : "";

                        if (response.error) {
                            OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to connect") + " (" + response.error + ")" + versionMessage);
                        } else {
                            OCP.Toast.success(t(OCA.Cadviewer.AppName, "Settings have been successfully updated") + versionMessage);
                        }
                    }
                }
            });
        });

        $("#cadviewerSave").click(function () {

            if (!licenceKeyFile) return;
            $(".section-cadviewer").addClass("icon-loading");

            // Create a new FormData object
            let formData = new FormData();

            // Add the file to the FormData object
            formData.append('file', licenceKeyFile);

            // Send the file data using an AJAX POST request
            $.ajax({
                method: "POST",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/common"),
                data: formData,
                processData: false,
                contentType: false,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response && (response.licenceKey != null)) {
                        $("#cadviewerLicenceKey").val(response.licenceKey);

                        var versionMessage = response.version ? (" (" + t(OCA.Cadviewer.AppName, "version") + " " + response.version + ")") : "";

                        if (response.error) {
                            OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to connect") + " (" + response.error + ")" + versionMessage);
                        } else {
                            OCP.Toast.success(t(OCA.Cadviewer.AppName, "Settings have been successfully updated") + versionMessage);
                        }
                    }
                }
            });
        });
        
        $("#saveParameters").click(function () {
            
            var parameter_1 = $("#parameter_1").val().trim();
            var parameter_2 = $("#parameter_2").val().trim();
            var parameter_3 = $("#parameter_3").val().trim();
            var parameter_4 = $("#parameter_4").val().trim();
            var parameter_5 = $("#parameter_5").val().trim();
            var parameter_6 = $("#parameter_6").val().trim();
            var parameter_7 = $("#parameter_7").val().trim();
            var parameter_8 = $("#parameter_8").val().trim();
            var parameter_9 = $("#parameter_9").val().trim();

            var value_1 = $("#value_1").val().trim();
            var value_2 = $("#value_2").val().trim();
            var value_3 = $("#value_3").val().trim();
            var value_4 = $("#value_4").val().trim();
            var value_5 = $("#value_5").val().trim();
            var value_6 = $("#value_6").val().trim();
            var value_7 = $("#value_7").val().trim();
            var value_8 = $("#value_8").val().trim();
            var value_9 = $("#value_9").val().trim();

            $(".section-cadviewer").addClass("icon-loading");

            $.ajax({
                method: "PUT",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/parameters"),
                data: {
                    parameter_1, parameter_2,  parameter_3, parameter_4, 
                    parameter_5, parameter_6, parameter_7, parameter_8, parameter_9,
                    value_1, value_2, value_3,
                    value_4, value_5, value_6, value_7, value_8, value_9
                },
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response && (response.parameter_1 != null)) {
                        $("#parameter_1").val(response.parameter_1);
                        $("#parameter_2").val(response.parameter_2);
                        $("#parameter_3").val(response.parameter_3);
                        $("#parameter_4").val(response.parameter_4);
                        $("#parameter_5").val(response.parameter_5);
                        $("#parameter_6").val(response.parameter_6);
                        $("#parameter_7").val(response.parameter_7);
                        $("#parameter_8").val(response.parameter_8);
                        $("#parameter_9").val(response.parameter_9);
                        $("#value_1").val(response.value_1);
                        $("#value_2").val(response.value_2);
                        $("#value_3").val(response.value_3);
                        $("#value_4").val(response.value_4);
                        $("#value_5").val(response.value_5);
                        $("#value_6").val(response.value_6);
                        $("#value_7").val(response.value_7);
                        $("#value_8").val(response.value_8);
                        $("#value_9").val(response.value_9);

                        var versionMessage = response.version ? (" (" + t(OCA.Cadviewer.AppName, "version") + " " + response.version + ")") : "";

                        if (response.error) {
                            OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to connect") + " (" + response.error + ")" + versionMessage);
                        } else {
                            OCP.Toast.success(t(OCA.Cadviewer.AppName, "Settings have been successfully updated") + versionMessage);
                        }
                    }
                }
            });
        });

        $("#saveParametersFrontend").click(function () {
            
            var value_frontend_1 = $("#value_frontend_1").val().trim();
            if (!(parseInt(value_frontend_1) > 0)){
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "LineWeightFactor must be bigger than 0"));
                return;
            }
            $(".section-cadviewer").addClass("icon-loading");

            $.ajax({
                method: "PUT",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/frontend_parameters"),
                data: {
                    value_frontend_1
                },
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response && (response.value_frontend_1 != null)) {
                        $("#value_frontend_1").val(response.value_frontend_1);

                        var versionMessage = response.version ? (" (" + t(OCA.Cadviewer.AppName, "version") + " " + response.version + ")") : "";

                        if (response.error) {
                            OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to connect") + " (" + response.error + ")" + versionMessage);
                        } else {
                            OCP.Toast.success(t(OCA.Cadviewer.AppName, "Settings have been successfully updated") + versionMessage);
                        }
                    }
                }
            });
        });

        var axlicFile;
        var licenceKeyFile;
        
        $('#uploadaxlic').change(function() {
            let pattern = /axlic(.*)\.key/gm;
            if (!pattern.test(this.files[0].name)) {
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "Invalid licence key file"));
                return;
            }
            axlicFile = this.files[0];
            $("#uploadaxlicName").html(axlicFile.name);
        });

        $('#cadviewerLicenceKey').change(function() {
            let pattern = /cvlicense(.*)\.js/gm;
            if (!pattern.test(this.files[0].name)) {
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "Invalid licence key file"));
                return;
            }
            licenceKeyFile = this.files[0];
            $("#uploadCvlicenseName").html(licenceKeyFile.name);
        });        

        // upload the licence key file when click on save button
        $("#cadviewerSaveAxlic").click(function () {
            if (!axlicFile) return;
            $(".section-cadviewer").addClass("icon-loading");

            // Create a new FormData object
            let formData = new FormData();

            // Add the file to the FormData object
            formData.append('file', axlicFile);

            // Send the file data using an AJAX POST request
            $.ajax({
                method: "POST",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/autoexchange-save-axlic"),
                data: formData,
                processData: false,
                contentType: false,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    var versionMessage = response.version ? (" (" + t(OCA.Cadviewer.AppName, "version") + " " + response.version + ")") : "";

                    if (response.error) {
                        OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to save licence key") + " (" + versionMessage+ ")");
                    } else {
                        checkAutoExchangeLicenceKey();
                        OCP.Toast.success(t(OCA.Cadviewer.AppName, "AutoExchange licence key have been successfully saved") + versionMessage);
                    }
                }
            });
        });

        $("#displayLog").click(function () {
            $(".section-cadviewer").addClass("icon-loading");

            $.ajax({
                method: "POST",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/log"),
                data: {},
                processData: false,
                contentType: false,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response.error) {
                        OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to display Api conversion log"));
                    } else {
                        OC.dialogs.message(
                            response.log_content,
                            t(OCA.Cadviewer.AppName, "Api Conversion log")
                        )
                    }
                }
            });
        });

        $("#downloadLog").click(function () {
            $(".section-cadviewer").addClass("icon-loading");

            $.ajax({
                method: "POST",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/log"),
                data: {},
                processData: false,
                contentType: false,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response.error) {
                        OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to download Api conversion log"));
                    } else {
                        download("call-Api_Conversion_log.txt", response.log_content);
                    }
                }
            });
        });


        $("#cadviewerDoctor").click(function () {
            $(".section-cadviewer").addClass("icon-loading");

            $.ajax({
                method: "POST",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/doctor"),
                data: {},
                processData: false,
                contentType: false,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");

                    if (response.error) {
                        OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to debug the application"));
                    } else {
                        htaccessContent =  response.htaccess_content;
                        let responseContent = "";
                        responseContent += `<div class="access-status"><div class="${response.exec_command_is_activate ? "success" : "error"}"><img src="/apps/cadviewer/img/${response.exec_command_is_activate ? "check" : "x"}.svg" /></div> ${t(OCA.Cadviewer.AppName, '"exec" command activated on PHP')}</div>`;
                        responseContent += `<div class="access-status"><div class="${response.can_execute_script_file ? "success" : "error"}"><img src="/apps/cadviewer/img/${response.can_execute_script_file ? "check" : "x"}.svg" /></div> ${t(OCA.Cadviewer.AppName, 'Permission 777 for the "ax2023_L64_xx_yy_zz" executable')}</div>`;
                        responseContent += `<div class="access-status"><div class="${response.can_write_in_log_file ? "success" : "error"}"><img src="/apps/cadviewer/img/${response.can_write_in_log_file ? "check" : "x"}.svg" /></div> ${t(OCA.Cadviewer.AppName, 'Writing to the log file "call-Api_Conversion_log.txt"')}</div>`;
                        responseContent += `<div class="access-status">
                                <div class="${response.htaccess_is_whell_configured ? "success" : "error"}">
                                    <img src="/apps/cadviewer/img/${response.htaccess_is_whell_configured ? "check" : "x"}.svg" />
                                </div> ${t(OCA.Cadviewer.AppName, '.htaccess well configured')} 
                                <button id="downloadHtaccess" class="button" style="margin-left: 10px">
                                    ${t(OCA.Cadviewer.AppName, "Download")}
                                </button>
                        </div>`;
                        responseContent += `<div class="access-status">
                                <div class="${response.can_write_in_files_folder ? "success" : "error"}">
                                    <img src="/apps/cadviewer/img/${response.can_write_in_files_folder ? "check" : "x"}.svg" />
                                </div> 
                                ${t(OCA.Cadviewer.AppName, 'Read and write permission for folder')} /converter/converters/files/
                            </div>`;
                        
                        const otherFolders = [
                            "linux",
                            "merge",
                            "print",
                            "pdf",
                            "redlines",
                            "redlines_v7",
                            "php"
                        ]
                        const otherFoldersPath = {
                            "linux": "/converter/converters/ax2024/linux/",
                            "merge": "/converter/converters/files/merged/",
                            "print": "/converter/converters/files/print/",
                            "pdf": "/converter/converters/files/pdf/",
                            "redlines": "/converter/content/redlines/",
                            "redlines_v7": "/converter/content/redlines/v7/",
                            "php": "/converter/php/"
                        }
                        otherFolders.forEach(folder => {
                            responseContent += `<div class="access-status">
                                <div class="${response["can_write_in_files_folder_"+folder] ? "success" : "error"}">
                                    <img src="/apps/cadviewer/img/${response["can_write_in_files_folder_"+folder] ? "check" : "x"}.svg" />
                                </div> 
                                ${t(OCA.Cadviewer.AppName, 'Read and write permission for folder')}${" "+otherFoldersPath[folder]}
                            </div>`;
                        });
                        $("#cadviewerDoctorResponse").html(responseContent);

                        $("#downloadHtaccess").click(function() {
                            download(".htaccess", htaccessContent);
                        });
                    }
                }
            });
        });

        $("#flushCache").click(function () {
            $(".section-cadviewer").addClass("icon-loading");
            $.ajax({
                method: "POST",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/cadviewer/flush-cache"),
                data: {},
                processData: false,
                contentType: false,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");

                    if (response && response.error) {
                        OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to flush cache"));
                    } else {
                        checkAutoExchangeLicenceKey();
                        OCP.Toast.success(t(OCA.Cadviewer.AppName, "Cache have been successfully flushed"));
                    }
                }
            });
            
        });

        $("#saveFontMap").click(function () {
            $(".section-cadviewer").addClass("icon-loading");

            $.ajax({
                method: "POST",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/save-font-map"),
                data: {
                   "ax_font_map": $("#fontMap").val()
                },
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response.error) {
                        OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to update font map"));
                    } else {
                        OCP.Toast.success(t(OCA.Cadviewer.AppName, "Font map successfully saved"));
                    }
                }
            });
        });

        // get the status of AutoExchange licence

        $("#getLicenseKeyInfo").click(function () {
            checkAutoExchangeLicenceKey();
        });


    });

})(jQuery, OC);

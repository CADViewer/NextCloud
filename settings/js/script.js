(function ($, OC) {
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

        $("#cadviewerSave").click(function () {
            $(".section-cadviewer").addClass("icon-loading");

            var cadviewerLicenceKey = $("#cadviewerLicenceKey").val().trim();

            $.ajax({
                method: "PUT",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/common"),
                data: {
                    licenceKey: cadviewerLicenceKey,
                },
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

        var axlicFile;
        
        $('#uploadaxlic').change(function() {
            axlicFile = this.files[0];
            $("#uploadaxlicName").html(axlicFile.name);
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
                        let responseContent = "";
                        responseContent += `<div class="access-status"><div class="${response.exec_command_is_activate ? "success" : "error"}"><img src="/apps/cadviewer/img/${response.exec_command_is_activate ? "check" : "x"}.svg" /></div> ${t(OCA.Cadviewer.AppName, '"exec" command activated on PHP')}</div>`;
                        responseContent += `<div class="access-status"><div class="${response.can_execute_script_file ? "success" : "error"}"><img src="/apps/cadviewer/img/${response.can_execute_script_file ? "check" : "x"}.svg" /></div> ${t(OCA.Cadviewer.AppName, 'Permission 777 for the "ax2023_L64_xx_yy_zz" executable')}</div>`;
                        responseContent += `<div class="access-status"><div class="${response.can_write_in_log_file ? "success" : "error"}"><img src="/apps/cadviewer/img/${response.can_write_in_log_file ? "check" : "x"}.svg" /></div> ${t(OCA.Cadviewer.AppName, 'Writing to the log file "call-Api_Conversion_log.txt"')}</div>`;
                        responseContent += `<div class="access-status"><div class="${response.htaccess_is_whell_configured ? "success" : "error"}"><img src="/apps/cadviewer/img/${response.htaccess_is_whell_configured ? "check" : "x"}.svg" /></div> ${t(OCA.Cadviewer.AppName, '.htaccess well configured')}</div>`;
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

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
        


        var axlicFile;
        var licenceKeyFile;
        var shxFile;
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
        
        $('#shxFile').change(function() {
            let pattern = /(.*)\.shx/gm;
            if (!pattern.test(this.files[0].name)) {
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "Invalid file"));
                return;
            }
            shxFile = this.files[0];
            $("#uploadShxFileName").html(shxFile.name);
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

        $("#cadviewerSaveShxFile").click(function () {
            if (!shxFile) return;
            $(".section-cadviewer").addClass("icon-loading");

            // Create a new FormData object
            let formData = new FormData();

            // Add the file to the FormData object
            formData.append('file', shxFile);

            // Send the file data using an AJAX POST request
            $.ajax({
                method: "POST",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/shx-file"),
                data: formData,
                processData: false,
                contentType: false,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");

                    if (response.error) {
                        OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to save file"));
                    } else {
                        shxFile = null;
                        $("#uploadShxFileName").html("");
                        OCP.Toast.success(t(OCA.Cadviewer.AppName, "File have been successfully saved"));
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

        

        $("#newLineParametersFrontend").click(function () {
            const current = $("#form_frontend_control .grid_input_4").length + 1;
            const new_form = `
                <div class="grid_input_4">
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px">${t(OCA.Cadviewer.AppName, "Parameter:")}</span>
                        <input style="margin-left: 0px" id="parameter_frontend_${current}" value="LineWeightFactor" placeholder="" type="text">
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 70px">${t(OCA.Cadviewer.AppName, "(Value):")}</span>
                        <input style="margin-left: 0px"  id="value_frontend_${current}" value="100" placeholder="100" type="number">
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px">${t(OCA.Cadviewer.AppName, "Folder:")}</span>
                        <input style="margin-left: 0px" id="folder_frontend_${current}" value="" placeholder="/ or *" type="text">
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px">${t(OCA.Cadviewer.AppName, "User:")}</span>
                        <input style="margin-left: 0px" id="user_frontend_${current}" value="" placeholder="/user_001 or *" type="text">
                    </div>
                </div>
                <br />
            `;

            $("#form_frontend_control").append(new_form);
        });

        $("#newLineParametersConversion").click(function () {

            const current = $("#conversion_control .grid_input_4").length + 1;
            const new_form = `
                <div class="grid_input_4">
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px">${t(OCA.Cadviewer.AppName, "Parameter:")}</span>
                        <input style="margin-left: 0px" id="parameter_conversion_${current}" value="" placeholder="" type="text">
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 70px">${t(OCA.Cadviewer.AppName, "(Value):")}</span>
                        <input style="margin-left: 0px"  id="value_conversion_${current}" value="" placeholder="100" type="number">
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px">${t(OCA.Cadviewer.AppName, "Folder:")}</span>
                        <input style="margin-left: 0px" id="folder_conversion_${current}" value="" placeholder="/ or *" type="text">
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px">${t(OCA.Cadviewer.AppName, "User:")}</span>
                        <input style="margin-left: 0px" id="user_conversion_${current}" value="" placeholder="/user_001 or *" type="text">
                    </div>
                </div>
                <br />
            `;

            $("#conversion_control").append(new_form);

        });
        $("#saveParameters").click(function () {
            const current = $("#conversion_control .grid_input_4").length;
            let data = {};
            console.log(current)

            var parameter_conversion_1 = $("#parameter_conversion_1").val().trim();
            var value_conversion_1 = $("#value_conversion_1").val().trim();
            var folder_conversion_1 = $("#folder_conversion_1").val().trim();
            var user_conversion_1 = $("#user_conversion_1").val().trim();
            
            data['parameter_conversion_1'] = parameter_conversion_1;
            data['value_conversion_1'] = value_conversion_1;
            data['folder_conversion_1'] = folder_conversion_1 || "*";
            data['user_conversion_1'] = user_conversion_1 || "*";
            data['length'] = 1;
            if (value_conversion_1 !== "" && !(parseInt(value_conversion_1) > 0)){
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "value must be bigger than 0"));
                return;
            }
            for(let i=2; i<current+1; i++){
                var parameter_conversion = $("#parameter_conversion_"+i).val().trim();
                var value_conversion = $("#value_conversion_"+i).val().trim();
                var folder_conversion = $("#folder_conversion_"+i).val().trim()  || "*";
                var user_conversion = $("#user_conversion_"+i).val().trim()  || "*";
                if (value_conversion !== "" && !(parseInt(value_conversion) > 0)){
                    continue;
                }
                if (parameter_conversion){
                    data['parameter_conversion_'+i] = parameter_conversion;
                    data["value_conversion_"+i] = value_conversion;
                    data["folder_conversion_"+i] = folder_conversion;
                    data["user_conversion_"+i] = user_conversion;
                    data['length'] += 1;
                }
            }
            $(".section-cadviewer").addClass("icon-loading");

            $.ajax({
                method: "PUT",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/parameters"),
                data: data,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response) {
                        
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
            const current = $("#form_frontend_control .grid_input_4").length;
            let data = {};

            var value_frontend_1 = $("#value_frontend_1").val().trim();
            var folder_frontend_1 = $("#folder_frontend_1").val().trim();
            var user_frontend_1 = $("#user_frontend_1").val().trim();
            data['value_frontend_1'] = value_frontend_1;
            data['folder_frontend_1'] = folder_frontend_1 || "*";
            data['user_frontend_1'] = user_frontend_1 || "*";
            data['length'] = 1;
            if (!(parseInt(value_frontend_1) > 0)){
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "LineWeightFactor must be bigger than 0"));
                return;
            }
            for(let i=2; i<current+1; i++){
                var value_frontend = $("#value_frontend_"+i).val().trim();
                var folder_frontend = $("#folder_frontend_"+i).val().trim() || "*";
                var user_frontend = $("#user_frontend_"+i).val().trim()  || "*";
                if (!(parseInt(value_frontend) > 0)){
                    continue;
                }
                data["value_frontend_"+i] = value_frontend;
                data["folder_frontend_"+i] = folder_frontend;
                data["user_frontend_"+i] = user_frontend;
                data['length'] += 1;
            }
            $(".section-cadviewer").addClass("icon-loading");

            $.ajax({
                method: "PUT",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/frontend_parameters"),
                data: data,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response) {

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
    });

})(jQuery, OC);

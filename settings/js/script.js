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
                    let licensedTo = response.licensee;
                    let expirationTime = response.expiration_time;
                    let numberOfUsers = response.number_of_users;
                    let users = response.users;

                    $("#installationUrl").html(installationUrl);
                    $("#verifyOutput").html(verifyOutput);
                    if (!verifyOutput){
                        $("#verifyOutputError").css("display", "block");
                    } else {
                        $("#verifyOutputError").css("display", "none");
                    }
                    $("#instanceID").html(instanceID);
                    $("#licensedTo").html(licensedTo);
                    if(expirationTime !== null  && expirationTime > 0) {
                        document.getElementById("expirationTime").style.display = "block"
                        $("#expirationTime span").html($("#expirationTime span").html().replace("xx", expirationTime));
                    }
                    if (numberOfUsers > 0) {
                        document.getElementById("licenceUsersNumber").style.display = "block";
                        $("#licenceUsersNumber span").html($("#licenceUsersNumber span").html().replace("xx", numberOfUsers));
                    }
                    if (numberOfUsers === 0) {
                        document.getElementById("licenceFullServer").style.display = "block";
                    }
                    let html = "";
                    let index = 1;
                    users.forEach(user => {
                        html += `
                            <div class="grid_input_3">
                                <div style="display: flex; align-items: flex-start; flex-direction: column;">
                                    <span style="min-width: 80px">${t(OCA.Cadviewer.AppName,"User")} ${index}:</span>
                                    <input style="margin-left: 0px" id="user_licence_${index}" value="${user}" placeholder="username_001" type="text">
                                </div>
                            </div>
                            <br />
                        `;
                        index += 1;
                    });
                    $("#users_licence_list").html(html);
                    document.getElementById("users_licence_list_container").style.display = numberOfUsers > 0 ? "block" : "none";
                    
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
            let pattern = /axlic(.*)\.key/igm;
            if (!pattern.test(this.files[0].name)) {
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "Invalid licence key file"));
                return;
            }
            axlicFile = this.files[0];
            $("#uploadaxlicName").html(axlicFile.name);
        });

        $('#cadviewerLicenceKey').change(function() {
            let pattern = /cvlicense(.*)\.js/igm;
            if (!pattern.test(this.files[0].name)) {
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "Invalid licence key file"));
                return;
            }
            licenceKeyFile = this.files[0];
            $("#uploadCvlicenseName").html(licenceKeyFile.name);
        });       
        
        $('#shxFile').change(function() {
            let pattern = /(.*)\.shx/igm;
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
                        responseContent += `<div class="access-status"><div class="${response.exec_command_is_activate ? "success" : "error"}"><img src="/custom_apps/cadviewer/img/${response.exec_command_is_activate ? "check" : "x"}.svg" /></div> ${t(OCA.Cadviewer.AppName, '"exec" command activated on PHP')}</div>`;
                        responseContent += `<div class="access-status"><div class="${response.can_execute_script_file ? "success" : "error"}"><img src="/custom_apps/cadviewer/img/${response.can_execute_script_file ? "check" : "x"}.svg" /></div> ${t(OCA.Cadviewer.AppName, 'Permission 750 for the "ax2023_L64_xx_yy_zz" executable')}</div>`;
                        responseContent += `<div class="access-status"><div class="${response.can_write_in_log_file ? "success" : "error"}"><img src="/custom_apps/cadviewer/img/${response.can_write_in_log_file ? "check" : "x"}.svg" /></div> ${t(OCA.Cadviewer.AppName, 'Writing to the log file "call-Api_Conversion_log.txt"')}</div>`;
                        responseContent += `<div class="access-status">
                                <div class="${response.htaccess_is_whell_configured ? "success" : "error"}">
                                    <img src="/custom_apps/cadviewer/img/${response.htaccess_is_whell_configured ? "check" : "x"}.svg" />
                                </div> ${t(OCA.Cadviewer.AppName, '.htaccess well configured')} 
                                <button id="downloadHtaccess" class="button" style="margin-left: 10px">
                                    ${t(OCA.Cadviewer.AppName, "Download")}
                                </button>
                        </div>`;
                        responseContent += `<div class="access-status">
                                <div class="${response.can_write_in_files_folder ? "success" : "error"}">
                                    <img src="/custom_apps/cadviewer/img/${response.can_write_in_files_folder ? "check" : "x"}.svg" />
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
                                    <img src="/custom_apps/cadviewer/img/${response["can_write_in_files_folder_"+folder] ? "check" : "x"}.svg" />
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

        // add on checkbox change event to input dynamicCacheSwitch
        $("#dynamicCacheSwitch").change(function() {
            // call endpoint /toggle-cache-conversion for save new value
            $(".section-cadviewer").addClass("icon-loading");
            $.ajax({
                method: "POST",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/toggle-cache-conversion"),
                data: {
                   "cached_conversion": this.checked
                },
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response.error) {
                        OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to update cache conversion"));
                    } else {
                        OCP.Toast.success(t(OCA.Cadviewer.AppName, "Cache conversion successfully updated"));
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
                        <div class="exclude-block">
                            <div class="exclude-block-checkbox">
                                <input type="checkbox" id="checkbox_folder_frontend_${current}" class="checkbox" />
                                <label for="checkbox_folder_frontend_${current}">${t(OCA.Cadviewer.AppName, 'Exclude folder ?')}</label>
                            </div>
                            <div class="exclude-block-input">
                                <label style="min-width: 80px" for="excluded_folder_frontend_${current}">${t(OCA.Cadviewer.AppName, "Folder(s):")}</label>
                                <input style="margin-left: 0px" id="excluded_folder_frontend_${current}" value="" placeholder="/folder_1,/folder_2" type="text">
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px">${t(OCA.Cadviewer.AppName, "User:")}</span>
                        <input style="margin-left: 0px" id="user_frontend_${current}" value="" placeholder="/user_001 or *" type="text">
                        <div class="exclude-block">
                            <div class="exclude-block-checkbox">
                                <input type="checkbox" id="checkbox_user_frontend_${current}" class="checkbox" />
                                <label for="checkbox_user_frontend_${current}">${t(OCA.Cadviewer.AppName, 'Exclude user ?')}</label>
                            </div>
                            <div class="exclude-block-input">
                                <label style="min-width: 80px" for="excluded_user_frontend_${current}">${t(OCA.Cadviewer.AppName, "User(s):")}</label>
                                <input style="margin-left: 0px" id="excluded_user_frontend_${current}" value="" placeholder="/user_001,/user_002" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <br />
            `;

            $("#form_frontend_control").append(new_form);
            watchCheckboxChange();
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
                        <input style="margin-left: 0px"  id="value_conversion_${current}" value="" placeholder="" type="text">
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px">${t(OCA.Cadviewer.AppName, "Folder:")}</span>
                        <input style="margin-left: 0px" id="folder_conversion_${current}" value="" placeholder="/ or *" type="text">
                        <div class="exclude-block ">
                            <div class="exclude-block-checkbox">
                                <input type="checkbox" id="checkbox_conversion_${current}" class="checkbox" />
                                <label for="checkbox_conversion_${current}">${t(OCA.Cadviewer.AppName, 'Exclude folder ?')}</label>
                            </div>
                            <div class="exclude-block-input">
                                <label style="min-width: 80px" for="excluded_folder_conversion_${current}">${t(OCA.Cadviewer.AppName, "Folder(s):")}</label>
                                <input style="margin-left: 0px" id="excluded_folder_conversion_${current}" value="" placeholder="/folder_1,/folder_2" type="text">
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; flex-direction: column;">
                        <span style="min-width: 80px">${t(OCA.Cadviewer.AppName, "User:")}</span>
                        <input style="margin-left: 0px" id="user_conversion_${current}" value="" placeholder="/user_001 or *" type="text">
                        <div class="exclude-block">
                            <div class="exclude-block-checkbox">
                                <input type="checkbox" id="checkbox_conversion_user_${current}" class="checkbox" />
                                <label for="checkbox_conversion_user_${current}">${t(OCA.Cadviewer.AppName, 'Exclude user ?')}</label>
                            </div>
                            <div class="exclude-block-input">
                                <label style="min-width: 80px" for="excluded_user_conversion_${current}">${t(OCA.Cadviewer.AppName, "User(s):")}</label>
                                <input style="margin-left: 0px" id="excluded_user_conversion_${current}" value="" placeholder="/user_001,/user_002" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <br />
            `;

            $("#conversion_control").append(new_form);
            watchCheckboxChange();
        });
        $("#saveParameters").click(function () {
            const current = $("#conversion_control .grid_input_4").length;
            let data = {};
            console.log(current)

            var parameter_conversion_1 = $("#parameter_conversion_1").val().trim();
            var value_conversion_1 = $("#value_conversion_1").val().trim();
            var folder_conversion_1 = $("#folder_conversion_1").val().trim();
            var user_conversion_1 = $("#user_conversion_1").val().trim();
            var excluded_user_conversion_1 = $("#excluded_user_conversion_1").val().trim();
            var excluded_folder_conversion_1 = $("#excluded_folder_conversion_1").val().trim();
            
            data['parameter_conversion_1'] = parameter_conversion_1;
            data['value_conversion_1'] = value_conversion_1;
            data['folder_conversion_1'] = folder_conversion_1 || "*";
            data['user_conversion_1'] = user_conversion_1 || "*";
            data['excluded_user_conversion_1'] = excluded_user_conversion_1 || "";
            data['excluded_folder_conversion_1'] = excluded_folder_conversion_1 || "";
            data['length'] = 1;
            
            for(let i=2; i<current+1; i++){
                var parameter_conversion = $("#parameter_conversion_"+i).val().trim();
                var value_conversion = $("#value_conversion_"+i).val().trim();
                var folder_conversion = $("#folder_conversion_"+i).val().trim()  || "*";
                var user_conversion = $("#user_conversion_"+i).val().trim()  || "*";
                var excluded_user_conversion = $("#excluded_user_conversion_"+i).val().trim() || "";
                var excluded_folder_conversion = $("#excluded_folder_conversion_"+i).val().trim() || "";
                
                if (parameter_conversion){
                    data['parameter_conversion_'+i] = parameter_conversion;
                    data["value_conversion_"+i] = value_conversion;
                    data["folder_conversion_"+i] = folder_conversion;
                    data["user_conversion_"+i] = user_conversion;
                    data["excluded_user_conversion_"+i] = excluded_user_conversion;
                    data["excluded_folder_conversion_"+i] = excluded_folder_conversion;
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
            var excluded_folder_frontend_1 = $("#excluded_folder_frontend_1").val().trim();
            var excluded_user_frontend_1 = $("#excluded_user_frontend_1").val().trim();

            data['value_frontend_1'] = value_frontend_1;
            data['folder_frontend_1'] = folder_frontend_1 || "*";
            data['user_frontend_1'] = user_frontend_1 || "*";
            data['excluded_folder_frontend_1'] = excluded_folder_frontend_1 || "";
            data['excluded_user_frontend_1'] = excluded_user_frontend_1 || "";
            data['length'] = 1;
            if (!(parseInt(value_frontend_1) > 0)){
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "LineWeightFactor must be bigger than 0"));
                return;
            }
            for(let i=2; i<current+1; i++){
                var value_frontend = $("#value_frontend_"+i).val().trim();
                var folder_frontend = $("#folder_frontend_"+i).val().trim() || "*";
                var user_frontend = $("#user_frontend_"+i).val().trim()  || "*";
                var excluded_folder_frontend = $("#excluded_folder_frontend_"+i).val().trim() || "";
                var excluded_user_frontend = $("#excluded_user_frontend_"+i).val().trim()  || "";
                if (!(parseInt(value_frontend) > 0)){
                    continue;
                }
                data["value_frontend_"+i] = value_frontend;
                data["folder_frontend_"+i] = folder_frontend;
                data["user_frontend_"+i] = user_frontend;
                data["excluded_user_frontend_"+i] = excluded_user_frontend;
                data["excluded_folder_frontend_"+i] = excluded_folder_frontend;
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

        $("#saveZoomImageWallpaperParameters").click(function () {

            const zoom_image_wallpaper_scalefactor = parseFloat($("#zoom_image_wallpaper_scalefactor").val().replace(",", "."));
            const zoom_image_wallpaper_scalebreakpoint = parseFloat($("#zoom_image_wallpaper_scalebreakpoint").val().replace(",", "."));
            if (isNaN(zoom_image_wallpaper_scalefactor) || isNaN(zoom_image_wallpaper_scalebreakpoint)) {
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "Invalid value"));
                return;
            }

            let data = {
                "zoom_image_wallpaper": $("#zoom_image_wallpaper").is(":checked"),
                zoom_image_wallpaper_scalefactor, zoom_image_wallpaper_scalebreakpoint
            };

            $(".section-cadviewer").addClass("icon-loading");

            $.ajax({
                method: "PUT",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/zoom-image-wallpaper"),
                data: data,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response) {

                        var versionMessage = response?.version ? (" (" + t(OCA.Cadviewer.AppName, "version") + " " + response.version + ")") : "";

                        if (response?.error) {
                            OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to connect") + " (" + response.error + ")" + versionMessage);
                        } else {
                            const { zoom_image_wallpaper_scalefactor, zoom_image_wallpaper_scalebreakpoint } = response;
                            $("#zoom_image_wallpaper_scalefactor").val(zoom_image_wallpaper_scalefactor);
                            $("#zoom_image_wallpaper_scalebreakpoint").val(zoom_image_wallpaper_scalebreakpoint);
                            OCP.Toast.success(t(OCA.Cadviewer.AppName, "Settings have been successfully updated") + versionMessage);
                        }
                    }
                }
            });
        });

        $("#saveScrollWheelParameters").click(function () {

            const scroll_wheel_throttle_delay = parseInt($("#scroll_wheel_throttle_delay").val());
            const scroll_wheel_zoom_steps = parseInt($("#scroll_wheel_zoom_steps").val());
            const scroll_wheel_default_zoom_factor = parseFloat($("#scroll_wheel_default_zoom_factor").val().replace(",", "."));

            if (isNaN(scroll_wheel_throttle_delay) || isNaN(scroll_wheel_zoom_steps) || isNaN(scroll_wheel_default_zoom_factor)) {
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "Invalid value"));
                return;
            }

            let data = {
                scroll_wheel_default_zoom_factor, scroll_wheel_zoom_steps, scroll_wheel_throttle_delay
            }

            $(".section-cadviewer").addClass("icon-loading");

            $.ajax({
                method: "PUT",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/scroll-wheel-parameters"),
                data: data,
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response) {

                        var versionMessage = response?.version ? (" (" + t(OCA.Cadviewer.AppName, "version") + " " + response.version + ")") : "";

                        if (response?.error) {
                            OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to connect") + " (" + response.error + ")" + versionMessage);
                        } else {
                            const { scroll_wheel_default_zoom_factor, scroll_wheel_zoom_steps, scroll_wheel_throttle_delay } = response;
                            $("#scroll_wheel_throttle_delay").val(scroll_wheel_throttle_delay);
                            $("#scroll_wheel_zoom_steps").val(scroll_wheel_zoom_steps);
                            $("#scroll_wheel_default_zoom_factor").val(scroll_wheel_default_zoom_factor);
                            OCP.Toast.success(t(OCA.Cadviewer.AppName, "Settings have been successfully updated") + versionMessage);
                        }
                    }
                }
            });
        });
        
        $("#saveUsers").click(function () {
            const current = $("#users_licence_list .grid_input_3").length;
            let data = [];
            
            for(let i=1; i<current+1; i++){
                var user = $("#user_licence_"+i).val().trim()  || "*";
                data.push(user);
            }

            $(".section-cadviewer").addClass("icon-loading");

            $.ajax({
                method: "PUT",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/users"),
                data: {"users": data},
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response) {
                        
                        var versionMessage = response.version ? (" (" + t(OCA.Cadviewer.AppName, "version") + " " + response.version + ")") : "";

                        if (response.error) {
                            OCP.Toast.error(t(OCA.Cadviewer.AppName, "Error when trying to connect") + " (" + response.error + ")" + versionMessage);
                        } else {
                            OCP.Toast.success(t(OCA.Cadviewer.AppName, "Users have been successfully updated") + versionMessage);
                        }
                    }
                }
            });
        });

        $("#getDemoLicence").click(function () {
            const email = $("#demo_email").val();
            const company_name = $("#demo_company").val();
            if (!email || !company_name) {
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "Please fill email and company name"));
                return;
            }
            // validate email
            if (!/^\S+@\S+\.\S+$/.test(email)) {
                OCP.Toast.error(t(OCA.Cadviewer.AppName, "Invalid email"));
                return;
            }
            $(".section-cadviewer").addClass("icon-loading");

            // Request demo licence
            $.ajax({
                method: "POST",
                url: OC.generateUrl("apps/" + OCA.Cadviewer.AppName + "/ajax/settings/demo-licence"),
                data: { email, company_name, url: window.location.host },
                success: function onSuccess(response) {
                    $(".section-cadviewer").removeClass("icon-loading");
                    if (response.status == "failed") {
                        OCP.Toast.error(t(OCA.Cadviewer.AppName, "Demo licence already exists!"));
                    } else {
                        OCP.Toast.success(t(OCA.Cadviewer.AppName, "Demo licence keys generated successfully"));
                        checkAutoExchangeLicenceKey();
                    }
                }
            });
        });

        function watchCheckboxChange () {
            $(".exclude-block-checkbox input").change(function() {
                if(this.checked) {
                    $(this).parent().parent().addClass("checked");
                }else {
                    $(this).parent().parent().removeClass("checked");
                    $(this).parent().parent().find(".exclude-block-input input").val("");
                }        
            });
        }

        watchCheckboxChange();
        
    });


})(jQuery, OC);

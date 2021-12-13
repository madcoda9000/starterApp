// jQuery codes
$(document).ready(function(){
    // global variables
    let quastfin = false;    
    
     /* For theme switching */
    // array of themes
     var themes = {
        "undefined": "../assets/bootstrap-5.1.3-dist/css/bootstrap.min.css",
        "default": "assets/bootstrap-5.1.3-dist/css/bootstrap.min.css",
        "flatly" : "assets/css/bs5-themes/flatly.min.css",
        "minco" : "assets/css/bs5-themes/minco.min.css",
        "lymcha" : "assets/css/bs5-themes/lymcha.min.css",
        "superhero" : "assets/css/bs5-themes/superhero.min.css",
        "hollar" : "assets/css/bs5-themes/hollar.min.css",
        "preptor" : "assets/css/bs5-themes/preptor.min.css",
        "sunset" : "assets/css/bs5-themes/sunset.min.css",
        "skeeblue" : "assets/css/bs5-themes/skeeblue.min.css",
        "wandoo" : "assets/css/bs5-themes/wandoo.min.css"
    }

     // set theme if cookie is found
    var themeName = getCookie("themeName");
    if(!themeName) {
        themeName = "default";
    }
    if (themeName !== undefined) {
        setTheme(themeName, themes[themeName]);
    } else {
        setTheme("default", themes["default"]);
    }

    // function to set theme and save as cookie
    function setTheme(themeName, themePath) {
        var cssLink = "<link rel='stylesheet' id='btTheme' href='" + themePath + "' />";
        $('#btTheme').replaceWith(cssLink);
        setCookie("themeName", themeName, 7);
    }

    // function to change theme on menu click            
    $('.theme-link').click(function(){
        setTheme($(this).attr('data-theme'), themes[$(this).attr('data-theme')]);                   
    });

    // show login from if no one logged in
    showHomePage();

    // show sign up / registration form
    $(document).on('click', '#sign_up', function(){

        var html = `
            <h2>Sign Up</h2>
            <form id='sign_up_form'>
                <div class="mb-3">
                    <label for="firstname">Firstname</label>
                    <input type="text" class="form-control" name="firstname" id="firstname" required />
                </div>

                <div class="mb-3">
                    <label for="lastname">Lastname</label>
                    <input type="text" class="form-control" name="lastname" id="lastname" required />
                </div>

                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required />
                </div>

                <div class="mb-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required />
                </div>

                <button type='submit' class='btn btn-primary'>Sign Up</button>
            </form>
            `;

        clearResponse();
        $('#content').html(html);
    });

    // show mfa form
    $(document).on('click', '#mfa', function(){
        // validate jwt to verify access
        var jwt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {      
            var erg = result;   
            if(erg.data.totp_enabled == 1) {
                var qr = "";
                $.ajax({
                    url: "api/totp_createqr_from_secret.php",
                    type : "POST",
                    data : {jwt: jwt, totpcsecret: erg.data.totp_secret},
                    success : function(result) {
                        //alert(result);
                        qr = result;

                        var html = `
                        <br>
                        <form id='disable_mfa_form' method='post'>
                            <div class="alert alert-info" role="alert">
                                Currently MFA is enabled for your account. Please review your app secret below.
                            </div>
                            <br>
                            <div class="alert alert-secondary" role="alert">
                                ` + qr + `
                            </div>
                            <br>
                            <div class="alert alert-danger" role="alert">
                                <br><b>Disable MFA</b><br><br>
                                Here you can disable multi factor authentication for your account.
                                <br><br>
                                <button type='button' class='btn btn-danger' id='btndisabletotp'>disable MFA...</button>
                            </div>
                        </form>
                        
                        <div class="modal" tabindex="-1" role="dialog" id="modalconfirmDelMFA">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">WARNING</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>NOTE: MFA successfully deactivated!<br><br><b>You will now be logged out.</b></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Okay!</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        `;                
                        clearResponse();
                        $('#headline').html('<h5>MFA configuration</h5>');
                        $('#content').html(html);
                    },
                    error: function(xhr, resp, text){
                        // on error
                        $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + text + "</div>");
                    }
                });                 
            } else if(erg.data.totp_enabled == 0) {
                var html = `
                <br>
                <form id='enable_mfa_form' method='post'>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="enableMFA" id="enableMFA" />
                        <label class="form-check-label" for="enableMFA">enable MFA (multi factor authentication)</label>
                    </div>
                    <br>
                    <div class="form-group">
                        <div id="totpdata">

                        </div>
                    </div>
                    <br>
                    <button type='button' class='btn btn-primary disabled' id='nexttotstep'>next step</button>
                </form>
                `;        
                clearResponse();
                $('#headline').html('<h5>configure MFA (Step 1 of 2)</h5>');
                $('#content').html(html);
            }        
        })                
        // on error/fail, tell the user he needs to login to show the account page
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
        });
    });

    // disable mfa for user
    $(document).on('click', '#btndisabletotp', function(){
        // validate jwt to verify access
        var jwt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {  
            // ask the user before deaktivating
            $.confirm({
                title: 'WARNING!',
                content: 'Do you really want to disable two facto authentication?<br><br><b>For security reasons it is strongly recommended to activate mfa!</b>',
                type: 'red',
                theme: 'modern', // 'material', 'bootstrap', 'Supervan', Material,
                buttons: {
                    Deactivate: {
                        text: 'Deactivate MFA',
                        btnClass: 'btn btn-danger',
                        action: function () {
                            // submit request to api
                            $.ajax({
                                url: "api/totp_disable_mfa.php",
                                type : "POST",
                                data : {jwt: jwt},
                                success : function(result) {
                                    if(result =='true') {
                                        showLoginPage(true);
                                    }
                                },
                                error: function(xhr, resp, text){
                                    // on error
                                    $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + text + "</div>");
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Cancel',
                        btnClass: 'btn btn-primary',
                        action: function() {
                        }
                    }
                }
            });              
        })      
        // on error/fail, tell the user he needs to login to show the account page
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
        });    
    });

    // retrieve totp secret on activating checkbox
    $(document).on('click', '#enableMFA', function(){

        // validate jwt to verify access
        var jwtt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt:jwtt })).done(function(result) {

            var sec = "";

            // submit request to api
            $.ajax({
                url: "api/totp_create_secret.php",
                type : "POST",
                data : {jwt: jwtt},
                success : function(result) {
                    // on success, show totp data and enable next step button
                    $("#totpdata").html(result);
                    $("#enableMFA").prop("checked", true);
                    $('#nexttotstep').removeClass('disabled');

                    $.ajax({
                        url: "api/crypt_encrypt_string.php",
                        type : "POST",
                        data : {jwt: jwtt, value: $("#totpsecret").text()},
                        success : function(result) {
                            var t = result;
                            setCookie("mfasec", result, 1);
                        },
                        error: function(xhr, resp, text){
                            // on error
                            $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + text + "</div>");
                        }
                    });
                    
                },
                error: function(xhr, resp, text){
                    // on error
                    $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + text + "</div>");
                }
            });                     
            
            return false;
        })                
        // on error/fail, tell the user he needs to login to show the account page
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
        });
    });

    // show last step of totp configuration
    $(document).on('click', '#nexttotstep', function(){
        // validate jwt to verify access
        var jwtt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt:jwtt })).done(function(result) {

            // submit request to api to get a totp code for the totp secret
            
            var html = `
                <br>
                <form id='enable_mfa_form2' method='post' onkeydown="return event.key != 'Enter';">
                    <div class="mb-3">
                        <label id="lblfinishtotp" for="token">Please enter your token from your totp app</label>
                        <input type="text" class="form-control w-25" name="token" id="token" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" maxlength="6" required/>
                    </div>
                    <br>
                    <button type='button' class='btn btn-primary' id='finishtotp'>validate code from app...</button>
                </form>
                `;
            clearResponse();
            $('#headline').html('<h5>configure MFA (Step 2 of 2)</h5>');
            $('#content').html(html);
        })
        // on error/fail, tell the user he needs to login to show the account page
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
        });
    });

    // finish totp configuration
    $(document).on('click', '#finishtotp', function(){

        // validate jwt to verify access
        var jwtt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt:jwtt })).done(function(result) {

            var secret = getCookie('mfasec');

            $.ajax({
                url: "api/totp_validate_code.php",
                type : "POST",
                data : {jwt: jwtt, totpsecret: secret, totpcode: $("#token").val(), method: 'activateMFA'},
                success : function(result) {
                    if(result == 'true') {
                        $("#token").hide();
                        $("#finishtotp").hide();
                        $("#finishtotph2").hide();
                        $("#lblfinishtotp").hide();
                        var msg = `<h2>SUCCESS - MFA activation</h2>
                        The entered code was correct! Please finish the MFA configuration by clicking on "okay, I understand!".<br><br>
                        <b>NOTE</b>: from now on you must also enter the code from your authenticator app when logging in after entering your user name and password.<br><br>
                        If you no longer want this, you can deactivate it under Account -> mFA."<br><br><br>
                        <button type='button' class='btn btn-success' id='finishtotp_ok'>okay, i understand!</button>&nbsp;&nbsp;&nbsp;
                        <button type='button' class='btn btn-btn btn-danger' id='finishtotp_no'>Nooo, I don't want that!</button><br>
                        `;
                        $('#response').html("<div class='alert alert-success'>" + msg + "</div>");
                        
                    }
                    else {
                        var msg ='Either the code you entered is incorrect or has expired. <br>Please enter a valid code.';
                        $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + msg + "</div>");
                    }
                },
                error: function(xhr, resp, text){
                    // on error
                    $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + text + "</div>");
                }
            })

        })
        // on error
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
        });        
    });

    // save totp configuration
    $(document).on('click', '#finishtotp_ok', function() {
        // validate jwt to verify access
        var jwtt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt:jwtt })).done(function(result) {
            
            var secret = getCookie('mfasec');

            // submit form data to api
            $.ajax({
                url: "api/totp_enable_for_user.php",
                type : "POST",
                data : {totpsecret: secret, jwt: jwtt, method: 'activateMFA'},
                success : function(result) {
                    clearResponse();
                    showLoginPage(isDelMfaLogout=false,isActivatedMfaLogout=true);
                },            
                // show error message to user
                error: function(xhr, resp, text){
                    // on error
                    $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + text + "</div>");
                }
            });


        })
        // on error
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
        });
    });

    // cancel totp configuration
    $(document).on('click', '#finishtotp_no', function() {

        clearResponse();
        showHomePage();
    });

    // trigger when registration form is submitted
    $(document).on('submit', '#sign_up_form', function(){
    
        // get form data
        var sign_up_form=$(this);
        var form_data=JSON.stringify(sign_up_form.serializeObject());

        // submit form data to api
        $.ajax({
            url: "api/create_user.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
                // if response is a success, tell the user it was a successful sign up & empty the input boxes
                $('#response').html("<div class='alert alert-success'>Successful sign up. Please login.</div>");
                sign_up_form.find('input').val('');
            },
            error: function(xhr, resp, text){
                // on error, tell the user sign up failed
                $('#response').html("<div class='alert alert-danger'>Unable to sign up. Please contact admin.</div>");
            }
        });

        return false;
    });

    // show login form
    $(document).on('click', '#login', function(){
        showLoginPage();
    });
    
    // trigger when login form is submitted
    $(document).on('submit', '#login_form', function(){
    
        // get form data
        var login_form=$(this);
        var form_data=JSON.stringify(login_form.serializeObject());

        // submit form data to api
        $.ajax({
            url: "api/login.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result){  
                

                // check if mfa is enabled
                if(result.mfa == 1) {
                    // store jwt to cookie
                    setCookie("jwt", result.jwt, 1);
                    // show mfa verification                    
                    showMFALogonDialog();
                } else {
                    // store jwt to cookie
                    setCookie("jwt", result.jwt, 1);
                    // show home page & tell the user it was a successful login
                    showHomePage();
                    $('#response').html("<div class='alert alert-success'>Successful login.</div>");                    
                }       
            },
            error: function(xhr, resp, text){
                // on error, tell the user login has failed & empty the input boxes
                $('#response').html("<div class='alert alert-danger'>Login failed. Email or password is incorrect.</div>");
                login_form.find('input').val(''); 
            }
        });

        return false;
    });

    // trigger when loginmfa from ic clicked
    $(document).on('click', '#btnloginmfa', function(){
        if($('#token').val()){
            // validate jwt to verify access
            var jwtt = getCookie('jwt');
            $.post("api/validate_token.php", JSON.stringify({ jwt:jwtt })).done(function(result) {
                $.ajax({
                    url: "api/totp_validate_code.php",
                    type : "POST",
                    data : {jwt: jwtt, method: 'loginmfa', totpcode: $('#token').val()},
                    success : function(result) {
                        if(result == 'true') {
                            quastfin = true;
                            $('#response').html('');                            
                            showHomePage();
                        }
                        else {
                            var msg ='Either the code you entered is incorrect or has expired. <br>Please enter a valid code.';
                            $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + msg + "</div>");
                        }
                    },
                    error: function(xhr, resp, text){
                        // on error
                        $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + text + "</div>");
                    }
                })
            })
            // on error
            .fail(function(result){
                showLoginPage();
                $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
            });
        } else {
            $('#modalconfirm').modal();
        }
    });

    // show home page
    $(document).on('click', '#home', function(){
        showHomePage();
        clearResponse();
    });
    
    // show update account form
    $(document).on('click', '#update_account', function(){
        showUpdateAccountForm();
    });
    
    // trigger when 'update account' form is submitted
    $(document).on('submit', '#update_account_form', function(){
    
        // handle for update_account_form
        var update_account_form=$(this);

        // validate jwt to verify access
        var jwt = getCookie('jwt');

        // get form data
        var update_account_form_obj = update_account_form.serializeObject()
        
        // add jwt on the object
        update_account_form_obj.jwt = jwt;
        
        // convert object to json string
        var form_data=JSON.stringify(update_account_form_obj);
        
        // submit form data to api
        $.ajax({
            url: "api/update_user.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
        
                // tell the user account was updated
                $('#response').html("<div class='alert alert-success'>Account was updated.</div>");
        
                // store new jwt to coookie
                setCookie("jwt", result.jwt, 1);
            },
        
            // show error message to user
            error: function(xhr, resp, text){
                if(xhr.responseJSON.message=="Unable to update user."){
                    $('#response').html("<div class='alert alert-danger'>Unable to update account.</div>");
                }
            
                else if(xhr.responseJSON.message=="Access denied."){
                    showLoginPage();
                    $('#response').html("<div class='alert alert-success'>Access denied. Please login</div>");
                }
            }
        });

        return false;
    });

    // logout the user
    $(document).on('click', '#logout', function(){
        showLoginPage();
        $('#response').html("<div class='alert alert-info'>You are logged out.</div>");
    });

    // reset mfa
    $(document).on('click', '#btnResetMfa', function(){
        // validate jwt to verify access
        var jwtt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt:jwtt })).done(function(result) {

            var sub = 'Reset MFA for User: ' + result.data.email;
            var bod = 'Hello Admin,<br>the mentioned user has lost the verification device.<br>Please reset MFA fro this user.';
            $.ajax({
                url: "api/mail_functions.php",
                type : "POST",
                data : {jwt: jwtt, method: 'sendAdminMail', subject: sub, body: bod},
                success : function(result) {
                    $('#response').html("<div class='alert alert-success'><b>The admin was notified successfully!</b><br></div>");

                },
                error: function(xhr, resp, text){
                    // on error
                    $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + text + "</div>");
                }
            })
            // on error
            .fail(function(result){
                showLoginPage();
                $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
            }); 
        });
    });

    // remove any prompt messages
    function clearResponse(){
        $('#response').html('');
    }

    // chow mfa dialog
    function showMFALogonDialog() {
        // validate jwt to verify access
        var jwtt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt:jwtt })).done(function(result) {
            var msg = `<div class='alert alert-info'><b>Note</b>: if you do not have access to your device for verification anymore, you can ask the administrator to reset your mFA activation here.
            <br><br>
            <button type="button" id="btnResetMfa" class='btn btn-primary'>Request mafa reset...</button>
            </div>
            `;
            $('#response').html(msg);
            var html = `
                <br>
                <!--  onkeydown="return event.key != 'Enter'; -->
                <form id='loginmfa_form' method='post' onkeydown="return event.key != 'Enter';">
                    <div class="mb-3">
                        <label id="lblloginmfa" for="token">Please enter your token from your totp app</label>
                        <input type="text" class="form-control w-25" name="loginmfatoken" id="token" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" maxlength="6" required autofocus />
                    </div>
                    <br>
                    <button type='button' class='btn btn-primary' id='btnloginmfa'>validate code from app...</button>
                </form>
                `;
            var modal = `
                <!-- Modal -->
                <div class="modal" tabindex="-1" role="dialog" id="modalconfirm">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">WARNING</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>NOTE: you've to enter a valid code from your authenticator app!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Okay!</button>
                        </div>
                        </div>
                    </div>
                </div>
                `;
            var content = html + modal;
            $('#headline').html('<h5>MFA-Login required</h5>');
            $('#content').html(content);    
            $('#token').focus(); 
            
            $("#token").keyup(function(event) {
                //alert(event.keyCode);
                if (event.keyCode === 13) {
                    alert('goooo');
                    $("#btnloginmfa").click();
                }
            });
        })
        // on error
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
        });
    }
    
    // show login page
    function showLoginPage(isDelMfaLogout = false, isActivatedMfaLogout = false){
    
        // remove jwt
        setCookie("jwt", "", 1);
        quastfin=false;

        // login page html
        var headline = "<h5>Login</h5>";
        var html = `
            <form id='login_form' >
                <div class='mb-3'>
                    <label for='email'>Email address</label>
                    <input type='email' class='form-control' id='email' name='email' placeholder='Enter email' required>
                </div>

                <div class='mb-3'>
                    <label for='password'>Password</label>
                    <input type='password' class='form-control' id='password' name='password' placeholder='Password' required>
                </div>

                <button type='submit' class='btn btn-primary'>Login</button>
            </form>
            `;

        $('#content').html(html);
        $('#headline').html(headline);
        clearResponse();

         if(isDelMfaLogout==true) {
             $('#response').html("<div class='alert alert-success'>The multi factor authentication was successfully <b>deactivated</b>.</div>");
         }

         if(isActivatedMfaLogout==true) {
            $('#response').html("<div class='alert alert-success'>The multi factor authentication was successfully <b>activated</b>. Please login again.</div>");
        }

        showLoggedOutMenu();
    }

    // function to check if user is admin
    function checkForAdmin() {        
        // validate jwt to verify access
        var jwtt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt:jwtt })).done(function(result) {

            // submit form data to api
            $.ajax({
                url: "api/adm_checkForAdmin.php",
                type : "POST",
                data : {jwt: jwtt},
                success : function(result) {
                    //alert(result);
                    if(result=="true") {
                        $('#admin_menu').css('display', 'block');
                    } else if(result=="false") {
                        $('#admin_menu').css('display', 'none');
                    } else {
                        $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + result + "</div>");
                    }
                },            
                // show error message to user
                error: function(xhr, resp, text){
                    // on error
                    $('#admin_menu').css('display', 'none');
                    $('#response').html("<div class='alert alert-danger'>There was error: <br><br>" + text + "</div>");
                }
            });


        })
        // on error
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
        });
    }

    //function to delete a cookie
    function deleteCookie(cname) {
        document.cookie = cname + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

    // function to set cookie
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    } 

    // if the user is logged out
    function showLoggedOutMenu(){
        // show home and sign up from navbar & hide login, logout & account button
        $("#login").css('display', 'none');
        $("#sign_up").css('display', 'block');
        $("#logout").css('display', 'none');
        $("#account_menu").css('display', 'none');
    }
    
    // show home page
    function showHomePage(){
    
        // validate jwt to verify access
        var jwt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {

            // if valid, show homepage
            var headline = "<h5>You are logged in.</h5>";
            var html = `<p>You won't be able to access the home and account pages if you are not logged in.</p>`;

            if(result.data.totp_enabled == 1 && quastfin == false) {
                showLoginPage();
            } else if(result.data.totp_enabled == 1 && quastfin == true) {
                $('#content').html(html);
                $('#headline').html(headline);
                showLoggedInMenu();
            } else if(result.data.totp_enabled == 0 && quastfin == false) {               
                $('#content').html(html);
                $('#headline').html(headline);
                showLoggedInMenu();
            }            
        })

        // show login page on error
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-info'>Please login to access this app.</div>");
        });
    }

    // get or read cookie
    function getCookie(cname){
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' '){
                c = c.substring(1);
            }
    
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    // if the user is logged in
    function showLoggedInMenu(){
        // hide login and sign up from navbar & show account & logout button
        $("#login").css('display', 'none');
        $("#sign_up").css('display', 'none');
        $("#account_menu").css('display', 'block');
        $("#logout").css('display', 'block');
        checkForAdmin();
    }
    
    function showUpdateAccountForm(){
        // validate jwt to verify access
        var jwt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {
    
            // if response is valid, put user details in the form
            var headline = "<h5>update account data</h5>";
            var html = `
                    <form id='update_account_form'>
                        <div class="mb-3">
                            <label for="firstname">Firstname</label>
                            <input type="text" class="form-control" name="firstname" id="firstname" required value="` + result.data.firstname + `" />
                        </div>
            
                        <div class="mb-3">
                            <label for="lastname">Lastname</label>
                            <input type="text" class="form-control" name="lastname" id="lastname" required value="` + result.data.lastname + `" />
                        </div>
            
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" required value="` + result.data.email + `" />
                        </div>
            
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password" />
                        </div>
            
                        <button type='submit' class='btn btn-primary'>
                            Save Changes
                        </button>
                    </form>
                `;
            
            clearResponse();
            $('#headline').html(headline);
            $('#content').html(html);
        })
    
        // on error/fail, tell the user he needs to login to show the account page
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
        });
    }
    
    // function to make form values to json format
    $.fn.serializeObject = function(){            
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
});
// jQuery codes
$(document).ready(function(){
     /* For theme switching */
    // array of themes
    var themes = {
        "undefined": "../assets/bootstrap-5.1.3-dist/css/bootstrap.min.css",
        "default": "../assets/bootstrap-5.1.3-dist/css/bootstrap.min.css",
        "flatly" : "../assets/css/bs5-themes/flatly.min.css",
        "minco" : "../assets/css/bs5-themes/minco.min.css",
        "lymcha" : "../assets/css/bs5-themes/lymcha.min.css",
        "superhero" : "../assets/css/bs5-themes/superhero.min.css",
        "hollar" : "../assets/css/bs5-themes/hollar.min.css",
        "preptor" : "../assets/css/bs5-themes/preptor.min.css",
        "sunset" : "../assets/css/bs5-themes/sunset.min.css",
        "skeeblue" : "../assets/css/bs5-themes/skeeblue.min.css",
        "wandoo" : "../assets/css/bs5-themes/wandoo.min.css"
    }

     // set theme if cookie is found
    var themeName = getCookie("themeName");
    var themePath = themes[themeName];   
    if (themeName !== undefined) {
        setTheme(themeName, themePath);
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

    // if the user is logged out
    function showLoggedOutMenu(){
        // show home and sign up from navbar & hide login, logout & account button
        $("#login").css('display', 'none');
        $("#sign_up").css('display', 'block');
        $("#logout").css('display', 'none');
        $("#account_menu").css('display', 'none');
    }

    // if the user is logged in
    function showLoggedInMenu(){
        // hide login and sign up from navbar & show account & logout button
        $("#login").css('display', 'none');
        $("#sign_up").css('display', 'none');
        $("#account_menu").css('display', 'block');
        $("#logout").css('display', 'block');
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

    // function to set cookie
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    // show home page
    function showPage(){
    
        // validate jwt to verify access
        var jwt = getCookie('jwt');
        $.post("../api/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {
            showLoggedInMenu();           
        })
        // show login page on error
        .fail(function(result){
            setCookie("jwt", "", 1);
            window.location.replace("../index.php");
        });
    }

    // logout the user
    $(document).on('click', '#logout', function(){
        setCookie("jwt", "", 1);
        window.location.href = "../index.php";
    });

    // show home page
    $(document).on('click', '#home', function(){
        window.location.href = "../index.php";
    });

    // function to fetch paginated groups data
    function getPaginatedGroups() {
        var jwt = getCookie('jwt');
        $("#target-content").load("../api/adm_groups_pagination.php?page=1&jwt=" + jwt);
		$(".page-link").click(function(){
			var id = $(this).attr("data-id");
			var select_id = $(this).parent().attr("id");
			$.ajax({
				url: "../api/adm_groups_pagination.php",
				type: "GET",
				data: {
					page : id,
                    jwt : jwt
				},
				cache: false,
				success: function(dataResult){
					$("#target-content").html(dataResult);
					$(".pageitem").removeClass("active");
					$("#"+select_id).addClass("active");
					
				}
			});
		});
    }    

    // catch delete click
    $(document).on('click', '.delBtnList', function(){
        var jwt = getCookie('jwt');
        var gID = $(this).attr("data-btnDelid");
        $.confirm({
            title: 'Confirm group deletion!',
            content: 'Do you really want to delete this group?',
            buttons: {
                delete: {
                    text: 'Delete!',
                    btnClass: 'btn-red',
                    action: function(){
                        $.ajax({
                            url: "../api/adm_deleteEntryById.php",
                            type: "GET",
                            data: {
                                id : gID,
                                jwt : jwt,
                                table : 'app_groups'
                            },
                            cache: false,
                            success: function(dataResult){
                                if(dataResult=='success') {
                                    $.alert({
                                        title: 'Success!',
                                        content: 'the group was deleted successfully.',
                                    });
                                    showPage();
                                    getPaginatedGroups();
                                } else {
                                    $.alert({
                                        title: 'Error!',
                                        content: dataResult,
                                    });
                                }
                            }
                        });
                    }
                },
                cancel: {
                    text: 'cancel',
                    btnClass: 'btn-green',
                    action: function(){
                        
                    }
                }
            }
        });
    });

    // catch new group click
    $(document).on('click', '#btnNewGroup', function(){
        var jwt = getCookie('jwt');
        $.confirm({
            title: false,
            content: 'url:../templates/adm_addGroup.html',
            theme: 'bootstrap',
            type: 'green',
            buttons: {
                ok: {
                    text: 'ok',
                    btnClass: 'btn-green',
                    action: function(){
                        var self = this;
                        var gName = self.$content.find('input').val(); 
                        if(!gName) {
                            $.alert({
                                title: 'ERROR!',
                                content: 'you must enter a group name!',
                            });
                        } else {
                            $.ajax({
                                url: "../api/adm_groups_addGroup.php",
                                type: "GET",
                                data: {
                                    jwt : jwt,
                                    gName : gName
                                },
                                cache: false,
                                success: function(dataResult){
                                    if(dataResult=='success') {
                                        $.alert({
                                            title: 'Success!',
                                            content: 'the group was created successfully.',
                                        });
                                        showPage();
                                        getPaginatedGroups();
                                    } else {
                                        $.alert({
                                            title: 'Error!',
                                            content: dataResult,
                                        });
                                    }
                                }
                            }); 
                        }                   
                    }
                },
                cancel: {
                    text: 'cancel',
                    btnClass: 'btn-blue',
                    action: function(){
                        
                    }
                }
            }
        });
    });

    showPage();
    getPaginatedGroups();

    // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    // var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    // return new bootstrap.Tooltip(tooltipTriggerEl)
    // })

});
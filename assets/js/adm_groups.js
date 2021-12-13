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

            // if valid, show homepage
            var headline = "<h5>You are logged in.</h5>";
            var html = `<p>You won't be able to access the home and account pages if you are not logged in.</p>`;

            $('#content').html(html);
            $('#headline').html(headline);
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

    showPage();
});
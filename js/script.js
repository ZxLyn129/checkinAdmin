function rememberMee() {
    var rememberme = document.forms["loginForm"]["idremember"].checked;
    var email = document.forms["loginForm"]["idemail"].value;
    var pass = document.forms["loginForm"]["idpass"].value;
    console.log("Form data:" + rememberme + "," + email + "," + pass);
    if (!rememberme) {
        setCookiess("cemail", "", 0);
        setCookiess("cpass", "", 0);
        setCookiess("crem", false, 0);
        document.forms["loginForm"]["idemail"].value = "";
        document.forms["loginForm"]["idpass"].value = "";
        document.forms["loginForm"]["idremember"].checked = false;
        alert("Credentials removed");
    } else {
        if (email == "" && pass == "") {
            document.forms["loginForm"]["idremember"].checked = false;
            alert("Please enter your credentials");
            return false;
        } else {
            setCookiess("cemail", email, 30);
            setCookiess("cpass", pass, 30);
            setCookiess("crem", rememberme, 30);
            alert("Credentials Stored Success");
        }
    }
}

function setCookiess(cookiename, cookiedata, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cookiename + "=" + cookiedata + ";" + expires + ";path=/";
}

function loadCookiess() {
    var username = getCookies("cemail");
    var password = getCookies("cpass");
    var rememberme = getCookies("crem");
    console.log("COOKIES:" + username, password, rememberme);
    document.forms["loginForm"]["idemail"].value = username;
    document.forms["loginForm"]["idpass"].value = password;
    if (rememberme) {
        document.forms["loginForm"]["idremember"].checked = true;
    } else {
        document.forms["loginForm"]["idremember"].checked = false;
    }
}

function getCookies(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function deleteCookies(cname) {
    const d = new Date();
    d.setTime(d.getTime() + (24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=;" + expires + ";path=/";
}

function acceptCookieConsent() {
    deleteCookies('user_cookie_consent');
    setCookiess('user_cookie_consent', 1, 30);
    document.getElementById("cookieNotice").style.display = "none";
}

function autoLogin() {
    var username = getCookies("cemail");
    var password = getCookies("cpass");

    if (username !== "" && password !== "") {
        document.forms["loginForm"]["idemail"].value = username;
        document.forms["loginForm"]["idpass"].value = password;
        document.forms["loginForm"]["idremember"].checked = true;
    }
}
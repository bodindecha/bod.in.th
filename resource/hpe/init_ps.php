<?php
    session_start();
    $my_url = ($_SERVER['REQUEST_URI']=="/")?"":"?return_url=".urlencode(rtrim(ltrim($_SERVER['REQUEST_URI'], "/"), "/")); // str_replace("#", "%23", "");
	
    // Redirection for authorized persons
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); $ua = $_SERVER['HTTP_USER_AGENT'];
	if (!(strpos("facebookexternalhit/1.1;line-poker/1.0", $ua)>-1 || strpos("facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)", $ua)>-1)) {
        $require_sso = false; if (!isset($_SESSION['auth']) && isset($_COOKIE['bdSSOv1a']) && $_COOKIE['bdSSOv1a']<>"") $require_sso = true;
        else if (!isset($_SESSION['auth']) && preg_match("/^\/(dashboard|(?!(error|dashboard|admin))(@|!)?[A-Za-z0-9_\-]{3,150}(~|\+))$/", $url)) header("Location: /$my_url");
        else if (isset($_SESSION['auth']) && preg_match("/^\/$/", $url)) header("Location: /dashboard");
    }
?>
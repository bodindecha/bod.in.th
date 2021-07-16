<?php
    session_start();
    $my_url = ($_SERVER['REQUEST_URI']=="/")?"":"?return_url=".urlencode(rtrim(ltrim($_SERVER['REQUEST_URI'], "/"), "/")); // str_replace("#", "%23", "");
	
    // Redirection for authorized persons
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); $ua = $_SERVER['HTTP_USER_AGENT'];
	if (!(strpos("facebookexternalhit/1.1;line-poker/1.0", $ua)>-1 || strpos("facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)", $ua)>-1)) {
        if (!isset($_SESSION['auth']) && preg_match("/^\/(dashboard)$/", $url)) header("Location: /$my_url");
        else if (isset($_SESSION['auth']) && !preg_match("/^\/(dashboard|go)$/", $url)) header("Location: /dashboard");
    }
?>
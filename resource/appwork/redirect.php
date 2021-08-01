<?php
    if (isset($_GET['key'])) {
        // Tune
        if (preg_match("/^[A-Za-z0-9_\-]{3,150}$/", $_GET['key'])) { $key = $_GET['key']; $type = "T"; }
        else if (preg_match("/^@[A-Za-z0-9_\-]{3,150}$/", $_GET['key'])) { $key = ltrim($_GET['key'], "@"); $type = "M"; }
        else if (preg_match("/^![A-Za-z0-9_\-]{3,150}$/", $_GET['key'])) { $key = ltrim($_GET['key'], "!"); $type = "S"; }
        // Find
        if (isset($type)) {
            require("db_connect.php");
            $get_url = $db -> query("SELECT urlid,rdrto,owner FROM urls WHERE type='$type' AND keyword='".base64_encode($key)."' AND active='Y'");
            if ($get_url -> num_rows == 1) {
                $read_url = $get_url -> fetch_array(MYSQLI_ASSOC);
                // Record log
                $ua = $_SERVER['HTTP_USER_AGENT'];
                # if (!(strpos("facebookexternalhit/1.1;line-poker/1.0", $ua)>-1 || strpos("facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)", $ua)>-1 || strpos("WordPress/", $ua)>-1)) {
                    require_once("config.php");
                    $utmS = (isset($_GET['utm_source'])) ? utmStext2code($_GET['utm_source']) : utmStext2code("direct");
                    if (isset($_GET['utm_campaign'])) $utmC = utmCtext2code($_GET['utm_campaign']);
                    else {
                        $hrfr = $_SERVER['HTTP_REFERER'] ?? "";
                        if ($hrfr == "http://m.facebook.com/") $utmC = utmCtext2code("Facebook");
                        else if (strpos($ua, "Line") > -1) $utmC = utmCtext2code("Line");
                        else if ($hrfr == "") $utmC = utmCtext2code("Link");
                        else $utmC = "5";
                    }
                    require_once("getip.php"); if (!isset($ip)) $ip = "";
                    $g_cc = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip")); $ccode = strval($g_cc -> geoplugin_countryCode);
                    $success = $db -> query("INSERT INTO log_click (urlid,utm_source,utm_campaign,ccode,useragent,ipaddr) VALUES(".$read_url['urlid'].",$utmS,$utmC,'$ccode','$ua','$ip')");
                    if ($success) {
                        $db -> query("UPDATE urls SET click=click+1 WHERE urlid='".$read_url['urlid']."'");
                        $db -> query("UPDATE users SET url_clicks=url_clicks+1 WHERE idcode='".$read_url['owner']."'");
                    }
                # }
                // Redirect
                header("Location: ".$read_url['rdrto']);
            } else $error = 900;
            $db -> close();
        } else $error = 902;
    } else $error = 902;
    if (isset($error)) {
        include("../hpe/init_ps.php");
        $header_title = "Error (".strval($error).")";
        echo '<html xmlns="http://www.w3.org/1999/xhtml">
                <head>';
                    include("../hpe/heading.php"); include("../hpe/init_ss.php");
        echo '          <style type="text/css">
                        html body main iframe {
                            width: 100%; height: calc(var(--window-height) - var(--top-height));
                            border: none;
                        }
                    </style>
                    <script type="text/javascript">
                        
                    </script>
                </head>
                <body>';
                    include("../hpe/header.php");
        echo '      <main shrink="'.(($_COOKIE['sui_open-nt'])??"false").'">
                        <iframe src="/error/'.strval($error).'"></iframe>
                    </main>';
                    include("../hpe/material.php");
        echo '      <footer>';
                        include("../hpe/footer.php");
        echo '      </footer>
                </body>
            </html>';
    }
?>
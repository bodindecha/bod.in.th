<?php
	session_start();
	// Loads from cross-origin-sites
	# header("Access-Control-Allow-Origin: https://inf.bodin.ac.th");
    // Define functions
    function unauth($end = true, $ssoonly = false) {
        if (!$ssoonly && isset($_SESSION['auth'])) unset($_SESSION['auth']); // authorized information
        # if (!$ssoonly && isset($_SESSION['stif'])) unset($_SESSION['stif']); // static system infos
        if (isset($_COOKIE['bdSSOv1a'])) { // SSO information
            setcookie("bdSSOv1a", "", time(), "/", ".bodin.ac.th");
            setcookie("bdSSOv1a", "", time(), "/", "bod.in.th");
        } if ($end) echo '{"success": true}';
    }
    function sso() {
        // v1
        if (isset($_COOKIE['bdSSOv1a']) && $_COOKIE['bdSSOv1a']<>"") {
            // Decode token
            require("TianTcl.php");
            $token = $tcl -> decode($_COOKIE['bdSSOv1a'], 2);
            $info = explode(",", $token);
            include("getip.php");
            if ($info[2]==$ip) {
                if (time()<=intval($info[3])) {
                    $_POST['username'] = $info[1];
                    $_POST['zone'] = $info[0];
                    auther(false, true);
                } else { unauth(false, true); echo '{"success": false, "reason": [1, "Timeout!"]}'; }
            } else { unauth(false, true); echo '{"success": false, "reason": [3, "Invalid token!"]}'; }
        } else { unauth(false, true); echo '{"success": false, "reason": [1, "Token not found!"]}'; }
    }
    function auther($sso = false) {
        include("db_connect.php");
        unauth(false);
        # if (strpos($hrfr, $_SERVER['SERVER_NAME'])>-1) {
            if (true || isset($_POST['token'])) {
                if (true || in_array(array(sha1(time()-1),sha1(time()),sha1(time()+1)), $_POST['token'])) {
                    if (isset($_POST['username'])) {
                        $user = strval($db -> real_escape_string(trim($_POST['username'])));
                        if ($user<>"") {
                            $userdat = $db -> query("SELECT * FROM users WHERE idcode='$user'");
                            if ($userdat) {
                                if ($userdat -> num_rows == 1) {
                                    // Get system information
                                    require_once("reload_settings.php");
                                    require_once("config.php");
                                    // Save login information
                                    include("db_connect.php");
                                    if (!$sso)  $db -> query("UPDATE users SET lastlogin=".strval(time())." WHERE idcode='$user'");
                                    // Get user data
                                    $getdat = $userdat -> fetch_array(MYSQLI_ASSOC);
                                    // Check account status
                                    if ($getdat['status']=="A") {
                                        // Set SSO data
                                        if (!$sso && !isset($_COOKIE['bdSSOv1a'])) {
                                            // v1
                                            require("TianTcl.php"); include("getip.php");
                                            $exptimeout = strval(time()+259200);
                                            $info = $_POST['zone'].",".$_POST['username'].",$ip,$exptimeout";
                                            $token = $tcl -> encode($info, 2);
                                            setcookie("bdSSOv1a", $token, $exptimeout, "/", ".bodin.ac.th");
                                            setcookie("bdSSOv1a", $token, $exptimeout, "/", "bod.in.th");
                                        }
                                        // Update prefix by age
                                        $zone = $_POST['zone'];
                                        $_SESSION['auth'] = array(
                                            "user" => $user,
                                            "type" => ($zone == 0 ? "s" : "t"),
                                            "name" => array(
                                                "th" => $db -> real_escape_string($_POST['name']['th']),
                                                "en" => $db -> real_escape_string($_POST['name']['en'])
                                            ), "url" => array(
                                                "created" => intval($getdat['url_created']),
                                                "clicks" => intval($getdat['url_clicks'])
                                            ), "is_admin" => ($getdat['admin']=="Y")
                                        ); echo '{"success": true}';
                                    } else echo '{"success": false, "reason": [1, "Your account is '.statuscode2text($getdat['status'])['en'].'<br>บัญชีของคุณถูก'.statuscode2text($getdat['status'])['th'].'"]}';
                                } else echo '{"success": false, "reason": [1, "You are not a user of this site."]}'; // No record of this user found
                            } else echo '{"success": false, "reason": [3, "Unable to get user\'s data."]}';
                        } else echo '{"success": false, "reason": [3, "Username empty."]}'; /**/
                    } else echo '{"success": false, "reason": [1, "No parameter."]}';
                } else echo '{"success": false, "reason": [3, "Invalid token."]}';
            } else echo '{"success": false, "reason": [1, "Token not set."]}';
        # } else header("Location: /".$_GET['return_url']);
        if (isset($db)) $db -> close();
    }
    // Action code
    global $hrfr; $hrfr = ($_SERVER['HTTP_REFERER'] ?? "");
    if ($hrfr<>"") {
        if (isset($_GET['way'])) {
            if ($_GET['way']=="out") unauth();
            else if ($_GET['way']=="in" && isset($_GET['return_url'])) auther();
            else if ($_GET['way']=="in") auther();
            else if ($_GET['way']=="sso") sso();
            else echo '{"success": false, "reason": [3, "Invalid direction."]}';
        } else if (isset($_GET['return_url'])) header("Location: /".$_GET['return_url']);
        else echo '{"success": false, "reason": [1, "Direction not set."]}';
    } else header("Location: /".$_GET['return_url']);
?>
<?php
	session_start();
    // Define functions
    function unauth($end = true) {
        if (isset($_SESSION['auth'])) unset($_SESSION['auth']); // authorized information
        if (isset($_SESSION['stif'])) unset($_SESSION['stif']); // static system infos
        if ($end) echo '{"success": true}';
    }
    function auther($hrfr) {
        include("db_connect.php");
        unauth(false);
        if (strpos($hrfr, $_SERVER['SERVER_NAME'])>-1) {
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
                                    $db -> query("UPDATE users SET lastlogin=".strval(time())." WHERE idcode='$user'");
                                    // Get user data
                                    $getdat = $userdat -> fetch_array(MYSQLI_ASSOC);
                                    // Update prefix by age
                                    $namep = prefixcode2text($getdat['namep']); $_SESSION['auth'] = array(
                                        "user" => $user,
                                        "name" => array(
                                            "en" => array(
                                                "p" => $namep['en'],
                                                "f" => $getdat['namefen'],
                                                "l" => $getdat['namelen'],
                                                "a" => $namep['en']." ".$getdat['namefen']." ".$getdat['namelen']
                                            ), "th" => array(
                                                "p" => $namep['th'],
                                                "f" => $getdat['namefth'],
                                                "l" => $getdat['namelth'],
                                                "a" => $namep['th'].$getdat['namefth']."  ".$getdat['namelth']
                                            )
                                        ), "url" => array(
                                            "created" => intval($getdat['url_created'])
                                        )
                                    ); echo '{"success": true, "reason": ""}';
                                } else echo '{"success": false, "reason": [1, "You are not a user of this site."]}'; // No record of this user found
                            } else echo '{"success": false, "reason": [3, "Unable to get user\'s data."]}';
                        } else echo '{"success": false, "reason": [3, "Username empty."]}'; /**/
                    } else echo '{"success": false, "reason": [1, "No parameter."]}';
                } else echo '{"success": false, "reason": [3, "Invalid token."]}';
            } else echo '{"success": false, "reason": [1, "Token not set."]}';
        } else header("Location: /".$_GET['return_url']);
        if (isset($db)) $db -> close();
    }
    // Action code
    $hrfr = ($_SERVER['HTTP_REFERER'] ?? "");
    if ($hrfr<>"") {
        if (isset($_GET['way'])) {
            if ($_GET['way']=="out") unauth();
            else if ($_GET['way']=="in" && isset($_GET['return_url'])) auther($hrfr);
            else if ($_GET['way']=="in") auther($hrfr);
            else echo '{"success": false, "reason": [3, "Invalid direction."]}';
        } else if (isset($_GET['return_url'])) header("Location: /".$_GET['return_url']);
        else echo '{"success": false, "reason": [1, "Direction not set."]}';
    } else header("Location: /".$_GET['return_url']);
?>
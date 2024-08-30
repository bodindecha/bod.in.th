<?php
	session_start();
	if (isset($_POST['cmd']) && isset($_POST['target'])) { $has_data = true; $cmd = $_POST['cmd']; $dat = $_POST['target']; $attr = $_POST['attr']; }
	else if (isset($_GET['cmd']) && isset($_GET['target'])) { $has_data = true; $cmd = $_GET['cmd']; $dat = $_GET['target']; $attr = $_GET['attr']; }
	else $has_data = false; if ($has_data) {
		include("db_connect.php"); require_once("config.php");
		if ($cmd == "create") {
			// Get information
			$typo = strtoupper($_SESSION['auth']['type']);
			$dat = trim($dat); $attr = trim($attr);
			// Scanning
			if (!(preg_match($regex_url, $attr) || preg_match($regex_tmt, strtolower($attr))) || preg_match("/(?:\ )/", $attr)) $i_dup = array(0, "Invalid url");
			if (!($dat == "" || ((strlen($dat)>=3 && strlen($dat)<5 && preg_match("/([a-zA-Z]+)/", $dat) && preg_match("/([0-9]+)/", $dat)) || (strlen($dat)>=5 && strlen($dat)<=150 && (preg_match("/([a-zA-Z]+)/", $dat) || preg_match("/([0-9]+)/", $dat)))) && preg_match("/^[a-zA-Z0-9\_\-]+$/", $dat))) $i_dup = array(0, "Invalid custom short URL");
			// Cleaning
			if (!preg_match("/^((http(s)?:)?\/\/)/", $attr) && !preg_match($regex_tmt, strtolower($attr))) $attr = "http://".$attr;
			if (preg_match($regex_tmt, $attr)) $attr = strtolower($attr);
			$attr = preg_replace("/(#|\?|\?#)$/", "", $attr);
			// Generate
			function gen_rand($typo) {
				include("db_connect.php");
				$ss_randstr = ""; for ($ss_i = 0; $ss_i < 8; $ss_i++) $ss_randstr .= substr("abcdfghjklmnpqrstvwxyzABCDFGHJKLMNPQRSTVWXYZ0123456789_-", rand(0,54), 1);
				if (preg_match("/([a-zA-Z]+)/", $ss_randstr) && preg_match("/([0-9]+)/", $ss_randstr)) {
					$r_query = $db -> query("SELECT urlid FROM urls WHERE type='$typo' AND keyword='$ss_randstr'");
					if ($r_query->num_rows == 1) $ss_randstr = gen_rand($typo);
				} else $ss_randstr = gen_rand($typo);
				$db -> close();
				return $ss_randstr;
			}
			if (!isset($db)) include("db_connect.php");
			if (!isset($i_dup) && $dat=="") $dat = gen_rand($typo);
			// Check URL duplicates
			if (!isset($i_dup)) {
				$u_query = $db -> query("SELECT type,keyword FROM urls WHERE rdrto='$attr'");
				if ($u_query->num_rows == 1) { $u_each = $u_query -> fetch_array(MYSQLI_ASSOC); $i_dup = array(1, $u_each['type'], $u_each['keyword']); }
			}
			// Check CSU duplicates
			if (!isset($i_dup)) {
				$c_query = $db -> query("SELECT rdrto FROM urls WHERE type='$typo' AND keyword='$dat'");
				if ($c_query->num_rows == 1) { $c_each = $c_query -> fetch_array(MYSQLI_ASSOC); $i_dup = array(2, $c_each['rdrto']); }
			}
			// Shorten
			if (!isset($i_dup)) {
				// Insert new URL
				$db -> query("INSERT INTO urls (type,keyword,rdrto,owner) VALUES ('$typo','$dat','$attr','".$_SESSION['auth']['user']."')");
				// Record creation log
				require_once("getip.php");
				$db -> query("INSERT INTO log_action (user,act,feild,old,new,ipaddr) VALUES ('".$_SESSION['auth']['user']."','C','','$attr','$dat','$ip')");
				// Update user data
				$db -> query("UPDATE users SET url_created=url_created+1 WHERE idcode='".$_SESSION['auth']['user']."'");
				// End point
				$i_dup = array(3, $dat, $attr);
			} // Send out response
			switch ($i_dup[0]) {
				case 0: echo '{"success": false, "reason": [3, "'.$i_dup[1].'"]}'; break;
				case 1: echo '{"success": false, "reason": [1, "Original URL has already been shortened as <a href=\"/'.($i_dup[1]=="S"?"!":($i_dup[1]=="M"?"@":"")).$i_dup[2].'\" target=\"_blank\">'.$_SERVER['SERVER_NAME']."/".($i_dup[1]=="S"?"!":($i_dup[1]=="M"?"@":"")).$i_dup[2].'</a>"]}'; break;
				case 2: echo '{"success": false, "reason": [1, "Custom short name has already been used for <a href=\"'.$i_dup[1].'\" target=\"_blank\">'.ensure_length($i_dup[1]).'</a>"]}'; break;
				case 3: echo '{"success": true, "reason": [0, "Original URL (<a href=\"'.$i_dup[2].'\" target=\"_blank\">'.ensure_length($i_dup[2]).'</a>) is now shortened as <a href=\"/'.($typo=="S"?"!":($typo=="M"?"@":"")).$i_dup[1].'\" target=\"_blank\">'.$_SERVER['SERVER_NAME']."/".($typo=="S"?"!":($typo=="M"?"@":"")).$i_dup[1].'</a>"], "data": ["'.($typo=="S"?"!":($typo=="M"?"@":"")).$i_dup[1].'", "'.$i_dup[2].'", "'.ensure_length($i_dup[2]).'", "'.date("Y-m-d H:i:s", time()).'"]}'; break;
				default: echo '{"success": false, "reason": [2, "Unknown"]}'; break;
			} # header("Content-type: text/json");
		} else if ($cmd == "change") {
			$type = str_split($dat)[0]; $type = ($type == "@" ? "M" : ($type == "!" ? "S" : "T"));
			if ($attr == "Y" || $attr == "N") {
				$dat = preg_replace('/^(!|@)/', "", $db -> real_escape_string($dat));
				$success = $db -> query("UPDATE urls SET active='$attr' WHERE type='$type' AND keyword='$dat'");
				if ($success) {
					require_once("getip.php");
					$db -> query("INSERT INTO log_action (user,act,feild,old,new,ipaddr) VALUES ('".$_SESSION['auth']['user']."','E','S','$dat','$attr','$ip')");
					echo '{"success": true, "reason": [0, "URL status updated"]}';
				} else echo '{"success": false, "reason": [3, "Unable to change URL status"]}';
			}
		} else if ($cmd == "") {
			
		}
		$db -> close();
	}
?>
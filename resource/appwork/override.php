<?php
	session_start();
	if (isset($_POST['cmd']) && isset($_POST['target'])) { $has_data = true; $cmd = $_POST['cmd']; $dat = $_POST['target']; $attr = $_POST['attr']; }
	else if (isset($_GET['cmd']) && isset($_GET['target'])) { $has_data = true; $cmd = $_GET['cmd']; $dat = $_GET['target']; $attr = $_GET['attr']; }
	else $has_data = false; if ($has_data) {
		include("db_connect.php"); require_once("config.php");
		if ($cmd == "") {
			
		}
		$db -> close();
	}
?>
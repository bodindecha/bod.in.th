<?php
	function statuscode2text($statuscode) {
		switch ($statuscode) {
			case "A": $statusen = "Active"; $statusth = "เปิดใช้งาน"; break;
			case "I": $statusen = "Inactive"; $statusth = "ปิดใช้งาน"; break;
			case "O": $statusen = "Deactivated"; $statusth = "ปิดใช้งานชั่วคราว"; break;
			case "U": $statusen = "Unactivated"; $statusth = "ยังไม่ถูกเปิดใช้งาน"; break;
			case "S": $statusen = "Suspended"; $statusth = "ถูกระงับ"; break;
			case "D": $statusen = "Deleted"; $statusth = "ถูกลบ"; break;
			default: $statusen = ""; $statusth = ""; break;
		} return array("en" => $statusen, "th" => $statusth);
	}
	function prefixcode2text($prefixcode) {
		switch (intval($prefixcode)) {
			case 1: $namepen = "Master"; $namepth = "ด.ช."; break;
			case 2: $namepen = "Mr."; $namepth = "นาย"; break;
			case 3: $namepen = "Miss"; $namepth = "ด.ญ."; break;
			case 4: $namepen = "Ms."; $namepth = "น.ส."; break;
			case 5: $namepen = "Mrs."; $namepth = "นาง"; break;
			case 6: $namepen = "A/Sub Lt. "; $namepth = "ว่าที่ ร.ต."; break;
			case 7: $namepen = "A/Sub Lt."; $namepth = "ว่าที่ ร.ต.หญิง "; break;
			case 8: $namepen = "Sqn Ldr."; $namepth = "น. ต.หญิง"; break;
			case 9: $namepen = "Dr."; $namepth = "ดร."; break;
			default: $namepen = ""; $namepth = ""; break;
		} return array("en" => $namepen, "th" => $namepth);
	}
	function utmScode2text($utmcode) {
		switch (intval($utmcode)) {
			case 1: $utmtxten = "Direct"; $utmtxtth = "โดยตรง"; break;
			case 2: $utmtxten = "Home"; $utmtxtth = "หน้าหลัก"; break;
			default: $utmtxten = ""; $utmtxtth = ""; break;
		} return array("en" => $utmtxten, "th" => $utmtxtth);
	}
	function utmCcode2text($utmcode) {
		switch (intval($utmcode)) {
			case 1: $utmtxten = "Paste"; $utmtxtth = "แปะ/พิมพ์"; break;
			case 2: $utmtxten = "Link"; $utmtxtth = "กดลิ้งก์"; break;
			case 3: $utmtxten = "Line"; $utmtxtth = "จากไลน์"; break;
			case 4: $utmtxten = "Facebook"; $utmtxtth = "จากเฟซบุ๊ก"; break;
			default: $utmtxten = ""; $utmtxtth = ""; break;
		} return array("en" => $utmtxten, "th" => $utmtxtth);
	}
	function utmStext2code($utmtext) {
		switch (strtolower($utmtext)) {
			case "direct": $utmcode = 1; break;
			case "โดยตรง": $utmcode = 1; break;
			case "home": $utmcode = 2; break;
			case "หน้าหลัก": $utmcode = 2; break;
			default: $utmcode = 0; break;
		} return strval($utmcode);
	}
	function utmCtext2code($utmtext) {
		switch (strtolower($utmtext)) {
			case "paste": $utmcode = 1; break;
			case "แปะ/พิมพ์": $utmcode = 1; break;
			case "link": $utmcode = 2; break;
			case "กดลิ้งก์": $utmcode = 2; break;
			case "line": $utmcode = 3; break;
			case "จากไลน์": $utmcode = 3; break;
			case "facebook": $utmcode = 4; break;
			case "จากเฟซบุ๊ก": $utmcode = 4; break;
			default: $utmcode = 0; break;
		} return strval($utmcode);
	}
?>
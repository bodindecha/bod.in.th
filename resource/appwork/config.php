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
?>
<?php
	include("resource/hpe/init_ps.php");
	$header_title = "Administrator";

	if ($_SESSION['auth']['is_admin']) {
		require_once("resource/appwork/config.php"); include("resource/appwork/db_connect.php");
		$read_url = $db -> query("SELECT type,keyword,rdrto,click,owner,active,created FROM urls ORDER BY urlid DESC");
		$read_user = $db -> query("SELECT idcode,status,lastlogin,url_created,url_clicks FROM users WHERE lastlogin IS NOT NULL OR NOT url_created=0 ORDER BY lastlogin DESC,url_clicks DESC,url_created DESC");
		$db -> close();
	} else header("Location: /dashboard");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php include("resource/hpe/heading.php"); include("resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			
		</style>
		<link rel="stylesheet" href="/resource/css/extend/ControlPanel.css">
		<script type="text/javascript">
			$(document).ready(function() {
				<?php if (isset($_GET['q'])) echo 'go("'.$_GET['q'].'");'; ?>
				$("html body main div.container form.create div input:not([type=submit])").on("keyup", validate_input);
				$(window).on("resize", function() {
					setTimeout(resize_view_table, 250);
				}).trigger("resize");
			});
			function validate_input(r=false) {
				var url = document.querySelector('form.create input[name="url"]').value.trim(), csu = document.querySelector('form.create input[name="csu"]').value.trim();
				var reg_url = <?php echo $regex_url; ?>, reg_tmt = <?php echo $regex_tmt; ?>;
				var nlu = url.toLowerCase(), blacklisted_url = false, blacklisted_csu = false, whitelisted = false;
				for (bld of val.myd.concat(val.bld)) { if (!blacklisted_url && nlu.includes(bld+"/")) blacklisted_url = true; }
				for (blk of val.niw) { if (!blacklisted_csu && (csu.toLowerCase().includes(blk) || leet.totxt(csu).toLowerCase().includes(blk))) blacklisted_csu = true; }
				for (wld of val.wld) { if (!whitelisted && nlu.toLowerCase().includes(wld+"/")) { whitelisted = true; if (blacklisted_url) blacklisted_url = false; } }
				val_url = ((((reg_url.test(url) || reg_tmt.test(nlu)) && !/(?:\ )/.test(url)) || whitelisted) && !blacklisted_url);
				val_csu = (((csu == "" && !whitelisted) || (((csu.length>=3 && csu.length<5 && /([a-zA-Z]+)/.test(csu) && /([0-9]+)/.test(csu)) || (csu.length>=5 && csu.length<=150 && (/([a-zA-Z]+)/.test(csu) || /([0-9]+)/.test(csu)))) && /^[a-zA-Z0-9\_\-]+$/.test(csu))) && !blacklisted_csu);
				document.querySelector("form.create button").disabled = !(val_url && val_csu);
				document.querySelector('form.create input[name="url"]').style.outline = ((url=="")?"none":"1px solid #"+((val_url)?"BFFAC4":"FACDBF"));
				document.querySelector('form.create input[name="csu"]').style.borderBottom = "1px solid #"+((csu=="")?"777":((val_csu)?"00EF15":"DD0C0C"));
				if (r) return (val_url && val_csu);
			}
			function shortit() {
				if (validate_input(true)) gen_req();
				return false;
				function gen_req() {
					var obj_url = $('form.create input[name="url"]'), obj_csu = $('form.create input[name="csu"]');
					var data = {
						cmd: "create",
						attr: obj_url.val().trim(),
						target: obj_csu.val().trim()
					}; $.post("/resource/appwork/override", data, function(res, hsc) {
						var dat = JSON.parse(res);
						document.querySelector("form.create button").disabled = true;
						app.ui.notify(1, dat.reason);
						// if (typeof dat.data !== "undefined") console.log(dat.data);
						if (dat.success) {
							obj_url.val(""); obj_csu.val("");
							var new_row = $('<tr><td><span>'+dat.data[0]+'</span> <a onClick="copy(this)" href="javascript:void(0)"><i class="material-icons">content_copy</i></a></td><td>'+dat.data[2]+' <a href="'+dat.data[1]+'" target="_blank"><i class="material-icons">open_in_new</i></a></td><td>0 <a data-title="View Analystics" onClick="ViewAnalystic(\''+dat.data[0]+'\', event)" href="javascript:void(0)"><i class="material-icons">show_chart</i></a></td><td>Active <a onClick="change_status(this)" href="javascript:void(0)"><i class="material-icons">power_settings_new</i></a></td><td>'+dat.data[3]+'</td><td><?php echo $_SESSION['auth']['user']; ?></td></tr>'), vtbl = "html body main div.container div.lists div.viewport div.viewer div.table table tbody";
							if (document.querySelector(vtbl) != null) {
								if ($(vtbl).children().length >= 30) $(vtbl).children().last().remove();
								$(vtbl).prepend(new_row); resize_view_table();
							} else location.reload();
						}
					});
				}
			}
		</script>
		<script type="text/javascript" src="/resource/js/extend/ControlPanel.js"></script>
		<script type="text/javascript" src="/resource/js/extend/leeter.min.js"></script>
		<script type="text/javascript" src="/resource/js/lib/w3.min.js"></script>
	</head>
	<body>
		<?php include("resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<form method="post" class="jumpto"><label>Jump to </label><input type="text" placeholder="Enter URL or Keyword"><button onClick="return go()" class="cyan">Go!</button></form>
				<div class="account">
					<span>ชื่อผู้ใช้งาน : <?php echo $_SESSION['auth']['name'][$_COOKIE['set_lang']]; ?></span>
					<span>บัญชี : <a onClick="profile(this, event)" href="javascript:void(0)"><?php echo $_SESSION['auth']['user']; ?></a></span>
				</div>
				<form class="create">
					<h2>Shorten URL</h2>
					<div><span>Enter original URL</span><input name="url" type="url" required placeholder="https://"></div>
					<div><span>New URL will be <font>"<?php echo $_SERVER['SERVER_NAME']."/".($_SESSION['auth']['type']=="s"?"!":"");?></font></span>
						<font><input name="csu" type="text" placeholder="Custom short url"></font>
						<span><font>"</font></span>
						<a href="javascript:random_short_url()" title="Randomize short URL" role="button" class="yellow"><i class="material-icons">bubble_chart</i></a>
					</div>
					<center><button disabled onClick="return shortit()" class="blue">ย่อลิงก์</button></center>
				</form>
				<div class="lists">
					<input id="ref_murlop" type="checkbox" hidden>
					<div class="accordian"><label for="ref_murlop"><span>All URLs</span><i class="marker material-icons ripple-click">keyboard_arrow_down</i></label></div>
					<div class="viewport" style="--e: 91px;">
						<div class="action" <?php if($read_url->num_rows==0)echo"disabled";?>>
							<div disabled onClick="">Disable/Enable All</div>
							<div disabled onClick="">View Analystics</div>
						</div>
						<div class="action">
							<div class="filter"><input type="search" placeholder="Filter ... (ตัวกรอง)" onInput="fd(1)"/><i class="material-icons">filter_list</i></div>
						</div>
						<div class="viewer">
							<?php
								if ($read_url -> num_rows > 0) {
									echo '<div class="table"><table><thead>
											<th onClick="rovt(1)">Short URL</th>
											<th onClick="rovt(2)">Redirects to</th>
											<th onClick="rovt(3)">Clicks</th>
											<th onClick="rovt(4)">Status</th>
											<th onClick="rovt(5)">Created</th>
											<th onClick="rovt(6)">Owner</th>
										</thead><tbody>';
									while ($mu = $read_url -> fetch_assoc()) echo '<tr>
											<td><span>'.($mu['type']=="S"?"!":($mu['type']=="M"?"@":"")).base64_decode($mu['keyword']).'</span> <a data-title="Copy URL" onClick="copy(this)" href="javascript:void(0)"><i class="material-icons">content_copy</i></a></td>
											<td>'.ensure_length($mu['rdrto']).' <a data-title="Open link" href="'.$mu['rdrto'].'" target="_blank"><i class="material-icons">open_in_new</i></a></td>
											<td>'.$mu['click'].' <a data-title="View Analystics" onClick="ViewAnalystic(\''.($mu['type']=="S"?"!":($mu['type']=="M"?"@":"")).base64_decode($mu['keyword']).'\', event)" href="javascript:void(0)"><i class="material-icons">show_chart</i></a></td>
											<td>'.($mu['active']=="Y"?"Active":"Disabled").' <a onClick="change_status(this)" href="javascript:void(0)"><i class="material-icons">power_settings_new</i></a></td>
											<td>'.$mu['created'].'</td>
											<td>'.$mu['owner'].'</td>
										</tr>';
									echo '</tbody></table></div>';
								} else echo '<center class="message gray">You haven\'t create any short URL yet</center>';
							?>
						</div>
					</div>
				</div>
				<div class="lists">
					<input id="ref_users" type="checkbox" hidden>
					<div class="accordian"><label for="ref_users"><span>Recent Users</span><i class="marker material-icons ripple-click">keyboard_arrow_down</i></label></div>
					<div class="viewport" style="--e: 51px;">
						<div class="action">
							<div class="filter"><input type="search" placeholder="Filter ... (ตัวกรอง)" onInput="fd(2)"/><i class="material-icons">filter_list</i></div>
						</div>
						<div class="viewer">
							<?php
								if ($read_user -> num_rows > 0) {
									echo '<div class="table"><table><thead>
											<th onClick="rovt(1, 2)">Username</th>
											<th onClick="rovt(2, 2)">Status</th>
											<th onClick="rovt(3, 2)">Last signed-in</th>
											<th onClick="rovt(4, 2)">URL created</th>
											<th onClick="rovt(5, 2)">URL clicks</th>
										</thead><tbody>';
									while ($eu = $read_user -> fetch_assoc()) echo '<tr>
											<td>'.$eu['idcode'].' <a data-title="View profile" href="https://inf.bodin.ac.th/'.$eu['idcode'].'" target="_blank"><i class="material-icons">visibility</i></a></td>
											<td>'.statuscode2text($eu['status'])[$_COOKIE['set_lang']].'</td>
											<td>'.date("Y-m-d H:i:s", $eu['lastlogin']).'</td>
											<td>'.$eu['url_created'].'</td>
											<td>'.$eu['url_clicks'].'</td>
										</tr>';
									echo '</tbody></table></div>';
								} else echo '<center class="message gray">No recently active users yet</center>';
							?>
						</div>
					</div>
				</div>
			</div>
		</main>
		<?php include("resource/hpe/material.php"); ?>
		<footer>
			<?php include("resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>
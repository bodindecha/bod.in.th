<?php
	header("Location: /error/903");
	include("../hpe/init_ps.php");
	$header_title = "Edit URL";
	$header_desc = "แก้ไขข้อมูลของลิงก์ย่อ";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php include("../hpe/heading.php"); include("../hpe/init_ss.php"); ?>
		<style type="text/css">
			html body main div.container {
				padding: 30px 10px 20px;
				position: relative; left: 50%; transform: translateX(-50%);
				max-width: calc(95% - 20px);
				font-size: 18.75px; font-family: "Sarabun", serif;
				overflow-x: hidden;
			}
			@media (min-width: 768px) { main div.container { width: 750px; } }
			@media (min-width: 992px) { main div.container { width: 970px; } }
			@media (min-width: 1200px) { main div.container { width: 1170px; } }
			@media (max-width: 768px) {
				html body main div.container { font-size: 12.5px; line-height: 18.75px; }
				html body main div.container > h1 { font-size: 27.5px; line-height: 42.5px; }
			}
		</style>
		<script type="text/javascript">
			
		</script>
	</head>
	<body>
		<?php include("../hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				
			</div>
		</main>
		<?php include("../hpe/material.php"); ?>
		<footer>
			<?php include("../hpe/footer.php"); ?>
		</footer>
	</body>
</html>
<?php
	include("resource/hpe/init_ps.php");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php include("resource/hpe/heading.php"); include("resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			html body main div.container {
				padding: 30px 10px 20px;
				position: relative; left: 50%; transform: translateX(-50%);
				max-width: calc(95% - 20px);
				font-size: 18.75px; font-family: "Sarabun", serif; text-align: center;
				overflow-x: hidden;
			}
			@media (min-width: 768px) { main div.container { width: 750px; } }
			@media (min-width: 992px) { main div.container { width: 970px; } }
			@media (min-width: 1200px) { main div.container { width: 1170px; } }
			html body main div.container > * { margin: 0px 0px 10px; }
			html body main div.container form { display: flex; justify-content: center; }
			html body main div.container form button { margin-left: 7.5px; }
			html body main div.container form input {
				padding: 0px 7.5px;
				width: 275px; max-width: calc(100% - 70px); height: 45px; line-height: 45px;
				font-size: 17.5px; font-family: "Open sans", serif;
				border-radius: 3px; border: 1px solid var(--clr-bs-gray-dark);
				transition: var(--time-tst-fast);
			}
			html body main div.container form input:focus { box-shadow: 0 0 7.5px .125px var(--clr-bs-blue); }
			html body main div.container form input::placeholder { font-family: "Quicksand", sans-serif; }
			html body main div.container div {
				--c: var(--clr-gg-grey-500);
				color: var(--c);
				display: flex; justify-content: center;
			}
			html body main div.container div span {
				margin: auto 0px;
				width: /* 50% */ 250px; height: 1px;
				background-color: var(--c);
				display: block;
			}
			html body main div.container div label { margin: 0px 7.5px; }
			@media (max-width: 768px) {
				html body main div.container { font-size: 12.5px; line-height: 18.75px; }
				html body main div.container form input {
					padding: 0px 5px;
					width: 150px; height: 30px; line-height: 30px;
					font-size: 12.5px;
				}
				html body main div.container div span { width: 125px; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				<?php
					if (isset($_GET['return_url'])) echo "app.sys.auth.orize('".$_GET['return_url']."')";
					else if (isset($_GET['q'])) echo 'go("'.$_GET['q'].'");';
				?>
			});
			const full_url_regex = /^((http(s)?:\/\/)?bod\.in\.th\/)?(?!(error|dashboard|admin))(@|!)?[A-Za-z0-9_\-]{3,150}$/;
			function go(get = null) {
				var from_form = (get==null);
				if (from_form) get = $("html body div.container form input").val();
				get = get.trim();
				if (get=="") app.ui.notify(1, [1, "Please enter some information"]);
				else {
					get = get.replace(/^(http(s)?:\/\/)?bod\.in\.th\//, "");
					if (/^(?!(error|dashboard|admin))(@|!)?[A-Za-z0-9_\-]{3,150}$/.test(get)) location = "/"+get+"?utm_source=home&utm_campaign=paste";
					else app.ui.notify(1, [3, "Invalid information format"]);
				} if (from_form) return false;
			}
		</script>
	</head>
	<body>
		<?php include("resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<form method="post"><input type="text" placeholder="Enter URL or Keyword"><button onClick="return go()" class="cyan">Go!</button></form>
				<div><span></span><label>หรือ</label><span></span></div>
				<button onClick="app.sys.auth.orize()" class="green">เข้าสู่ระบบ</button>
			</div>
		</main>
		<?php include("resource/hpe/material.php"); ?>
		<footer>
			<?php include("resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>
<?php
	include("../hpe/init_ps.php");
	$header_title = "Developers";
	$header_desc = "App Font lists";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php include("../hpe/heading.php"); include("../hpe/init_ss.php"); ?>
		<style type="text/css">
			html body main section.container {
				position: relative; top: 25px; left: 50%; transform: translateX(-50%);
				/* width: 960px; max-width: 95vw; */ width: 95%;
			}
			html body main section.container ul {
				margin: 0px; padding: 0px;
				list-style-type: none;
				display: grid; grid-template-columns: repeat(2, 1fr);
			}
			@media only screen and (max-width: 768px) { html body main section.container ul { grid-template-columns: repeat(1, 1fr); } }
			@media only screen and (min-width: 1440px) { html body main section.container ul { grid-template-columns: repeat(3, 1fr); } }
			html body main section.container ul a:link, html body main section.container ul a:visited {
				text-decoration: none; color: var(--clr-main-black-absolute) !important;
				transition: var(--time-tst-fast);
			}
			html body main section.container ul a:hover { text-decoration: none !important; }
			html body main section.container ul a:active { transform: scale(1.125); z-index: 1; }
			html body main section.container ul a li {
				margin: 5px; padding: 5px;
				border-radius: 10px; border: 2.5px solid #999;
				background-color: rgba(250, 250, 250, 0.0625); backdrop-filter: blur(5px);
			}
			html body main section.container ul a:hover li { background-color: rgba(250, 250, 250, 0.625); }
			html body main section.container ul a li * { display: block; }
			html body main section.container ul a li b { font-size: 25px; text-decoration: underline; }
			html body main section.container ul a li span { font-size: 18.75px; }
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				for (let i = 1; i<=document.querySelectorAll("html body main section.container ul a").length; i++) {
					document.querySelector("html body main section.container ul a:nth-child("+i.toString()+")").href = "javascript:cfn("+i.toString()+")";
					$("html body main section.container ul a:nth-child("+i.toString()+") li").css("font-family", "'"+$("html body main section.container ul a:nth-child("+i.toString()+") li b").text()+"'");
				} $("html body main section.container ul a li").append($("<span>A B C D E F G H I J K L M N O P Q R S T U V W X Y Z a b c d e f g h i j k l m n o p q r s t u v w x y z 0 1 2 3 4 5 6 7 8 9 ๐ ๑ ๒ ๓ ๔ ๕ ๖ ๗ ๘ ๙ ก ข ฃ ค ฅ ฆ ง จ ฉ ช ซ ฌ ญ ฎ ฏ ฐ ฑ ฒ ณ ด ต ถ ท ธ น บ ป ผ ฝ พ ฟ ภ ม ย ร ฤ ฤๅ ล ฦ ฦๅ ว ศ ษ ส ห ฬ อ ฮ ~ ? ! @ # $ % ^ & ( ) _ + - * / \ = - ` [ ] { } ; : ' \" , . < ></span>"));
			});
			function cfn(fi) {
				const elem = document.createElement('textarea'); let fontname = $("html body main section.container ul a:nth-child("+fi.toString()+") li b").text();
				elem.value = fontname;
				document.body.appendChild(elem); elem.select(); document.execCommand('copy'); document.body.removeChild(elem);
				app.ui.notify(1, [0, "Font name copied!<br>\""+fontname+"\""]);
			}
		</script>
	</head>
	<body>
		<?php include("../hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<section class="container">
				<ul>
					<a><li><b>Akaya Telivigala</b></li></a>
					<a><li><b>Balsamiq Sans</b></li></a>
					<a><li><b>Bitter</b></li></a>
					<a><li><b>Caladea</b></li></a>
					<a><li><b>Cormorant Upright</b></li></a>
					<a><li><b>Dancing Script</b></li></a>
					<a><li><b>Dosis</b></li></a>
					<a><li><b>Itim</b></li></a>
					<a><li><b>Kanit</b></li></a>
					<a><li><b>Modak</b></li></a>
					<a><li><b>Oswald</b></li></a>
					<a><li><b>Permanent Marker</b></li></a>
					<a><li><b>Prompt</b></li></a>
					<a><li><b>Quicksand</b></li></a>
					<a><li><b>Ranchers</b></li></a>
					<a><li><b>Roboto</b></li></a>
					<a><li><b>Sarabun</b></li></a>
					<a><li><b>Jokerman</b></li></a>
					<a><li><b>orange juice</b></li></a>
					<a><li><b>pixelmix</b></li></a>
					<a><li><b>THKodchasal</b></li></a>
					<a><li><b>THKrub</b></li></a>
					<a><li><b>THSarabunNew</b></li></a>
					<a><li><b>TianTcl-en_01</b></li></a>
					<a><li><b>TianTcl-th_01</b></li></a>
				</ul>
			</section>
		</main>
		<?php include("../hpe/material.php"); ?>
		<footer>
			<?php include("../hpe/footer.php"); ?>
		</footer>
	</body>
</html>
<?php
	include("../hpe/init_ps.php");
	$header_title = "Analystic";
	$header_desc = "ประมวลลิงก์ (สรุป)";

	include("db_connect.php");
	if (isset($_GET['key'])) {
        // Tune
        if (preg_match("/^[A-Za-z0-9_\-]{3,150}$/", $_GET['key'])) { $key = $_GET['key']; $type = "T"; }
        else if (preg_match("/^@[A-Za-z0-9_\-]{3,150}$/", $_GET['key'])) { $key = ltrim($_GET['key'], "@"); $type = "M"; }
        else if (preg_match("/^![A-Za-z0-9_\-]{3,150}$/", $_GET['key'])) { $key = ltrim($_GET['key'], "!"); $type = "S"; }
        // Find
        if (isset($type)) {
            require_once("config.php");
			$read_url = $db -> query("SELECT urlid,rdrto,click,owner,active FROM urls WHERE type='$type' AND keyword='".base64_encode($key)."'");
            if ($read_url -> num_rows == 1) {
                $get_url = $read_url -> fetch_array(MYSQLI_ASSOC);
				if ($get_url['owner']==$_SESSION['auth']['user'] || $_SESSION['auth']['is_admin']) {
					/* $read_stat = $db -> query("SELECT utm_source,utm_campaign,ccode,time,useragent FROM log_click WHERE urlid='".$get_url['urlid']."'");
					$has_stat = $read_stat -> num_rows > 0; */
					$has_stat = intval($get_url['click']) > 0;
				} else $error = 901;
			} else $error = 900;
			
		} else $error = 902;
	} else $error = 902;
	if (isset($error)) {
		$header_title = "Error (".strval($error).")";
		unset($header_desc);
	} else if ($has_stat) {
		$dataset = array(
			"hour" => array(),
			"days" => array(),
			"month" => array(),
			"zone" => array(),
			# "source" => array(),
			# "campg" => array()
			"utms" => array()
		); $time = time();
		// Hour
		$read_stat = $db -> query("SELECT SUBSTRING(time, 12, 2) hour,COUNT(logid) click FROM log_click WHERE urlid=".$get_url['urlid']." GROUP BY hour");
		while ($ea = $read_stat -> fetch_assoc()) $dataset['hour'][$ea['hour']] = $ea['click'];
		for ($i = 0; $i < 86400; $i += 3600) { if (!isset($dataset['hour'][date("H", $i)])) $dataset['hour'][date("H", $i)] = "0"; }
		// Daily
		$read_stat = $db -> query("SELECT SUBSTRING(time, 1, 10) date,COUNT(logid) click FROM log_click WHERE urlid=".$get_url['urlid']." GROUP BY date");
		while ($ea = $read_stat -> fetch_assoc()) $dataset['days'][$ea['date']] = $ea['click'];
		mysqli_data_seek($read_stat, 0); $temp = $read_stat -> fetch_array(MYSQLI_ASSOC); $min = strtotime($temp['date']);
		for ($i = $min; $i < $time; $i += 86400) { if (!isset($dataset['days'][date("Y-m-d", $i)])) $dataset['days'][date("Y-m-d", $i)] = "0"; }
		// Monthly
		$read_stat = $db -> query("SELECT SUBSTRING(time, 1, 7) month,COUNT(logid) click FROM log_click WHERE urlid=".$get_url['urlid']." GROUP BY month");
		while ($ea = $read_stat -> fetch_assoc()) $dataset['month'][$ea['month']] = $ea['click'];
		mysqli_data_seek($read_stat, 0); $temp = $read_stat -> fetch_array(MYSQLI_ASSOC); $min = strtotime($temp['month']."-01");
		for ($i = $min; $i < $time; $i += 2419200) { if (!isset($dataset['month'][date("Y-m", $i)])) $dataset['month'][date("Y-m", $i)] = "0"; }
		// Zone
		$read_stat = $db -> query("SELECT ccode,COUNT(logid) click FROM log_click WHERE urlid=".$get_url['urlid']." GROUP BY ccode");
		while ($ea = $read_stat -> fetch_assoc()) $dataset['zone'][$ea['ccode']] = $ea['click'];
		// Source
		$read_stat = $db -> query("SELECT utm_source,COUNT(logid) click FROM log_click WHERE urlid=".$get_url['urlid']." GROUP BY utm_source");
		while ($ea = $read_stat -> fetch_assoc()) $dataset['source'][intval($ea['utm_source'])] = $ea['click'];
		for ($i = 1; $i <= 3; $i++) { if (!isset($dataset['source'][$i])) $dataset['source'][$i] = "0"; }
		// Campaign
		$read_stat = $db -> query("SELECT utm_campaign,COUNT(logid) click FROM log_click WHERE urlid=".$get_url['urlid']." GROUP BY utm_campaign");
		while ($ea = $read_stat -> fetch_assoc()) $dataset['campg'][intval($ea['utm_campaign'])] = $ea['click'];
		for ($i = 1; $i <= 5; $i++) { if (!isset($dataset['campg'][$i])) $dataset['campg'][$i] = "0"; }
		/* // Source
		$read_stat = $db -> query("SELECT utm_source,utm_campaign,COUNT(logid) click FROM log_click WHERE urlid=".$get_url['urlid']." GROUP BY utm_source,utm_campaign"); */
		// Sortings
		ksort($dataset['hour']); ksort($dataset['days']); ksort($dataset['month']); arsort($dataset['source']); arsort($dataset['campg']); # ksort($dataset['zone']);
	} $db -> close();
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php include("../hpe/heading.php"); include("../hpe/init_ss.php"); ?>
		<style type="text/css">
			html body main iframe {
				width: 100%; height: calc(var(--window-height) - var(--top-height));
				border: none;
			}
			html body main div.container {
				padding: 30px 10px 20px;
				position: relative; left: 50%; transform: translateX(-50%);
				max-width: calc(95% - 20px);
				font-size: 18.75px; font-family: "Sarabun", serif;
				overflow-x: hidden;
			}
			html body main div.container > * { margin: 0px 0px 10px; }
			html body main div.container > p > a > i.material-icons { transform: translateY(2.5px); }
			html body main div.container > p.oint { display: flex; }
			html body main div.container > p.oint span.txtoe { max-width: calc(100% - 118px); }
			/* html body main div.container > p.oint span:last-child {
				width: 125px;
				display: inline-block;
			}
			html body main div.container > p.oint a:before { transform: translate(16px, calc(100% + 2.5px)); }
			html body main div.container > p.oint a:after { transform: translate(100%, calc(100% + 5.5px)) rotate(135deg); } */
			html body main div.container div.vb {
				--bd : 1px solid var(--clr-main-black-absolute);
				margin-top: 30px;
				border-radius: 12.5px; border: var(--bd);
				overflow: hidden;
			}
			html body main div.container div.vb > input:checked ~ div.accordian label i.marker { transform: scale(1.25, -1.25); }
			html body main div.container div.vb > input:checked ~ div.viewport { height: var(--avh); border-top: var(--bd); }
			html body main div.container div.vb div.accordian label {
				padding: 7.5px 10px;
				font-family: "Open Sans", "Sarabun", serif;
				display: flex; justify-content: space-between; cursor: pointer;
			}
			html body main div.container div.vb div.accordian label span { font-size: 18.75px; line-height: 25px; }
			html body main div.container div.vb div.accordian label i.marker {
				position: relative; transform: scale(1.25, 1.25);
				width: 25px; height: 25px;
				border-radius: 50%;
				line-height: 25px; text-align: center; /* list-style-type: disclosure-open;
				display: list-item; */ display: block; transition: var(--time-tst-xfast);
			}
			html body main div.container div.vb div.accordian label i.marker:hover { background-color: var(--fade-black-8); }
			html body main div.container div.vb div.viewport {
				height: 0px;
				transition: var(--time-tst-fast) ease-in-out;
			}
			div.viewport span.wrapup { display: block; }
			div.viewport span.wrapup div.tab {
				margin: 0px;
				display: flex; overflow: hidden;
			}
			div.viewport span.wrapup div.tab div {
				padding: 7.5px 10px;
				width: 100%; height: 30px;
				line-height: 30px; text-align: center;
				cursor: pointer; transition: var(--time-tst-xfast) ease;
			}
			div.viewport span.wrapup div.tab div:hover { background-color: var(--clr-pp-blue-50); }
			div.viewport span.wrapup div.tab div.active {
				background-color: var(--fade-black-8);
				pointer-events: none;
			}
			div.viewport span.wrapup div.tab + span.bar-responsive {
				margin-bottom: 0px;
				transform: translate(calc(100% * var(--show)), -100%);
				width: calc(100% / var(--o)); height: 2.5px;
				background-color: var(--clr-bs-blue);
				display: block; transition: var(--time-tst-xfast);
				pointer-events: none;
			}
			/* div.viewport span.wrapup div.tab:active + span.bar-responsive { animation: bar_moving var(--time-tst-fast) ease 1; } */
			@keyframes bar_moving {
				0%, 100% { width: 50%; }
				50% { width: 75%; }
			}
			div.viewport span.wrapup div.tbs { transform: translateY(-2.5px); }
			div.viewport [name^="Visit"] { width: 100%; }
			@media (min-width: 768px) { main div.container { width: 750px; } }
			@media (min-width: 992px) { main div.container { width: 970px; } }
			@media (min-width: 1200px) { main div.container { width: 1170px; } }
			@media (max-width: 768px) {
				html body main div.container { font-size: 12.5px; line-height: 18.75px; }
				html body main div.container > h1 { font-size: 27.5px; line-height: 42.5px; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				google.charts.load("current", { packages: ["geochart"], mapsApiKey: "AIzaSyAKMCcQbqlEHV6yhmIYLTrROAFrrE5HCLA" });
				google.charts.setOnLoadCallback(function() {
					$(window).on("resize", resize).trigger("resize");
				}); ShowTime(1); // ShowUtm(0);
			});
			function resize() {
				let ec = $('html body main div.container div.vb div.viewport [name="VisitDaily"]');
				$('html body main div.container div.vb div.viewport canvas[name^="Visit"]').attr("width", ec.width().toString());
				$('html body main div.container div.vb div.viewport canvas[name^="Visit"]').attr("height", (ec.width()*9/16).toString());
				$('html body main div.container div.vb div.viewport div[name^="Visit"]').css("width", ec.width().toString()+"px");
				$('html body main div.container div.vb div.viewport div[name^="Visit"]').css("height", (ec.width()*9/16).toString()+"px");
				$('html body main div.container div.vb div.viewport canvas[name="VisitUtms"]').css("height", ec.width().toString()+"px");
				resize_tabs().then(PlotGraph);
			}
			async function resize_tabs() { document.querySelectorAll("html body main div.container div.vb div.viewport").forEach((ea) => {
				$(ea).css("--avh", $(ea.children[0]).outerHeight().toString()+"px"); });
			}
			// URL function
			function copyURL() {
				const ce = document.createElement("textarea"); ce.value = location.hostname+"/"+"<?php echo $_GET['key']; ?>";
				document.body.appendChild(ce); ce.select(); document.execCommand("copy"); document.body.removeChild(ce);
				app.ui.notify(1, [0, "Short URL copied!"]);
			}
			// Viewer function
			function ShowTime(what) {
				$("div.viewport.time span.wrapup div.tab div.active").removeClass("active");
				$('div.viewport.time span.wrapup div.tab div[onClick="ShowTime('+what.toString()+')"]').addClass("active");
				$("div.viewport.time span.wrapup div.tab + span.bar-responsive").css("--show", what.toString());
				$("div.viewport.time span.wrapup div.tbs > div").hide();
				$('div.viewport.time span.wrapup div.tbs > div[order="'+what.toString()+'"]').show();
			}
			function ShowUtm(what) {
				$("div.viewport.utm span.wrapup div.tab div.active").removeClass("active");
				$('div.viewport.utm span.wrapup div.tab div[onClick="ShowUtm('+what.toString()+')"]').addClass("active");
				$("div.viewport.utm span.wrapup div.tab + span.bar-responsive").css("--show", what.toString());
				$("div.viewport.utm span.wrapup div.tbs > div").hide();
				$('div.viewport.utm span.wrapup div.tbs > div[order="'+what.toString()+'"]').show();
			}
			var dataset = {
				Hours: { labels: [null<?php foreach ($dataset['hour'] as $h => $c) echo ",\"$h\""; ?>], datasets: [{
						label: "Clicks",
						data: [null<?php foreach ($dataset['hour'] as $h => $c) echo ",$c"; ?>],
						fill: true,
						borderColor: "#3367D6",
						tension: 0.1
				}] }, Daily: { labels: [null<?php foreach ($dataset['days'] as $d => $c) echo ",\"$d\""; ?>], datasets: [{
						label: "Clicks",
						data: [null<?php foreach ($dataset['days'] as $d => $c) echo ",$c"; ?>],
						fill: true,
						borderColor: "#3367D6",
						tension: 0.1
				}] }, Monthly: { labels: [""<?php foreach ($dataset['month'] as $m => $c) echo ",\"$m\""; ?>], datasets: [{
						label: "Clicks",
						data: [0<?php foreach ($dataset['month'] as $m => $c) echo ",$c"; ?>],
						fill: true,
						borderColor: "#3367D6",
						tension: 0.1
				}] }, Zones: [ ["Country Code", "Clicks"]
					<?php foreach ($dataset['zone'] as $z => $c) echo ",['$z', $c]"; ?>
				], /* Source: { labels: [null<?php foreach ($dataset['source'] as $s => $c) echo ",\"".utmScode2text($s)[$_COOKIE['set_lang']]."\""; ?>], datasets: [{
						label: "Clicks",
						data: [0<?php foreach ($dataset['source'] as $s => $c) echo ",$c"; ?>],
						fill: true,
						borderColor: "#3367D6",
						tension: 0.1
				}] }, Campaign: { labels: [null<?php foreach ($dataset['campg'] as $m => $c) echo ",\"".utmCcode2text($m)[$_COOKIE['set_lang']]."\""; ?>], datasets: [{
						label: "Clicks",
						data: [0<?php foreach ($dataset['campg'] as $m => $c) echo ",$c"; ?>],
						fill: true,
						borderColor: "#3367D6",
						tension: 0.1
				}] } */ Utms: { labels: [null<?php
						# for ($i = 1; $i <= 5; $i++) { for ($j = ($i==1?2:1); $j <= ($i==1?3:1); $j++) echo ',"'.utmCcode2text($i)[$_COOKIE['set_lang']].' '.utmScode2text($j)[$_COOKIE['set_lang']].'"'; }
						foreach ($dataset['campg'] as $m => $c) { if ($c<>"0") echo ",\"".utmCcode2text($m)[$_COOKIE['set_lang']]."\""; } foreach ($dataset['source'] as $s => $c) { if ($c<>"0") echo ",\"".utmScode2text($s)[$_COOKIE['set_lang']]."\""; }
					?>], datasets: [ { label: [null<?php foreach ($dataset['campg'] as $m => $c) echo ",\"".utmCcode2text($m)[$_COOKIE['set_lang']]."\""; ?>],
						data: [0<?php foreach ($dataset['campg'] as $m => $c) echo ",$c"; ?>],
						backgroundColor: ["#303134", "#559FD3", "#06C755", "#2D88FF", "#444649"]
					}, { label: [null<?php foreach ($dataset['source'] as $s => $c) echo ",\"".utmScode2text($s)[$_COOKIE['set_lang']]."\""; ?>],
						data: [0<?php foreach ($dataset['source'] as $s => $c) echo ",$c"; ?>],
						backgroundColor: ["#6610F2", "#007BFF", "#28A745"]
				}] }
			}; dataset.Hours.labels.shift(); dataset.Hours.datasets[0].data.shift();
			dataset.Daily.labels.shift(); dataset.Daily.datasets[0].data.shift();
			dataset.Monthly.labels.shift(); dataset.Monthly.datasets[0].data.shift();
			/* dataset.Source.labels.shift(); dataset.Source.datasets[0].data.shift();
			dataset.Camapaign.labels.shift(); dataset.Camapaign.datasets[0].data.shift(); */ dataset.Utms.labels.shift();
			dataset.Utms.datasets[0].backgroundColor.shift(); dataset.Utms.datasets[0].data.shift();
			dataset.Utms.datasets[1].backgroundColor.shift(); dataset.Utms.datasets[1].data.shift();
			function PlotGraph() {
				Object.keys(dataset).forEach((key) => {
					let canvas = document.querySelector('div.vb div.viewport [name="Visit'+key+'"]'), chart;
					if (canvas!=null) {
						var mode = $(canvas).attr("data-mode");
						if (mode == "js-line") {
							dataset[key].datasets[0].backgroundColor = (dataset[key].labels.length<2 ? "#3367D6" : "#007BFF10");
							// canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
							chart = new Chart(canvas.getContext('2d'), {
								type: (dataset[key].labels.length<2?"bar":"line"),
								data: dataset[key], options: {
									responsive: true, plugins: { title: {
										display: false,
										text: key+" click(s)"
									} }, scales: { y: { min: 0 } }
								}
							});
						} else if (mode == "js-bar") {
							dataset[key].datasets[0].backgroundColor = "#3367D6";
							// canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
							chart = new Chart(canvas.getContext('2d'), {
								type: "bar",
								data: dataset[key], options: {
									responsive: true, plugins: { title: {
										display: false,
										text: key+" click(s)"
									} }, scales: { y: { min: 0 } }
								}
							});
						} else if (mode == "js-pie") {
							// dataset[key].datasets[0].backgroundColor = "#3367D6";
							// canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
							chart = new Chart(canvas.getContext('2d'), {
								type: "pie",
								data: dataset[key], options: {
									responsive: true, plugins: { legend: { labels: { generateLabels: function(chart) {
										// Get the default label list
										const original = Chart.overrides.pie.plugins.legend.labels.generateLabels;
										const labelsOriginal = original.call(this, chart);
										// Build an array of colors used in the datasets of the chart
										var datasetColors = chart.data.datasets.map(function(e) { return e.backgroundColor; });
										datasetColors = datasetColors.flat();
										// Modify the color and hide state of each label
										labelsOriginal.forEach(label => {
											// There are twice as many labels as there are datasets. This converts the label index into the corresponding dataset index
											label.datasetIndex = (label.index - label.index % 2) / 2;
											// The hidden state must match the dataset's hidden state
											label.hidden = !chart.isDatasetVisible(label.datasetIndex);
											// Change the color to match the dataset
											label.fillStyle = datasetColors[label.index];
										}); return labelsOriginal;
									} }, onClick: function(mouseEvent, legendItem, legend) {
										// toggle the visibility of the dataset from what it currently is
										legend.chart.getDatasetMeta(legendItem.datasetIndex).hidden = legend.chart.isDatasetVisible(legendItem.datasetIndex);
										legend.chart.update();
									} }, tooltip: { callbacks: { label: function(context) {
										const labelIndex = (context.datasetIndex * 2) + context.dataIndex;
										return context.chart.data.labels[labelIndex] + ': ' + context.formattedValue;
									} } } }
								}
							});
						} else if (mode == "gg-geo") {
							chart = new google.visualization.GeoChart(canvas);
							let gData = google.visualization.arrayToDataTable(dataset[key]);
							chart.draw(gData, { /*
								displayMode: "text",
								colorAxis: {colors: ["#28A745", "#007BFF"]} */
							});
						}
					}
				});
			}
		</script>
		<script type="text/javascript" src="/resource/js/lib/chart.min.js"></script>
		<script type="text/javascript" src="/resource/js/lib/charts.min.js"></script>
	</head>
	<body>
		<?php include("../hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<?php
				if (!isset($error)) {
					echo '<div class="container">
						<p>Keyword : '.$_GET['key'].' <a onClick="copyURL()" data-title="Copy URL" href="javascript:void(0)"><i class="material-icons">content_copy</i></a></p>
						<p class="oint">Redirect : &nbsp;<span class="txtoe">'.$get_url['rdrto'].'</span><span>&nbsp; <a data-title="Open link" href="'.$get_url['rdrto'].'" target="_blank"><i class="material-icons">open_in_new</i></a></span></p>
						<p>Total clicks : <font style="color: var(--clr-bs-blue)">'.$get_url['click'].'</font></p>
						<p>Status : <font style="color: var(--clr-bs-'.($get_url['active']=="Y"?'green)">Active':'red)">Disabled').'</font></p>';
					if ($has_stat) {
						echo '<div class="vb">
								<input id="ref_ac1" type="checkbox" hidden>
								<div class="accordian"><label for="ref_ac1"><span>การเยี่ยมชมตามเวลา</span><i class="marker material-icons ripple-click">keyboard_arrow_down</i></label></div>
								<div class="viewport time">
									<span class="wrapup">
										<div class="tab">
											<div onClick="ShowTime(0)">รายชั่วโมง</div>
											<div onClick="ShowTime(1)">รายวัน</div>
											<div onClick="ShowTime(2)">รายเดือน</div>
										</div><span class="bar-responsive" style="--o: 3"></span>
										<div class="tbs">
											<div order="0">
												<canvas name="VisitHours" data-mode="js-line"></canvas>
											</div>
											<div order="1">
												<canvas name="VisitDaily" data-mode="js-line"></canvas>
											</div>
											<div order="2">
												<canvas name="VisitMonthly" data-mode="js-line"></canvas>
											</div>
										</div>
									</span>
								</div>
							</div>
							<div class="vb">
								<input id="ref_ac2" type="checkbox" hidden>
								<div class="accordian"><label for="ref_ac2"><span>การเยี่ยมชมตามพื้นที่</span><i class="marker material-icons ripple-click">keyboard_arrow_down</i></label></div>
								<div class="viewport place">
									<div name="VisitZones" data-mode="gg-geo"></div>
								</div>
							</div>
							<div class="vb">
								<input id="ref_ac3" type="checkbox" hidden>
								<div class="accordian"><label for="ref_ac3"><span>แหล่งการเข้าถึง</span><i class="marker material-icons ripple-click">keyboard_arrow_down</i></label></div>
								<div class="viewport utm">
									<!--span class="wrapup">
										<div class="tab">
											<div onClick="ShowUtm(0)">รายชั่วโมง</div>
											<div onClick="ShowUtm(1)">รายวัน</div>
										</div><span class="bar-responsive" style="--o: 2"></span>
										<div class="tbs">
											<div order="0">
												<canvas name="VisitSource" data-mode="js-bar"></canvas>
											</div>
											<div order="1">
												<canvas name="VisitCampaign" data-mode="js-bar"></canvas>
											</div>
										</div>
									</span-->
									<canvas name="VisitUtms" data-mode="js-pie"></canvas>
								</div>
							</div>';
					} else echo '<center class="message gray">This link has no analystics</center>';
					echo '</div>';
				} else echo '<iframe src="/error/'.strval($error).'"></iframe>';
			?>
		</main>
		<?php include("../hpe/material.php"); ?>
		<footer>
			<?php include("../hpe/footer.php"); ?>
		</footer>
	</body>
</html>
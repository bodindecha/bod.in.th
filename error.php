<?php
	session_start();
	$my_url = ($_SERVER['REQUEST_URI']=="/")?"":"?return_url=".urlencode(ltrim($_SERVER['REQUEST_URI'], "/"));
	// HSC REF : iana.org/assignments/http-status-codes/http-status-codes.xhtml
	$http_status_codes = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		103 => 'Checkpoint',
		// 104-199 => 'Unassigned',
		
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		// 209-225 => 'Unassigned',
		226 => 'IM Used',
		// 227-299 => 'Unassigned',
		
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Switch Proxy',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		// 309-399 => 'Unassigned',
		
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		419 => 'Client Error',
		420 => 'Enhance Your Calm',
		421 => 'Misdirected Request',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		// 427 => 'Unassigned',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		// 430 => 'Unassigned',
		431 => 'Request Header Fields Too Large',
		// 432-443 => 'Unassigned',
		444 => 'Connection Closed Without Response',
		// 445-448 => 'Unassigned',
		449 => 'Retry With',
		450 => 'Blocked by Windows Parental Controls',
		451 => 'Unavailable For Legal Reasons',
		// 452-498 => 'Unassigned',
		499 => 'Client Closed Request',
		
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
		// 512-525 => 'Unassigned',
		526 => 'Invalid SSL certificate',
		// 527-598 => 'Unassigned',
		599 => 'Network Connect Timeout Error',
		
		// Bschool's system HSC
		900 => 'Not found!',
		901 => 'No permission',
		902 => 'Wrong!',
		903 => 'Page under construction',
		904 => 'JS disabled',
		905 => 'Server error',
		906 => 'Safari not supported'
	);
	$hsc = $_GET['hsc']; if ($hsc == "" || $http_status_codes[$hsc] == "") { header("Location: /error/900"); }
	$header_title = "Error ($hsc)";
	$header_desc = $http_status_codes[$hsc];
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php include('resource/hpe/heading.php'); include('resource/hpe/init_ss.php'); ?>
		<noscript><style type="text/css">html body main { padding-bottom: 69px; }</style></noscript>
		<style type="text/css">
			html body main section.container {
				position: relative; top: 34.5px; left: 50%; transform: translateX(-50%);
				min-width: 46%; width: 690px; max-width: 97.75%; max-height: calc(100% - 69px);
				overflow-x: visible; overflow-y: auto;
			}
			html body main section.container div.show {
				margin: 5.75px; padding: 5.75px 5.75px 11.5px;
				width: calc(100% - 11.5px - 11.5px);
				border-radius: 23px; box-shadow: 0 0 2.3px 3px rgba(69, 69, 69, 0.345) inset; /*background-image: linear-gradient(to bottom, rgba(169, 137, 10, 0.138), rgba(169, 137, 10, 0.023));*/
				transition: 0.138s;
			}
			html body main section.container div.show:hover { box-shadow: 0 0 2.925px 4.025px rgba(69, 69, 69, 0.3) inset; }
			html body main section.container div.show div:first-child { font-size: 46px; text-align: center; }
			html body main section.container div.show div:last-child {
				margin-top: 5.75px;
				font-size: 17.25px; text-align: center;
			}
			html body main section.container div.act { margin-top: 11.5px; padding: 11.5px; }
			html body main section.container div.act ul {
				margin: 0px; padding: 0px;
				display: flex; justify-content: center;
			}
			html body main section.container div.act ul li {
				margin: 2.875px 11.5px; padding: 5.75px 11.5px;
				height: 18.6875px;
				font-size: 14.375px;
				border-radius: 15.09375px; background-color: rgba(184, 255, 253, 0.1); box-shadow: 0 0 1.15px 1.955px rgba(57.5, 57.5, 57.5, 0.46);
				display: inline-block; list-style-type: none; cursor: pointer; transition: 0.092s;
			}
			html body main section.container div.act ul li:hover { background-color: var(--fade-black-8); border: 0.023px solid rgba(103, 68, 9, 0.23); }
		</style>
	</head>
	<body>
		<?php include("resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<section class="container">
				<div class="show">
					<div><?php echo $hsc." : ".$http_status_codes[$hsc]; ?></div>
					<div>Sorry, we're unable to load or access the page you are entering.</div>
				</div>
				<div class="act">
					<ul>
						<li onCLick="window.top.location='/'">Home</li>
						<li onCLick="window.top.history.back()">Back</li>
						<li onCLick="window.top.location.reload()">Reload</li>
					</ul>
				</div>
			</section>
		</main>
		<?php include("resource/hpe/material.php"); ?>
		<footer>
			<?php include("resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>
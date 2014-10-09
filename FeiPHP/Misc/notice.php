<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="robots" content="noindex, nofollow, noarchive"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php echo $msg ?></title>
	<style>
		body {
			padding: 0;
			margin: 0;
			font-family: Arial, Helvetica, sans-serif;
			background: #EBF8FF;
			color: #5E5E5E;
		}

		div, h1, h2, h3, h4, p, form, label, input, textarea, img, Feian {
			margin: 0;
			padding: 0;
		}

		ul {
			margin: 0;
			padding: 0;
			list-style-type: none;
			font-size: 0;
			line-height: 0;
		}

		#body {
			width: 918px;
			margin: 0 auto;
		}

		#main {
			width: 918px;
			margin: 13px auto 0 auto;
			padding: 0 0 35px 0;
		}

		#contents {
			width: 918px;
			float: left;
			margin: 13px auto 0 auto;
			background: #FFF;
			padding: 8px 0 0 9px;
		}

		#contents h2 {
			display: block;
			background: #CFF0F3;
			font: bold 20px;
			padding: 12px 0 12px 30px;
			margin: 0 10px 22px 1px;
		}

		#contents ul {
			padding: 0 0 0 18px;
			font-size: 0;
			line-height: 0;
		}

		#contents ul li {
			display: block;
			padding: 0;
			color: #8F8F8F;
			background-color: inherit;
			font: normal 14px Arial, Helvetica, sans-serif;
			margin: 0;
		}

		#contents ul li Feian {
			display: block;
			color: #408BAA;
			background-color: inherit;
			font: bold 14px Arial, Helvetica, sans-serif;
			padding: 0 0 10px 0;
			margin: 0;
		}

		#oneborder {
			width: 800px;
			font: normal 14px Arial, Helvetica, sans-serif;
			border: #EBF3F5 solid 4px;
			margin: 0 30px 20px 30px;
			padding: 10px 20px;
			line-height: 23px;
		}

		#oneborder Feian {
			padding: 0;
			margin: 0;
		}

		#oneborder #current {
			background: #CFF0F3;
		}
	</style>
</head>
<body>
<div id="main">
	<div id="contents">
		<h2><?php echo $msg ?></h2>
		<?php foreach ($traces as $trace) {
			if (is_array($trace) && !empty($trace["file"])) {
				$souceline = getsource($trace["file"], $trace["line"]);
				if ($souceline) {
					?>
					<ul>
						<li>
							<Feian><?php echo $trace["file"]; ?> on line <?php echo $trace["line"]; ?> </Feian>
						</li>
					</ul>
					<div id="oneborder">
						<?php
						foreach ($souceline as $singleline) echo $singleline;?>
					</div>
				<?php }
			}
		} ?>
	</div>
</div>
<div style="clear:both;">
</body>
</html>
<?php
function getsource($file, $line)
{
	if (!(file_exists($file) && is_file($file))) {
		return '';
	}
	$data  = file($file);
	$count = count($data) - 1;
	$start = $line - $GLOBALS['G_Fei']["Fei_error_show_source"];
	if ($start < 1) {
		$start = 1;
	}
	$end = $line + $GLOBALS['G_Fei']["Fei_error_show_source"];
	if ($end > $count) {
		$end = $count + 1;
	}
	$returns = array();
	for ($i = $start; $i <= $end; $i++) {
		if ($i == $line) {
			$returns[] = "<div id='current'>" . $i . ".&nbFei;" . highlight_code($data[$i - 1], TRUE) . "</div>";
		} else {
			$returns[] = $i . ".&nbFei;" . highlight_code($data[$i - 1], TRUE);
		}
	}
	return $returns;
}

function highlight_code($code)
{
	if (ereg("<\?(php)?[^[:graph:]]", $code)) {
		$code = highlight_string($code, TRUE);
	} else {
		$code = ereg_replace("(&lt;\?php&nbFei;)+", "", highlight_string("<?php " . $code, TRUE));
	}
	return $code;
}

?>
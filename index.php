<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Raspberry Pi Infos</title>
	<link rel="icon" type="image/png" href="info-rasp/img/favicon.png" />
	<link rel="stylesheet" href="info-rasp/css/reset.css">
	<link rel="stylesheet" href="info-rasp/css/style.css">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
</head>
<body>

<?php
	
	# Get the temperature data for the graphs
	$txt_file= file_get_contents('info-rasp/temp_log.dat');
	if(!$txt_file) {
		echo "<br>ERROR - There is no information for this date.";
	} else {
		$rows= explode("\n", $txt_file);
		array_pop($rows);
		$findata=array();
		foreach($rows as $row => $data) {
			$all= explode(" ", $data);
			$date= explode("-", $all[0]);
			$time= explode(":", $all[1]);
			$temp= floor($all[2] * 100) / 100;
			$findata[]="[Date.UTC($date[2], $date[1]-1, $date[0], $time[0], $time[1], $time[2]), $temp]";	
		}
		$findatas= implode(",", $findata);
	}

	# Get number of updates
	$txt_file = file_get_contents('info-rasp/updates/updates.txt');
	$upd = array();
	if(!$txt_file) {
		$upd[0] = "reading file error";
		$upd[1] = "reading file error";
	} else {
		$upd = explode("\n", $txt_file);
	}

	# Get the percentage of disk usage
	$junk = shell_exec("df -BM | grep '/dev/root'");
    preg_match_all('!\d+!', $junk, $matches);
    $nums = $matches[0];
    $rootu = $nums[1] / 1024.0;
    $roott = ($nums[1] + $nums[2]) / 1024.0;
   
	# Read the current memory output of free -mt
	$raw = array();
	$handle = popen('free -mt 2>&1', 'r');
	while (!feof($handle)) {
		$raw[] = fgets($handle);
	}
	pclose($handle);
	foreach($raw as $key => $val) {
  		if (strpos($val,"Mem:") !== FALSE) {
			list($junk,$tmem,$umem,$fmem,$shared,$buff,$cache) = preg_split('/ +/',$val);
  		}
  		if (strpos($val,"Swap:") !== FALSE) {
    			list($junk,$tswap,$uswap,$fswap) = preg_split('/ +/',$val);
  		}
  		if (strpos($val,"Total:") !== FALSE) {
    			list($junk,$ttot,$utot, $ftot) = preg_split('/ +/',$val);
  		}
	}
	$permem  = ($umem-$buff-$cache) / $tmem * 100.0;
	$perswap = $uswap / $tswap * 100.0;
	$pertot  = ($utot-$buff-$cache) / $ttot * 100.0;

	function progresscolor($per) {
		if ($per<25.0)  { echo "background-color: #86e01e;"; }
		if ($per>25.0 AND $per<50.0)  { echo "background-color: #f2d31b;"; }
		if ($per>50.0 AND $per<75.0)  { echo "background-color: #f27011;"; }
		if ($per>75.0)  { echo "background-color: #f63a0f;"; }
	}

	# Get uptime info from system call to uptime
	$uptime = exec("/usr/bin/uptime");
	$junk = explode("user",$uptime);
    $leftuptime = $junk[0];
    $rightuptime = explode(":",$junk[1]);
    $loads = explode(",",$rightuptime[1]);

	function formatteduptime($uptime) {
		preg_match_all('!\d+!', $uptime, $matches);
		$nums=$matches[0];
		$day = 0; $hour = 0; $min = 0;
		if (strpos($uptime,"day")) {
			$day = intval($nums[3]);
			if (strpos($uptime,"min")) {
				$min = intval($nums[4]);
			} else {
				$hour = intval($nums[4]);
				$min =  intval($nums[5]);
			}
		} else {
			if (strpos($uptime,"min")) {
					$min = intval($nums[3]);
				} else {
					$hour = intval($nums[3]);
					$min =  intval($nums[4]);
				}
		}
		if ($day  == 1) { echo $day  . " day "; }
		if ($day  >  1) { echo $day  . " days "; }
		if ($hour == 1) { echo $hour . " hour "; }
        if ($hour >  1) { echo $hour . " hours "; }
		if ($min  == 1) { echo $min  . " minute "; }
        if ($min  >  1) { echo $min  . " minutes "; }
	}

	function warning($thr,$value) {
		if ($value>$thr) { echo 'style="color: red; font-weight: 700;"'; }
	}

?>

<script type="text/javascript">
	$(function () {
        $('#temperature').highcharts({
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Last 200 temperature detections'
            },
            subtitle: {
                text: 'Source: /sys/class/thermal/thermal_zone0/temp'
            },
            xAxis: {
            	type: 'datetime',
            },
            yAxis: {
                title: {
                    text: 'Temperature'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
             tooltip: {
                 valueSuffix: ' °C'
            },
            series: [{
                name: 'Temperature',
                data: [<?php echo $findatas; ?>]
            }]
        });
    });
</script>

<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>

<div id="tutto">

	<div id="sidemenu">
		<p><a class="cool-link" href="info-rasp/temp-long.php">Detailed temperature</a></p>
		<p><a class="cool-link" href=#>Cool link 1</a></p>
		<p><a class="cool-link" href=#>Cool link 2</a></p>
		<p><a class="cool-link" href=#>Cool link 3</a></p>
	</div>

	<div id="container">
		<div id="temperature"></div>

		<div class="info">
			<div class="bar-label">Uptime:</div>
			<div class="bar-value"><?php echo formatteduptime($uptime) ?></div>
		</div>
		<div class="info">
			<div class="bar-label">Security updates:</div>
			<div class="bar-value" <?php warning(0,$upd[0]); echo ">".$upd[0]?></div>
		</div>
		<div class="info">
			<div class="bar-label">Non-security updates:</div>
			<div class="bar-value" <?php warning(4,$upd[1]); echo ">".$upd[1]?></div>
		</div>
		<div class="info">
			<div class="bar-label">CPU loads:</div>
			<div class="bar-value">
				<span <?php warning(1.0,$loads[0]); echo ">".$loads[0]?></span> (1 min),
				<span <?php warning(1.0,$loads[1]); echo ">".$loads[1]?></span> (5 mins),
				<span <?php warning(1.0,$loads[2]); echo ">".$loads[2]?></span> (15 mins)
			</div>
		</div>

		<div class="progress-container">
			<div class="bar-label">Total memory (chached):</div>
			<div class="bar-value"><?php echo ($utot-$buff-$cache) . " (". $utot .") / ". $ttot . " MB" ?></div>
			<div class="progress">
		      		<div class="progress-bar"
					style="width: <?php echo $pertot ?>%;
					<?php progresscolor($pertot) ?>"></div>
		   	</div>
		</div>
		<div class="progress-container">
			<div class="bar-label">RAM (chached):</div>
			<div class="bar-value"><?php echo ($umem-$buff-$cache) . " (". $umem .") / ". $tmem . " MB" ?></div>
			<div class="progress">
		      		<div class="progress-bar"
					style="width: <?php echo $permem ?>%;
					<?php progresscolor($permem) ?>"></div>
		   	</div>
		</div>
		<div class="progress-container">
			<div class="bar-label">Swap:</div>
			<div class="bar-value"><?php echo $uswap . " / ". $tswap . " MB" ?></div>
			<div class="progress">
		      		<div class="progress-bar" 
					style="width: <?php echo $perswap ?>%;
					 <?php progresscolor($perswap) ?>"></div>
		   	</div>
		</div>
		<div class="progress-container">
			<div class="bar-label">Disk usage:</div>
			<div class="bar-value"><?php printf("%3.1f / %3.1f GB", $rootu, $roott); ?></div>
			<div class="progress">
				<div class="progress-bar" 
						style="width: <?php echo $rootu/$roott*100.0 ?>%;
						<?php progresscolor($rootu/$roott*100.0) ?>"></div>
				</div>
			</div>
	</div>
</div>

</body>
</html>

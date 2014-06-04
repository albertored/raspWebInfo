<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Detailed temperature graph</title>
        <link rel="icon" type="image/png" href="img/favicon.png" />
        <link rel="stylesheet" href="css/reset.css">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
</head>
<body>

<?php
    # Get the temperature data for the graphs
    $txt_file= file_get_contents('temp_long_log.dat');
	$f = fopen('startdate.dat', 'r');
	$startdate = fgets($f);
	fclose($f);
    if(!$txt_file) {
    	echo "<br>ERROR - There is no information for this date.";
    } else {
        $rows= explode("\n", $txt_file);
        array_pop($rows);
        $findata=array();
        foreach($rows as $row => $data) {
        	$temp= floor($data * 100) / 100;
            $findata[]=$data;
        }
        $findatas= implode(",", $findata);
    }
?>

<script type="text/javascript">
	var d = new Date(<?php echo $startdate ?> * 1000)
	var n = d.getTimezoneOffset();
	var start = (<?php echo $startdate ?> - n*60) * 1000;
        $(function () {
        $('#temperature').highcharts({
            chart: {
                type: 'spline',
		zoomType: 'x'
            },
            title: {
                text: 'Last 200 temperature detections'
            },
            subtitle: {
                text: 'Source: /sys/class/thermal/thermal_zone0/temp'
            },
            xAxis: {
                type: 'datetime',
		minRange:  1 * 60 * 60 * 1000 // 1 hour
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
                pointInterval: 60 * 1000, // 1 min
                pointStart: start,
                data: [ <?php echo $findatas ?> ]
            }]
        });
    });
</script>

<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>

	<div id="temperature"></div>

</body>
</html>

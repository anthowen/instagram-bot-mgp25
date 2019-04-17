<?php
	// Global variables
	$users = array();
	$actions = array();

	set_time_limit(0);
	date_default_timezone_set('UTC');
	require __DIR__ . '/vendor/autoload.php';
	require __DIR__ . '/actions/follow.php';
	require __DIR__ . '/actions/like.php';

	// Loading User credientials from **users.csv**

	$csv_array = array_map('str_getcsv', file(__DIR__ . '/csv/users.csv'));
	foreach ($csv_array as $record) {
		$users[trim($record[0])] = $record[1];
	}

	// Loading actions from **actions.csv**

	$csv_array = array_map('str_getcsv', file(__DIR__ . '/csv/actions.csv'));
	foreach ($csv_array as $record) {
		$actions[] = $record;
	}

	// Performing actions
	$result_data = "var resultData=[";
	$col_texts = array("type", "src", "dest", "args", "status");
	foreach ($actions as $action) {

		$action_result = "{";
		foreach ($action as $key => $value) {
			$action_result .= "'" . $col_texts[$key] . "':'" 
							. $value . "',";
		}
		$action_result .= "'status':'";

		$type = $action[0];
		if($type === 'follow'){
			$status = followUser($action[1], $action[2]);
			$action_result .= ($status ? 'Success' : 'Failed');
		}
		else if($type === 'like'){
			$status = likeTimelineFeeds($action[1], $action[2], (int)$action[3]);
			$action_result .= ($status ? 'Success' : 'Failed');
		}
		else {
			$action_result .= 'Incomplete';
		}

		$action_result .= "'},";
		$result_data .= $action_result;
	}
	$result_data = substr($result_data, 0 , -1) . "];";

	file_put_contents(__DIR__ . '\js\result-data.js', $result_data);

	// Report table is created in index.html

	echo "Opening result in web browser ..." . "\n\n";
	echo "\t" . __DIR__ . '\index.html' . "\n";

	// Open\Open::open(__DIR__ . '\index.html');

<?php
function loadAll($view) {
    $view['username'] = $_SESSION['username'];
	// (1) アルバイトデータの全件読み出し
	$parttimers = array();
	$fh = fopen('./db/myfile.csv', 'r');
	flock($fh, LOCK_SH);
	$line = fgets($fh);
	while (! empty($line)) {
		$parttimer = explode(',', $line);
		array_push($parttimers, $parttimer);
		$line = fgets($fh);
	}
	flock($fh, LOCK_UN);
	fclose($fh);
	
	// (2) ビューデータの設定
	$view['parttimers'] = $parttimers;
	
	// (3) ビューデータを返す
	return $view;
}
?>
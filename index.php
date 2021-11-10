<?php

// (1) アクションファイルの読み込み
require_once './actions/LoginAction.php';
require_once './actions/InputQuestionAction.php';
require_once './actions/SearchQuestionAction.php';
require_once './actions/OtherAction.php';

// (2) セッションの開始・再開
session_start();

// (3) ビューデータの準備
$view = array();

$view['errorMessage'] = '';

// (4) イベントの取得
$event = 'showLoginPage';
if (isset($_GET['event'])) $event = $_GET['event'];

// (5) ログイン済チェック
if ((! isLoginned()) && ($event != 'checkLogin')) $event = 'showLoginPage';

// (6) イベントに応じたアクションの選択・実行
while (! is_null($event)) {
	$nextEvent = null;
	switch ($event) {
	case 'logout':
		logout();
		$nextEvent = 'showLoginPage';
		break;
	case 'showLoginPage':
		$view = prepareLogin($view);
		require './views/login.phtml';
		break;
	case 'checkLogin':
		$view = checkLogin($view);
		if($view['errorMessage'] == '') {
			if ($view['username'] == 'manager') {
				$nextEvent = 'showInput1Page';
			} else {
				$nextEvent = 'showInput2Page';
			}
		} else {
			$nextEvent = 'showLoginPage';
		}
		break;
	case 'showInput1Page':
		$view = prepareInput($view);
		require './views/input1.phtml';
		break;		
	case 'showInput2Page':
		$view = prepareInput($view);
		require './views/input2.phtml';
		break;
	case 'receiveInput':
		$view = receiveInput($view);
		require './views/confirmInput.phtml';
		break;
	case 'fixInput':
		$view = fixInput($view);
		require './views/savedResult.phtml';
		break;
	case 'showAll':
		$view = loadAll($view);
		require './views/showAll.phtml';
		break;
	case 'bbs':
		$view = bbs($view);
		require './views/bbs.phtml';
		break;
	case 'check':
		$view = check($view);
		if ($view['username'] == 'manager') {
			$nextEvent = 'showInput1Page';
		} else {
			$nextEvent = 'showInput2Page';
		}
		break;
	default:
		die("イベント ( $event ) は未定義です。");
	}
	$event = $nextEvent;
}
?>
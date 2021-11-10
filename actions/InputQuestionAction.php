<?php
function prepareInput($view) {
    $view['username'] = $_SESSION['username'];

    $view['q1_y'] = '';
    $view['q1_n'] = '';
    if (isset($_SESSION['q1'])) {
        if ($_SESSION['q1']=='yes') {
            $view['q1_y'] = 'checked';
        } elseif ($_SESSION['q1']=='no') {
            $view['q1_n'] = 'checked';
        }
    }
    $view['q2_h']  = '';
    $view['q2_ph'] = '';
    $view['q2_j']  = '';
    $view['q2_py'] = '';
    $view['q2_o']  = '';
    $view['q2_n']  = '';
    if (isset($_SESSION['q2'])) {
        if ($_SESSION['q2']=='html/css') {
            $view['q2_h'] = 'checked';
        } elseif ($_SESSION['q2']=='php') {
            $view['q2_ph'] = 'checked';
        } elseif ($_SESSION['q2']=='java') {
            $view['q2_j'] = 'checked';
        } elseif ($_SESSION['q2']=='python') {
            $view['q2_py'] = 'checked';
        } elseif ($_SESSION['q2']=='other') {
            $view['q2_o'] = 'checked';
        } elseif ($_SESSION['q2']=='nothing') {
            $view['q2_n'] = 'checked';
        }
    }
    $_SESSION['status'] = '登録前';

    return $view;
}
function receiveInput($view) {
    $view['username'] = $_SESSION['username'];
    // (3) 入力データをセッションにキャッシュ
    $_SESSION['q1'] = $_POST['q1'];
    $_SESSION['q2'] = $_POST['q2'];

    $view['q1'] = $_SESSION['q1'];
    $view['q2'] = $_SESSION['q2'];

    // (4) セッション状態の設定
    $_SESSION['status'] = '登録中';

    return $view;
}

function fixInput($view) {
    $view['username'] = $_SESSION['username'];
    // (2) セッション状態の確認
    if ($_SESSION['status'] == '登録済') {
	    die ('エラー： 「この内容で保存」ボタンを２度押さないでください');
    } elseif ($_SESSION['status'] != '登録中') {
	    die ('エラー：fixInputの不正な呼び出しです。');
    }

    // (3) セッションからデータを復元
    $q1 = $_SESSION['q1'];
    $q2 = $_SESSION['q2'];

    $view['q1'] = $_SESSION['q1'];
    $view['q2'] = $_SESSION['q2'];

    // (4) データをファイルに保存
    $fh = fopen('./db/myfile.csv', 'a');
    flock($fh, LOCK_EX);
    $line = $view['q1'] . ',' . $view['q2'] . "\n";
    fwrite($fh, $line);
    flock($fh, LOCK_UN);
    fclose($fh);

    // (5) 不要になったセッションデータの廃棄
    unset($_SESSION['q1']);
    unset($_SESSION['q2']);

    $view['q1'] = $q1;
	$view['q2'] = $q2;

    // (6) セッション状態の設定
    $_SESSION['status'] = '登録済';

    return $view;
}
?>
<?php
function prepareLogin($view) {
	// (1) 既入力データの再表示の準備
	$view['username'] = '';
	if (isset($_SESSION['username'])) $view['username'] = $_SESSION['username'];
	
	// (2) ビューデータを返す
	return $view;
}

function checkLogin($view) {
	$dsn = 'mysql:dbname=kadai;host=localhost';
	$user = 'kadai';
	$passward = 'passward';

	try{
		$dbh = new PDO($dsn, $user, $passward);
  
		$sql = "SELECT * FROM member";
  
		$stmt = $dbh->prepare($sql); //挿入する値は空のまま、SQL実行の準備をする
		$stmt->execute(); //挿入する値が入った変数をexecuteにセットしてSQLを実行
	} 
	catch (PDOException $e){
		exit('データベースに接続できませんでした' . $e->getMessage());
	}
	
	// (2) ログインデータのチェック
	$username = $_POST['username'];
	$password = $_POST['password'];

	$_SESSION['username'] = $_POST['username'];

	$view['username'] = $_SESSION['username'];
	
	foreach ($dbh->query($sql) as $row) {
		if ($username == $row['name']) {
			if ($password == $row['passward']) {
				$_SESSION['isLoginned'] = true;
				return $view;
			}
			else {
				$view['errorMessage'] = "パスワードが間違っています.";
        		return $view;
			}
		}
	}
  	$view['errorMessage'] = "ユーザ名が間違っています.";
	return $view;
}
function logout() {
	$_SESSION = array();
}

function isLoginned() {
	if (! isset($_SESSION['isLoginned'])) return false;
	return $_SESSION['isLoginned'];
}
?>
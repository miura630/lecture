<?php
function check($view) {
    $view['username'] = $_SESSION['username'];
    return $view;
}
function bbs($view) {
    $view['username'] = $_SESSION['username'];

    $filename = './db/comment.txt';
    $date = date('-Y-m-d H:i:s');
    $limit_comments = 100;
    $err_msg = [];
    $data = [];
    $comments = '';
    $view['err_msg1'] = '';
    $view['err_msg2'] = '';
    $view['err_msg3'] = '';
    $view['err_msg4'] = '';


    // 投稿されたかの確認
    if (isset($_POST['send'])) {

        // コメントが投稿された場合に入力された内容を$commentsに代入
        if (isset($_POST['comments']) === TRUE) {
            $comments = htmlspecialchars($_POST['comments'], ENT_QUOTES, 'UTF-8');
        }

        // 入力された文字数を確認しエラーメッセージを$err_msg[]に格納
        if (mb_strlen((htmlspecialchars_decode($comments, ENT_QUOTES))) > $limit_comments) {
            $view['err_msg3'] = 'コメントは100文字以内で入力してください';
        } else if (empty(trim($comments, " 　\r\n\t\0"))) {
            $view['err_msg4'] =  'コメントを入力してください。';
        }

        // テキストファイルに書き込む内容を$logに代入
        $log = $view['username'] . "：\t" . $comments . "\t" . $date . "\n";

        // $err_msg内のエラー数を確認
        if (count($err_msg) === 0) {
            // エラーが無ければテキストファイルを開く
            if (($fp = fopen($filename, 'a')) !== FALSE) {
                // 問題が無ければ書き込みを行う
                if (fwrite($fp, $log) === FALSE) {
                    print 'ファイル書き込み失敗: ' . $filename;
                }
                // ファイルを閉じる
                fclose($fp);
            }
        }
    }


    // ファイルが読み込み可能か確認
    if (is_readable($filename) === TRUE) {
        // 問題無ければテキストファイルを開く
        if (($fp = fopen($filename, 'r')) !== FALSE) {
            // テキストの内容を取得し$data[]に格納する
            while (($tmp = fgets($fp)) !== FALSE) {
                $data[] = $tmp;
            }
            // ファイルを閉じる
            fclose($fp);
        } else {
            $data[] = 'ファイルがありません';
        }
        // 更新順になるよう配列の内容を逆にする
        $view['reverse'] = array_reverse($data);
    }
    return $view;
}
?>
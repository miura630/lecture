<?php
// 検索キーワードの取得
    $target_kw = $_POST['target_kw'];  //検索キーワード

//  $enc_keyword=mb_convert_encoding($target_kw,"UTF-8","auto");
    $enc_keyword = $target_kw;
//  var_dump($enc_keyword);

    $req='http://ja.dbpedia.org/data/'.$enc_keyword.'.json';

    $first = 'http://ja.dbpedia.org/resource/'.$enc_keyword;

//  var_dump($req);

    //後で戻せるように設定を取得しておく
    $org_timeout = ini_get('default_socket_timeout');
    //3秒以上かかったらタイムアウトする設定に変更
    $timeout_second = 3;
    ini_set('default_socket_timeout', $timeout_second);

    $json = file_get_contents($req, true);
       if ($json == false) {

           $resw = "DBpediaから情報を取得できませんでした．";

       }else{
           $xml=json_decode($json, true);

//         var_dump( $xml[$first]['http://dbpedia.org/ontology/abstract'][0]['value'] );

           if (!$xml) {
           }

           $resw = $xml[$first]['http://dbpedia.org/ontology/abstract'][0]['value'];

//         var_dump($resw);
       }

    //設定を戻す
    ini_set('default_socket_timeout', $org_timeout);
?>

<html>
<head>
    <meta charset="utf-8">
    <title>検索結果</title>
    <style>
        body {
            margin: 0;
            background-color: #f8f8f8;
        }
        form {
            margin: 0;
        }
        .wrap {
            width: 850px;
            margin-left: auto;
            margin-right: auto;
            background-color: #ffffff;
        }
        #nav{
            margin: 24px 0 0;
            background-color: #ffffff;
            width: 100%;
            box-shadow: 0px 2px 10px -5px #a9a9a9;
            position: relative;
        }
        #nav #logout {
            width: 130px;
            position: absolute;
            top: 4px;
            right: 10px;
        } 
        #nav input[type="submit"] {
            background: none;
            display: block;
            text-align: center;
            border: 2px solid #e4555e;
            padding: 14px 30px;
            border-radius: 24px;
            transition: 0.25s;
            cursor: pointer;
        } 
        #nav .wrap ul {
            display: flex;
            margin: 0;
            padding: 0;
            list-style-type: none;
        }
        #nav .wrap ul li {
            width: 25%;
            text-align: center;
        }
        #nav .wrap ul li form {
            margin: 0;
        }
        #nav .wrap ul li a {
            display: block;
            height: 20px;
            margin: 0;
            padding: 20px;
            color: #000000;
            text-decoration: none;
        }
        #nav ul li a:hover {
            box-shadow: 2px 2px 10px #a9a9a9;
        }
        #nav input[type="submit"]:hover{
            background: #e4555e;
        }
        #main {
            width: 850px;
            margin-left: auto;
            margin-right: auto;
        }
        #main h1 {
            margin: 0;
            margin: 40px 0;
            padding: 10px 0;
            background-color: #e4555e;
            color: #ffffff;
            text-align: center;
        }
        #main-box {
            padding: 10% 10% 10%;
            background-color: #ffffff;
            box-shadow: 2px 2px 10px #a9a9a9;
        }
        #main-box p {
            margin: 0 0 10%;
        }
        #main input[type="submit"] { 
            background: none;
            display: block;
            margin: 40px auto;
            text-align: center;
            border: 2px solid #e4555e;
            padding: 14px 30px;
            border-radius: 24px;
            transition: 0.25s;
            cursor: pointer; 
        }
        #main input[type="submit"]:hover{
            background: #e4555e;
        }
    </style>
</head>
<body>
    <div id="nav">
        <form method="POST" action="./index.php?event=logout" id="logout">
			<input type="submit" value="ログアウト">
		</form>
    	<div class="wrap">
            <ul>
                <li>
                    <form method="POST" name="form1" action="index.php?event=showInput2Page">
                        <a href="javascript:form1.submit()">調査アンケート</a>
                    </form>
                </li>
                <li>
                    <form method="POST" name="form2" action="./showGraph.php">
                        <a href="javascript:form2.submit()">集計結果</a>
                    </form>
                </li>
                <li>
                    <form method="POST" name="form3" action="index.php?event=bbs">
                        <a href="javascript:form3.submit()">掲示板</a>
                    </form>
                </li>
                <li><a href="./views/input.html">検索</a></li>
            </ul>
        </div>
    </div>
    <div id="main">
        <h1>DBpedia 検索結果</h1>
        <div id="main-box">
            <p>【取得内容】</p>
            <?php
                echo $resw;
            ?>
        </div>
        <form method="POST" action="index.php?event=showInput2Page">
            <input type="submit" value="入力画面に戻る">
        </form>
    </div>
</body>
</html>

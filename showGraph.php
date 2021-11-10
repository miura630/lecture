<?php
  // (1) アンケートの全件読み出しと集計
  $Count1['yes'] = 0;
  $Count1['no'] = 0;
  $Count2['html/css'] = 0;
  $Count2['php'] = 0;
  $Count2['java'] = 0;
  $Count2['python'] = 0;
  $Count2['other'] = 0;
  $Count2['nothing'] = 0;

  $fh = fopen('db/myfile.csv', 'r');
  flock($fh, LOCK_SH);
  $line = fgets($fh);
  while (! empty($line)) {
    $answer = explode(',', trim($line));
    $q1 = $answer[0]; 
    $Count1[$q1]++;
    $q2 = $answer[1];
    $Count2[$q2]++;
    $line = fgets($fh);
  } 
  flock($fh, LOCK_UN);
  fclose($fh);

  ini_set('display_errors', "Off");
?>

<html>
<head>
  <title>アンケート集計結果グラフ</title>
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
      padding: 10% 6.5% 10%;
      background-color: #ffffff;
      box-shadow: 2px 2px 10px #a9a9a9;
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
            <form method="POST" name="form2" action ="index.php?event=showInput2Page">
                <a href="javascript:form2.submit()">調査アンケート</a>
            </form>
        </li>
        <li>
            <form action="./showGraph.php" name="form3" method="post">
                <a href="javascript:form3.submit()">集計結果</a>
            </form>
        </li>
        <li>
            <form action="./index.php?event=bbs" name="form4" method="post">
                <a href="javascript:form4.submit()">掲示板</a>
            </form>
        </li>
        <li><a href="./views/input.html">検索</a></li>
      </ul>
    </div>
  </div>
  <div id="main">
    <h1>集計結果</h1>
    <div id="main-box">
      <?php
      require_once 'graphs_php/graphs.inc.php';
      $legend = implode(',', array_keys($Count1));
      $values = implode(';', array_values($Count1));
      $graph = new BAR_GRAPH("hBar");
      $graph->values = $values;
      $graph->showValues = 1;
      $graph->labels = '今までにプログラミングを学習したことはありますか';
      $graph->legend = $legend;
      echo $graph->create();
      $legend = implode(',', array_keys($Count2));
      $values = implode(';', array_values($Count2));
      $graph = new BAR_GRAPH("hBar");
      $graph->values = $values;
      $graph->showValues = 1;
      $graph->labels = '得意なプログラミング言語を教えてください';
      $graph->legend = $legend;
      echo $graph->create();
      ?>
    </div>
    <form method="POST" action="index.php?event=showInput2Page">
	    <input type="submit" value="入力画面に戻る">
	  </form>
  </div>
</html>

<!doctype html>
<html lang="ja">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- 外部CSSファイルを参照する -->
<link rel="stylesheet" type="text/css" href="css/style.css">

<title>Rimomeito</title>


</head>
<body>
<header>
  <div class="wrapper">
<h1 class="font-weight-normal"><a href="index.php"><img src="images/logo.png" with="317" height="120" alt="rimomeito"></a></h1>

<ul id="nav">
<li><a href="index.php">top</a></li>
<li><a href="contact.html">お問い合わせ</a></li>
<li><a href="management_login.php">管理者ページ</a></li>
</ul>
</header>

<main>
<h2 class="center">仲間を募集する</h2>
<!-- 名前と書き込み用フォーム作成 送信ボタンを押すと再読み込み-->
<form class="center" action="index.php" method="post">
  <label for="my_name">お名前:</label>
<input type="text" id="my_name" name="my_name" maxlength="10" value="">
  <br>
  <label for="my_name">書き込み内容:</label><br>
<textarea name="memo" cols="50" rows="10" placeholder="自由にメモを残してください">
</textarea><br>
<button type="submit">登録する</button>
</form>
<div class="center" ><img src="images/logo2.png" alt="test class="img-right"></div>
<pre>
<?php
try {//PDOでデータベースにアクセス引数はデータベース名,ユーザー名,パスワード
    $db = new PDO('mysql:dbname=mydb;host=127.0.0.1;charset=utf8', 'root', 'root');

} catch (PDOException $e) {//接続時にエラーメッセージを出力
    echo 'DB接続エラー： ' . $e->getMessage();
}
//データベースにフォームの内容を書き込み
//データベース書き込みじにサニタライズする
$statement=$db->prepare('INSERT INTO memos SET name=?,memo=?,created_at=NOW()');//データの挿入
$statement->execute(array($_POST['my_name'],$_POST['memo']));
if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
} else {
    $page = 1;//page指定がなかったら1pageを指定
}
$start = 5 * ($page - 1);//5pageづつ表示させるため
$memos = $db->prepare('SELECT * FROM memos ORDER BY id DESC LIMIT ?,5');//データの検索
$memos->bindParam(1,$start,PDO::PARAM_INT);
$memos->execute();
?>
<h3 class="center">募集投稿一覧</h3>
<article>
<?php while ( $memo = $memos->fetch()): ?>
<p class="center"><a href="memo.php?id=<?php print($memo['id']); ?>"> <?php print(mb_substr($memo['memo'], 0, 50)); ?></a></p>
<p class="center"><?php print($memo['name']); ?><?php print("  "); ?>
<?php print($memo['created_at']); ?></p>
<hr class="center">
<?php endwhile; ?>

<?php if ($page >= 2): //2ページ以上の時にn-1ページを表示?>
  <p class="center"><a href="index.php?page=<?php print($page-1); ?>"><?php print($page-1); ?>ページ目へ</a></p>

<?php endif; ?>

<?php
$counts = $db->query('SELECT COUNT(*) as cnt FROM memos');
$count = $counts->fetch();
$max_page = ceil($count['cnt'] / 5);
if ($page < $max_page):
?>
  <p class="center"><a href="index.php?page=<?php print($page+1); ?>"><?php print($page+1); ?>ページ目へ</a></p>
<?php endif; ?>
</article>
</main>
</div>
</body>
</html>

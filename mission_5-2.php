<?php
//mission_4-1
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//mission_4-2
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "postedAt char(32),"
    . "password char(32)"
    . ");";
$stmt = $pdo->query($sql);
//編集
if(empty($_POST["edit_number"])==FALSE){
	//mission_4-6
	$edit_pass = $_POST["edit_pass"];
	$sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		if($row['id']==$_POST["edit_number"] && $edit_pass==$row['password'] ){
		$edit_num = $row['id'];
		$edit_name = $row['name'];
		$edit_comment = $row['comment'];
		}
	}
	
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
	<meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
	<title>ミッション</title>
</head>
<body>
<form method="POST" action="">
	<p>名前<br>      
	<input type="text" name="name" value="<?php if(isset($edit_name)){echo $edit_name;} else{echo "名前";} ?>">
	</p>
	<p>コメント<br>
	<input type="text" name="comment" value="<?php if(isset($edit_comment)){echo $edit_comment;} else{echo "コメント";} ?>">
	<input type="password" name="com_pass">
	</p>
	<p><input type="hidden" name="id" value="<?php if(isset($edit_num)){echo $edit_num;} ?>"></p>
	<p><input type="submit" value="送信"></p>
</form>
<form method="POST" action="">
	<p>削除対象番号<br>
	<input type="text" name="del_number">
	<input type="password" name="del_pass">
	</p>
	<p><input type="submit" value="削除"></p>
</form>
<form method="POST" action="">
	<p>編集対象番号<br>
	<input type="text" name="edit_number">
	<input type="password" name="edit_pass">
	</p>	      
	<p><input type="submit" value="編集"></p>
</form>
</body>
</html>
<?php
function display($pdo){
	//mission_4-6 入力したデータをselectで表示
	$sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['postedAt'].'<br>';
	}
	echo "<hr>";

}
//削除
if(empty($_POST["del_number"])==FALSE){
	$id = $_POST["del_number"];
	$del_pass = $_POST["del_pass"];
	$sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//パスワードが一致したとき
		if($id==$row['id'] && $del_pass==$row['password']){
			$sql = 'delete from tbtest where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
		}
	}
	display($pdo);

}

//コメントが送信されたら
elseif(empty($_POST['comment'])==FALSE && empty($_POST['com_pass'])==FALSE){ 
	//編集の番号が空かどうかを確認
	if(empty($_POST["id"])==FALSE){
		//mission_4-7 データをupdataによって編集する。
		$id = $_POST["id"]; //変更する投稿番号
		$name = $_POST['name'];
		$comment = $_POST['comment'];
		$postedAt = date("Y年m月d日 H:i:s");
		$password = $_POST['com_pass']; 
		$sql = 'update tbtest set name=:name,comment=:comment, postedAt=:postedAt, password=:password where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt->bindParam(':postedAt', $postedAt, PDO::PARAM_STR);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
	}
	//新規投稿
	else{
		$sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, postedAt, password) VALUES (:name, :comment, :postedAt, :password)");
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':postedAt', $postedAt, PDO::PARAM_STR);
		$sql -> bindParam(':password', $password, PDO::PARAM_STR);
		$name = $_POST['name'];
		$comment = $_POST['comment'];
		$postedAt = date("Y年m月d日 H:i:s");
		$password = $_POST['com_pass'];
		$sql -> execute();
	}

	display($pdo);
}

else{
	display($pdo);
}
?>

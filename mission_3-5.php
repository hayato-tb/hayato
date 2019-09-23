<?php
$filename='mission_3-5.txt';
$pause = "<>";
//編集
if(empty($_POST["edit_number"])==FALSE){
	$rist = file($filename);
	$fp = fopen($filename,'w');
	foreach ($rist as $value){
		$data0 = explode($pause,$value);
		//編集対象番号と比較
		if($data0[0] != $_POST["edit_number"]){
			fwrite($fp, $value);
		}
		else{	
			//パスワードが一致したときのみ編集
			if($_POST["edit_pass"]!=$data0[4]){
				//一致してないので編集しない
				fwrite($fp, $value);
				
			}
			else{	
				
				fwrite($fp, $value);
				$data0 = explode($pause,$value);
				$edit_num = $data0[0];
				$edit_name = $data0[1];
				$edit_comment = $data0[2];
				$edit_display =  "名前：".$edit_name." , "."コメント：".$edit_comment."を編集します。"."<br>";
			}
		}
	}
	fclose($fp);
}

?>
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
	<title>ミッション</title>
</head>
<body>
<form method="POST" action="mission_3-5.php">
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

<form method="POST" action="mission_3-5.php">
	<p>削除対象番号<br>
	<input type="text" name="del_number">
	<input type="password" name="del_pass">
	</p>
	<p><input type="submit" value="削除"></p>
</form>

<form method="POST" action="mission_3-5.php">
	<p>編集対象番号<br>
	<input type="text" name="edit_number">
	<input type="password" name="edit_pass">
	</p>	      
	<p><input type="submit" value="編集"></p>
</form>
<?php
function display($filename){
	$rist = file($filename);
	foreach ($rist as $value){
		$data1 = explode("<>",$value);
		unset($data1[4]);
		$data2 = implode($data1);
		echo $data2."<br>";
	}
}
if (isset($edit_display)){
	echo $edit_display;
}
//削除
if(empty($_POST["del_number"])==FALSE){ 
	//パスワードが一致したときのみ
	$rist = file($filename);
	$fp = fopen($filename,'w');
	foreach ($rist as $value){
		$data0 = explode($pause,$value);
		//削除番号ではないとき
		if($data0[0] != $_POST["del_number"]){
			fwrite($fp, $value);
		}
		//削除する番号の時
		else{
			//パスワードが一致したときのみ削除
			if($_POST["del_pass"]!=$data0[4]){
				fwrite($fp, $value);
			}
		}
	}
	fclose($fp);
	display($filename);
	
}

//コメントが送信されたら
elseif(empty($_POST['comment'])==FALSE && empty($_POST['com_pass'])==FALSE){
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$postedAt = date("Y年m月d日 H:i:s");
	//編集の番号が空かどうかを確認
	if(empty($_POST["id"])==FALSE){
		$rist = file($filename);
		$fp = fopen($filename,'w');
		$newData = $_POST["id"].$pause.$name.$pause.$comment.$pause.$postedAt.$pause.$_POST["com_pass"].$pause;
		//各行の投稿番号を比較
		foreach ($rist as $value){
			//投稿番号と一致したら送信された値と差し替え
			$data0 = explode($pause,$value);
			if($data0[0] != $_POST["id"]){
				fwrite($fp, $value);
			}
			else{
				fwrite($fp, $newData."\n");
			}
		}
	}
	//空の時は新規投稿
	else{
		$fp = fopen($filename, "a+");
	  	if(empty(fgets($fp))){
			$num = 1;
	  	}
		else{
			$rist = file($filename);
		    	$endrist = explode($pause, end($rist));
		    	$num = $endrist[0] + 1;
		}
		$newData = $num.$pause.$name.$pause.$comment.$pause.$postedAt.$pause.$_POST["com_pass"].$pause;
		fwrite($fp, $newData."\n");
	}
	fclose($fp);
	display($filename);
	
}
else{
	$fp = fopen($filename,'a'); 
	display($filename);
	fclose($fp);
}
?>
</body>
</html>
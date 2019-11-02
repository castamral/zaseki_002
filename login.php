<?php

//クラスの読み込み
function autoload($className){
	require './class/'.$className.'.php';
}
spl_autoload_register('autoload');
  
session_start();

//POSTのvalidate
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  echo '入力された値が不正です。';
  return false;
}
//DB内でPOSTされたメールアドレスを検索
try {
  $pdo = ConnDB::getInstance();
  $stmt = $pdo->prepare('select * from T_99999_UserData where email = ?');
  $stmt->execute([$_POST['email']]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}
//emailがDB内に存在しているか確認
if (!isset($row['email'])) {
  echo 'メールアドレスまたはパスワードが間違っています。[0001]';
  return false;
}
//パスワード確認後sessionにメールアドレスを渡す
if (password_verify($_POST['password'], $row['password'])) {
  session_regenerate_id(true); //session_idを新しく生成し、置き換える
  $_SESSION['EMAIL'] = $row['email'];
  $_SESSION['DISPNAME'] = $row['dispname'];
  $_SESSION['ADMIN'] = $row['admin'];
  http_response_code( 301 ) ;
  header( "Location: ./main.php" ) ;
  echo 'ログインしました';
  exit ;
} else {
  echo 'メールアドレス又はパスワードが間違っています。[0002]';
  return false;
}

?>

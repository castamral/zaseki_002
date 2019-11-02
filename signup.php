<?php

//クラスの読み込み
function autoload($className){
	require './class/'.$className.'.php';
}
spl_autoload_register('autoload');

//データベースへ接続
try {
  $pdo = ConnDB::getInstance();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}

$vrslt=false;
//POSTのValidate。
if (!$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  echo 'mail：入力された値が不正または未入力です。<br />';
  $vrslt=true;
}
if (!$dispname = (string)filter_input(INPUT_POST, 'dispname')) {
  echo '名前：入力された値が不正または未入力です。<br />';
  $vrslt=true;
}
//パスワードの正規表現
if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['password'])) {
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
} else {
  echo 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。<br />';
  $vrslt=true;
}
//エラーがあれば終了
if ($vrslt) {
  return false;
}

//登録処理
try {
  $stmt = $pdo->prepare("insert into T_99999_UserData(email, password, grp_no, dispname) value(?, ?, ?, ?)");
  $stmt->execute([$email, $password, '99999', $dispname]);
  echo '登録完了';
} catch (\Exception $e) {
  echo '登録済みのメールアドレスです。';
}

/*
//DB内のメールアドレスを取得
$stmt = $pdo->prepare("select email from userDeta where email = ?");
$stmt->execute([$email]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//DB内のメールアドレスと重複していない場合、登録する。
if (!isset($row['email'])) {
  $stmt = $pdo->prepare("insert into userDeta(email, password) value(?, ?)");
  $stmt->execute([$email, $password]);
  echo "登録完了";
} else {
  echo '既に登録されたメールアドレスです。';
  return false;
}
*/
?>

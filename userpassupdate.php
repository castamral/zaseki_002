<?php
  header('Content-type: text/plain; charset= UTF-8');

	$CONST_GRP_NO = "99999";

	session_start();

  //クラスの読み込み
  function autoload($className){
    require './class/'.$className.'.php';
  }
  spl_autoload_register('autoload');

  try {
    $pdo = ConnDB::getInstance();
    $stmt = $pdo->prepare('select * from T_99999_UserData where ID = ?');
    $stmt->execute([$_POST['u_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
  }
  //IDがDB内に存在しているか確認
  if (!isset($row['id'])) {
    echo 'ログインしていないかタイムアウトしました。再度ログインしてください。';
    return;
  } else {
    $now_password = $row['password'];
  }
  //POSTのValidate。
  //パスワードの入力チェック
  if (!$u_password1 = (string)filter_input(INPUT_POST, 'u_password1')) {
    $errmsg = $errmsg.'現在のパスワード：入力された値が不正または未入力です。'."\n";
  }
  if (!$u_password2 = (string)filter_input(INPUT_POST, 'u_password2')) {
    $errmsg = $errmsg.'新しいパスワード：入力された値が不正または未入力です。'."\n";
  }
  if (!$u_password3 = (string)filter_input(INPUT_POST, 'u_password3')) {
    $errmsg = $errmsg.'新しいパスワード(確認用)：入力された値が不正または未入力です。'."\n";
  }
  if ($errmsg != "") {
    echo $errmsg;
    return;
  }
  $u_password1 = password_hash($_POST['u_password1'], PASSWORD_DEFAULT);
  //現在のパスワードの一致チェック
  if (password_verify($u_password1, $now_password)) {
    $errmsg = $errmsg.'現在のパスワード：現在のパスワードが一致しませんでした。'."\n";
  }
  //新しいパスワードと確認用の一致チェック
  if (!$u_password2 == $u_password3) {
    $errmsg = $errmsg.'新しいパスワード：確認用パスワードと一致しませんでした。'."\n";
  }
  if ($errmsg != "") {
    echo $errmsg;
    return;
  }
  //パスワードの正規表現チェック
  if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $u_password2)) {
    $errmsg = $errmsg.'新しいパスワード：パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。'."\n";
  }
  if ($errmsg != "") {
    echo $errmsg;
    return;
  }
  $u_password2 = password_hash($_POST['u_password2'], PASSWORD_DEFAULT);
  
  //データベースへ接続
  try {
    $pdo = ConnDB::getInstance();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
  }

  if(isset($_POST['u_id'])){
    $u_id = $_POST['u_id'];
    $s_tablename="T_".$CONST_GRP_NO."_UserData";
    try {
      $stmt = $pdo->prepare("update T_99999_UserData set password=? where id=? ");
      $stmt->execute([$u_password2, $u_id]);
      echo '更新しました。';
    } catch (\Exception $e) {
      echo '更新できませんでした';
      throw $e;
    }
  }

?>

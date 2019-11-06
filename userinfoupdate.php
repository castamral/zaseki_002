<?php
  header('Content-type: text/plain; charset= UTF-8');

	$CONST_GRP_NO = "99999";

	session_start();

  //クラスの読み込み
  function autoload($className){
    require './class/'.$className.'.php';
  }
  spl_autoload_register('autoload');

  //POSTのValidate。
  $errmsg = "";
  if (!$u_email = filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)) {
    $errmsg = $errmsg.'mail：入力された値が不正または未入力です。'."\n";
  }
  if (!$u_dispname = (string)filter_input(INPUT_POST, 'u_dispname')) {
    $errmsg = $errmsg.'表示名：入力された値が不正または未入力です。'."\n";
  }
  if ($errmsg != "") {
    echo $errmsg;
    return;
  }

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
      $stmt = $pdo->prepare("update T_99999_UserData set email=?,dispname=? where id=? ");
      $stmt->execute([$u_email, $u_dispname, $u_id]);
      $_SESSION['EMAIL'] = $u_email;
      $_SESSION['DISPNAME'] = $u_dispname;
      echo '更新しました。';
    } catch (\Exception $e) {
      echo '更新できませんでした';
      throw $e;
    }
  }

?>

<?php
session_start();
//セッションクッキー削除
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000
  );
}

//セッション変数のクリア
$_SESSION = array();
//セッションクリア
@session_destroy();

http_response_code( 301 ) ;
header( "Location: ./entrance.php" ) ;

if (isset($_SESSION["EMAIL"])) {
  echo 'ログアウトしました。<br />';
} else {
  echo 'セッションがタイムアウトしました。<br />';
}
exit ;

?>

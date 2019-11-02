<?php
  header('Content-type: text/plain; charset= UTF-8');

	$CONST_GRP_NO = "99999";

  //クラスの読み込み
  function autoload($className){
    require './class/'.$className.'.php';
  }
  spl_autoload_register('autoload');

  echo "処理開始";
  if(isset($_POST['s_key'])){
    $s_key=$_POST['s_key'];
    $s_tablename=$_POST['s_tablename'];
    switch ($s_tablename) {
      case "T_".$CONST_GRP_NO."_M_COMMON":
        $T_99999_M_COMMON = new $s_tablename;
        $s_key = htmlspecialchars($s_key);
        $ret = $T_99999_M_COMMON->delete($s_key);
        break;
      case "T_".$CONST_GRP_NO."_M_FLOOR":
        $T_99999_M_FLOOR = new $s_tablename;
        $s_key = htmlspecialchars($s_key);
        $ret = $T_99999_M_FLOOR->delete($s_key);
        break;
      case "T_".$CONST_GRP_NO."_M_STATUS":
        $T_99999_M_STATUS = new $s_tablename;
        $s_key = htmlspecialchars($s_key);
        $ret = $T_99999_M_STATUS->delete($s_key);
        break;
      case "T_".$CONST_GRP_NO."_M_ICON":
        $T_99999_M_ICON = new $s_tablename;
        $s_key = htmlspecialchars($s_key);
        $ret = $T_99999_M_ICON->delete($s_key);
        break;
    }
    echo "処理終了".$ret;
  }

?>

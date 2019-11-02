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
        if ($s_key=="") {
          $s_key = $T_99999_M_COMMON->getNewID();
        }
        $val[0] = htmlspecialchars($s_key);
        $val[1] = htmlspecialchars($_POST['s_f002']);
        $val[2] = htmlspecialchars($_POST['s_f003']);
        $val[3] = htmlspecialchars($_POST['s_f004']);
        $val[4] = htmlspecialchars($_POST['s_f005']);
        $ret = $T_99999_M_COMMON->update(...$val);
        break;
      case "T_".$CONST_GRP_NO."_M_FLOOR":
        $T_99999_M_FLOOR = new $s_tablename;
        if ($s_key=="") {
          $s_key = $T_99999_M_FLOOR->getNewID();
        }
        $sortno = $_POST['s_f002'];
        if ($sortno=="") {
          $sortno = $T_99999_M_FLOOR->getNewSortNo();
        }
        $val[0] = htmlspecialchars($s_key);
        $val[1] = htmlspecialchars($sortno);
        $val[2] = htmlspecialchars($_POST['s_f003']);
        $val[3] = htmlspecialchars($_POST['s_f004']);
        $val[4] = htmlspecialchars($_POST['s_f005']);
        $ret = $T_99999_M_FLOOR->update(...$val);
        break;
      case "T_".$CONST_GRP_NO."_M_STATUS":
        $T_99999_M_STATUS = new $s_tablename;
        if ($s_key=="") {
          $s_key = $T_99999_M_STATUS->getNewID();
        }
        $sortno = $_POST['s_f003'];
        if ($sortno=="") {
          $sortno = $T_99999_M_STATUS->getNewSortNo();
        }
        $val[0] = htmlspecialchars($s_key);
        $val[1] = htmlspecialchars($_POST['s_f002']);
        $val[2] = htmlspecialchars($sortno);
        $val[3] = htmlspecialchars($_POST['s_f004']);
        $val[4] = htmlspecialchars($_POST['s_f005']);
        $ret = $T_99999_M_STATUS->update(...$val);
        break;
      case "T_".$CONST_GRP_NO."_M_ICON":
        $T_99999_M_ICON = new $s_tablename;
        if ($s_key=="") {
          $s_key = $T_99999_M_ICON->getNewID();
        }
        $val[0] = htmlspecialchars($s_key);
        $val[1] = htmlspecialchars($_POST['s_f002']);
        $val[2] = htmlspecialchars($_POST['s_f003']);
        $val[3] = htmlspecialchars($_POST['s_f004']);
        $val[4] = htmlspecialchars($_POST['s_f005']);
        $ret = $T_99999_M_ICON->update(...$val);
        break;
    }
    echo "処理終了".$ret;
  }

?>

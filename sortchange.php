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
    $s_sort=$_POST['s_sort'];
    $s_tablename=$_POST['s_tablename'];
    switch ($s_tablename) {
      case "T_".$CONST_GRP_NO."_M_FLOOR":
        $T_99999_M_FLOOR = new $s_tablename;
        $stmt = $T_99999_M_FLOOR->getData($s_key);
        while($row = $stmt->fetch(PDO::FETCH_NUM)) {
          $val[0] = htmlspecialchars($s_key);
          $val[1] = $s_sort;
          $val[2] = $row[2];
          $val[3] = $row[3];
          $val[4] = $row[4];
        }
        $ret = $T_99999_M_FLOOR->update(...$val);
        break;
      case "T_".$CONST_GRP_NO."_M_STATUS":
        $T_99999_M_STATUS = new $s_tablename;
        $stmt = $T_99999_M_STATUS->getData($s_key);
        while($row = $stmt->fetch(PDO::FETCH_NUM)) {
          $val[0] = htmlspecialchars($s_key);
          $val[1] = $row[1];
          $val[2] = $s_sort;
          $val[3] = $row[3];
          $val[4] = $row[4];
        }
        $ret = $T_99999_M_STATUS->update(...$val);
        break;
      case "T_".$CONST_GRP_NO."_M_ICON":
        $T_99999_M_ICON = new $s_tablename;
        $stmt = $T_99999_M_ICON->getData($s_key);
        while($row = $stmt->fetch(PDO::FETCH_NUM)) {
          $val[0] = htmlspecialchars($s_key);
          $val[1] = $row[1];
          $val[2] = $s_sort;
          $val[3] = $row[3];
          $val[4] = $row[4];
        }
        $ret = $T_99999_M_ICON->update(...$val);
        break;
    }
    echo "処理終了".$ret;
  }

?>

<?php
  header('Content-type: text/plain; charset= UTF-8');
  //クラスの読み込み
  function autoload($className){
    require './class/'.$className.'.php';
  }
  spl_autoload_register('autoload');

  echo "処理開始";
  if(isset($_POST['s_key']) && isset($_POST['s_status'])){
    $s_key=$_POST['s_key'];
    $s_status=$_POST['s_status'];
    $T_99999_SEAT = new T_99999_SEAT();
    $stmt = $T_99999_SEAT->getData($s_key);
    while($row = $stmt->fetch(PDO::FETCH_NUM)) {
      $val[0] = htmlspecialchars($s_key);
      $val[1] = $row[1];
      $val[2] = $row[2];
      $val[3] = $row[3];
      $val[4] = $row[4];
      $val[5] = $row[5];
      $val[6] = $row[6];
      $val[7] = $row[7];
      $val[8] = $row[8];
      $val[9] = $row[9];
      $val[10]= $s_status;
      $val[11] = $row[11];
      $val[12]= date("Y/m/d H:i:s");
    }
    $ret = $T_99999_SEAT->update(...$val);
    //echo $ret.":".$val[0].":".$val[1].":".$val[2].":".$val[3].":".$val[4].":".$val[5].":".$val[6].":".$val[7].":".$val[8].":".$val[9].":".$val[10].":".$val[11];
    echo "処理終了";
  }

?>

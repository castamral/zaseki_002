<?php
  header('Content-type: text/plain; charset= UTF-8');
  //クラスの読み込み
  function autoload($className){
    require './class/'.$className.'.php';
  }
  spl_autoload_register('autoload');

  echo "処理開始";
  if(isset($_POST['s_keys'])){
    $s_keys = htmlspecialchars($_POST['s_keys']);

    $T_99999_SEAT = new T_99999_SEAT();
    $arr = explode(',', $s_keys);
    foreach ($arr as $value)
    {
      if ($value != "") {
        $ret = $T_99999_SEAT->delete($value);
      }
    }
    echo "処理終了:".$ret;
  }

?>

<?php
  header('Content-type: text/plain; charset= UTF-8');
  //クラスの読み込み
  function autoload($className){
    require './class/'.$className.'.php';
  }
  spl_autoload_register('autoload');

  echo "処理開始";
  if(isset($_POST['s_key'])){
    $s_key       =$_POST['s_key'];
    $s_userid    =$_POST['s_userid'];
    $s_name      =$_POST['s_name'];
    $s_naisen    =$_POST['s_naisen'];
    $s_tokki     =$_POST['s_tokki'];
    $s_left      =$_POST['s_left'];
    $s_top       =$_POST['s_top'];
    $s_pic       =$_POST['s_pic'];
    $s_type      =$_POST['s_type'];
    $s_status_cd =$_POST['s_status_cd'];
    $s_rotate    =$_POST['s_rotate'];
    $f_key       =$_POST['f_key'];

    $T_99999_SEAT = new T_99999_SEAT();
    if ($s_key=="") {
      $ret = $T_99999_SEAT->getNewID();
      $s_key = $ret;
    }

    $val[0] = htmlspecialchars($s_key);
    $val[1] = htmlspecialchars($s_userid);
    $val[2] = htmlspecialchars($s_name);
    $val[3] = htmlspecialchars($s_naisen);
    $val[4] = htmlspecialchars($s_tokki);
    $val[5] = htmlspecialchars($s_left);
    $val[6] = htmlspecialchars($s_top);
    $val[7] = htmlspecialchars($f_key);
    $val[8] = htmlspecialchars($s_pic);
    $val[9] = htmlspecialchars($s_type);
    $val[10]= htmlspecialchars($s_status_cd);
    $val[11]= htmlspecialchars($s_rotate);
    $val[12]= date("Y/m/d H:i:s");
    $ret = $T_99999_SEAT->update(...$val);
    //echo $ret.":".$val[0].":".$val[1].":".$val[2].":".$val[3].":".$val[4].":".$val[5].":".$val[6].":".$val[7].":".$val[8].":".$val[9].":".$val[10].":".$val[11];
    echo "処理終了".$ret;
  }

?>

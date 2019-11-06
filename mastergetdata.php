<?php
  header('Content-type: text/plain; charset= UTF-8');

	$CONST_GRP_NO = "99999";

  //クラスの読み込み
  function autoload($className){
    require './class/'.$className.'.php';
  }
  spl_autoload_register('autoload');

  if(isset($_POST['s_key'])){
    $s_key=$_POST['s_key'];
    $s_tablename=$_POST['s_tablename'];
    switch ($s_tablename) {
      case "T_".$CONST_GRP_NO."_M_COMMON":
        $T_99999_M_COMMON = new $s_tablename;
        $stmt = $T_99999_M_COMMON->getdata(htmlspecialchars($s_key));
				while($row = $stmt->fetch(PDO::FETCH_NUM)) {
					$f001 = htmlspecialchars($row[0]);
					$f002 = $row[1];
					$f003 = $row[2];
					$f004 = $row[3];
					$f005 = $row[4];
        }
        $ret_json = array('FIELD001'=>$f001, 'FIELD002'=>$f002, 'FIELD003'=>$f003, 'FIELD004'=>$f004, 'FIELD005'=>$f005);
        echo json_encode($ret_json, JSON_UNESCAPED_UNICODE);
        break;
      case "T_".$CONST_GRP_NO."_M_FLOOR":
        $T_99999_M_FLOOR = new $s_tablename;
        $stmt = $T_99999_M_FLOOR->getdata(htmlspecialchars($s_key));
				while($row = $stmt->fetch(PDO::FETCH_NUM)) {
					$f001 = htmlspecialchars($row[0]);
					$f002 = $row[1];
					$f003 = $row[2];
					$f004 = $row[3];
					$f005 = $row[4];
        }
        $ret_json = array('FIELD001'=>$f001, 'FIELD002'=>$f002, 'FIELD003'=>$f003, 'FIELD004'=>$f004, 'FIELD005'=>$f005);
        echo json_encode($ret_json, JSON_UNESCAPED_UNICODE);
        break;
      case "T_".$CONST_GRP_NO."_M_STATUS":
        $T_99999_M_STATUS = new $s_tablename;
        $stmt = $T_99999_M_STATUS->getdata(htmlspecialchars($s_key));
				while($row = $stmt->fetch(PDO::FETCH_NUM)) {
					$f001 = htmlspecialchars($row[0]);
					$f002 = $row[1];
					$f003 = $row[2];
					$f004 = $row[3];
					$f005 = $row[4];
        }
        $ret_json = array('FIELD001'=>$f001, 'FIELD002'=>$f002, 'FIELD003'=>$f003, 'FIELD004'=>$f004, 'FIELD005'=>$f005);
        echo json_encode($ret_json, JSON_UNESCAPED_UNICODE);
        break;
      case "T_".$CONST_GRP_NO."_M_ICON":
        $T_99999_M_ICON = new $s_tablename;
        $ret = $T_99999_M_ICON->getdata(htmlspecialchars($s_key));
        break;
    }
  }

?>

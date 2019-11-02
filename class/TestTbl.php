<?php
	class TestTbl {
		public function __construct() {
			
		}

		public function getList() {
			$pdo = ConnDB::getInstance();
			$stmt = $pdo->query('SELECT * FROM '.get_class($this).' ORDER BY 1 ASC');
			return $stmt;
		}

		public function updData($f001,$f002,$f003,$f004,$f005) {
			$pdo = ConnDB::getInstance();
			$stmt = $pdo->prepare('INSERT INTO TestTbl(FIELD001,FIELD002,FIELD003,FIELD004,FIELD005) VALUES (:f001,:f002,:f003,:f004,:f005) 
								   ON DUPLICATE KEY UPDATE FIELD002=VALUES(FIELD002),FIELD003=VALUES(FIELD003),FIELD004=VALUES(FIELD004),FIELD005=VALUES(FIELD005)');
			$stmt->bindParam(':f001', $f001, PDO::PARAM_STR);
			$stmt->bindParam(':f002', $f002, PDO::PARAM_STR);
			$stmt->bindParam(':f003', $f003, PDO::PARAM_STR);
			$stmt->bindParam(':f004', $f004, PDO::PARAM_STR);
			$stmt->bindParam(':f005', $f005, PDO::PARAM_STR);
			$stmt->execute();
		}

		//更新処理。キーが被っていたらUPDATEします。
		public function updData2(...$fields) {
			$pdo = ConnDB::getInstance();
			$sql = 'INSERT INTO '.get_class($this).'(';
			$cnt=1;
			foreach ($fields as $f) {
				$str='FIELD'.str_pad($cnt, 3, '0', STR_PAD_LEFT);
				if($cnt==1) {	$sql = $sql.$str;	} else {	$sql = $sql.','.$str;	}
				$cnt++;
			}
			$sql = $sql.') VALUES (';
			$cnt=1;
			foreach ($fields as $f) {
				$str=':f'.str_pad($cnt, 3, '0', STR_PAD_LEFT);
				if($cnt==1) {	$sql = $sql.$str;	} else {	$sql = $sql.','.$str;	}
				$cnt++;
			}
			$sql = $sql.') ';
			$sql = $sql.' ON DUPLICATE KEY UPDATE ';
			$cnt=1;
			foreach ($fields as $f) {
				$str='FIELD'.str_pad($cnt, 3, '0', STR_PAD_LEFT).'=VALUES('.'FIELD'.str_pad($cnt, 3, '0', STR_PAD_LEFT).')';
				if($cnt==1) {	$sql = $sql.$str;	} else {	$sql = $sql.','.$str;	}
				$cnt++;
			}
			$stmt = $pdo->prepare($sql);
			$cnt=1;
			foreach ($fields as $f) {
				$fldnm=str_pad($cnt, 3, '0', STR_PAD_LEFT);
				$stmt->bindParam(':f'.$fldnm, $fields[$cnt-1], PDO::PARAM_STR);
				$cnt++;
			}
			$stmt->execute();
		}
	}
?>

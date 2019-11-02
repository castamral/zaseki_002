<?php
	class T_99999_M_STATUS {
		public function __construct() {
			
		}

		public function getList() {
			$pdo = ConnDB::getInstance();
			$stmt = $pdo->query('SELECT * FROM '.get_class($this).' ORDER BY FIELD002 ASC, cast(FIELD003 as signed) ASC');
			return $stmt;
		}

		public function getData($s_key) {
			$pdo = ConnDB::getInstance();
			$stmt = $pdo->query('SELECT * FROM '.get_class($this).' where FIELD001='.htmlspecialchars($s_key).' ORDER BY 1 ASC');
			return $stmt;
		}

		public function getNewID() {
			$pdo = ConnDB::getInstance();
			$stmt = $pdo->query('SELECT max(FIELD001) + 1 AS FIELD001 FROM '.get_class($this));
			$f001 = "";
			while($row = $stmt->fetch(PDO::FETCH_NUM)) {
				$f001 = htmlspecialchars($row[0]);
			}
			return $f001;
		}

		public function getNewSortNo() {
			$pdo = ConnDB::getInstance();
			$stmt = $pdo->query('SELECT max(cast(FIELD003 as signed)) + 1 AS FIELD003 FROM '.get_class($this));
			$f003 = "";
			while($row = $stmt->fetch(PDO::FETCH_NUM)) {
				$f003 = htmlspecialchars($row[0]);
			}
			return $f003;
		}

		//更新処理。キーが被っていたらUPDATEします。
		public function update(...$fields) {

			try {
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

				if ($stmt->execute()) {
					return 'OK';
				} else {
					return 'NG';
				}

			} catch(PDOException $e) {
				throw $e;
			}
		}
		//削除処理
		public function delete($key) {

			try {
				$pdo = ConnDB::getInstance();
				$sql = 'DELETE FROM '.get_class($this);
				$sql = $sql.' WHERE FIELD001 = :f001' ;
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':f001', $key, PDO::PARAM_INT);
				if ($stmt->execute()) {
					return 'OK';
				} else {
					return 'NG';
				}

			} catch(PDOException $e) {
				throw $e;
			}
		}
	}
?>

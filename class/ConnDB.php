<?php
	class ConnDB {
		private static $hostname='mysql1.php.xdomain.ne.jp';
		private static $dbname='castamral_presea';
		private static $dbuser='castamral_presea';
		private static $dbpass='Tt578220';
		private static $result;
		private static $pdo;

		public function __construct() {
			
		}

		public static function getInstance()
		{
			if (!isset(self::$pdo)) {
				try {
					self::$pdo = new PDO("mysql:host=".self::$hostname.";dbname=".self::$dbname.";", self::$dbuser, self::$dbpass);
					//$st = $pdo->prepare("INSERT INTO テーブル名 VALUES(?, ?, ?)");
					//$st->execute(array("001", "佐藤", "男性"));
					self::setResult('接続成功');
				}catch(PDOException $e){
					//エラー出力
					//var_dump($e->getMessage());    //エラーの詳細を調べる場合、コメント解除
					self::setResult('接続失敗');
				}
			}
			return self::$pdo;
		}
	
		public static function setResult($str) {
			self::$result = $str;
		}
		public static function getResult() {
			return self::$result;
		}
	}
?>

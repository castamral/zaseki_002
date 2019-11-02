<?php
	class User {
		private $name;

		public function __construct() {
			$this->setName('佐藤');
		}

		public function setName($str) {
			$this->name = $str;
		}
		public function getName() {
			return $this->name;
		}
	}
?>

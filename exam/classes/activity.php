<?php
	class Activity {
		private static $_table = 'activity';
		private $_data;

		public static function add($action = null, $staff, $value, $job = 0) {
			if($action) {
				DB::getInstance()->insert(self::$_table,array('action_id' => $action,'initiated' => $staff, 'person_affected' => $value, 'job_id' => $job));
			}
		}

		public static function get() {
			$sql = "SELECT * FROM ". self::$_table ." ORDER BY date DESC LIMIT 3";
			$result = DB::getInstance()->query($sql)->results();
			return $result;
		}
	}
?>
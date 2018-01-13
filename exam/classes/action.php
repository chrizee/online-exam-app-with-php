<?php
	class Action {
		private static $_table = 'actions';

		public static function get($id) {
			$sql = "SELECT description FROM ". self::$_table ." WHERE id = {$id}";
			$description = DB::getInstance()->query($sql)->first()->description;
			return $description;
		}
	}
?>
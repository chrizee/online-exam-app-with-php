<?php
class Info {
	private static $_table = 'init';

	public static function get() {
		if(!$info = DB::getInstance()->get(self::$_table, array('1', '=', '1'))) {
			throw new Exception("Error getting init info");
		}
		return $info->first();
	}

	public static function put($fields) {
		if(!DB::getInstance()->insert(self::$_table, $fields)) {
			throw new Exception("Error adding init info");
		}
	}
}

?>
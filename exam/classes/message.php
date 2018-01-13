<?php
	if (!class_exists('Message')) {
		class Message {
			private static $_table = 'message';

			public static function put($fields) {
				if(!DB::getInstance()->insert(self::$_table, $fields)) {
					throw new Exception("Error adding message");
				}
			}

			public static function get($where, $fields = '*') {
				if(!$message = DB::getInstance()->get(self::$_table, $where, $fields)) {
					throw new Exception("Error adding message");
				}
				return $message;
			}

			public static function update($fields, $id) {
				if(!DB::getInstance()->update(self::$_table, $id, $fields)) {
					throw new Exception("Error updating message");
				}	
			}
		}
	}
?>
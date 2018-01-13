<?php
class Hash {
	private static $_cipher = "aes-128-gcm",
					$_key = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?",
					$_iv = "vu9TWQiwZacr";
	public static function make($string, $salt = '') {
		return hash('sha256', $string.$salt);
	}

	public static function salt($length) {
		//return mcrypt_create_iv($length);
		return openssl_random_pseudo_bytes($length);
	}

	public static function unique() {
		return self::make(uniqid());
	}

	public static function openssl_salt() {
		if(in_array(self::$_cipher, openssl_get_cipher_methods())) {
			$ivLength = openssl_cipher_iv_length(self::$_cipher);
			return openssl_random_pseudo_bytes($ivLength);
		}
	}

	public static function encrypt($string) {
		return openssl_encrypt($string, self::$_cipher, self::$_key, $options = 0, self::$_iv);	
	}

	public static function decrypt($string) {
		return openssl_decrypt($string, self::$_cipher, self::$_key, $options = 0, self::$_iv);	
	}

	public static function random_password( $length = 8 ) {
		$password = '';
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
	    $int = strlen($chars) - 1;
	   	for ($i = 0; $i < $length; $i++) {
			$password .= $chars{mt_rand(0, $int)};
		}
	    return $password;
	}
}

?>
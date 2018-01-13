<?php
	Class Route {
		private $_uri = array();

		public function add($uri) {
			$this->_uri[] = $uri;
		}

		public function submit() {
			echo $uriGet = isset($_GET['uri']) ? $_GET['uri'] : '/';
			echo '<br>';
			foreach ($this->_uri as $key => $value) {
				echo $value."<br>";
				if(preg_match("#^$value$#", $uriGet)) {
					echo 'match';
				}
			}
		}
	}
?>
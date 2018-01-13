<?php
	class Admin {
		private $_db,
				$_members = array(),
				$_ids = array();
		
		public $noOfMembers = 0;

		public function __construct() {
			$this->_db = DB::getInstance();
		}

		public function getMembers() {
			$sql = "SELECT * FROM users";
			$this->_members = $this->_db->query($sql)->results();
			$this->noOfMembers = count($this->_members);
			return $this->_members;	
		}
		public function getIds() {
			for($i = 0; $i < $this->noOfMembers; $i++) {
				$this->_ids[] = $this->_members[$i]->id; 
			}
			return $this->_ids;
		}	
	}
?>
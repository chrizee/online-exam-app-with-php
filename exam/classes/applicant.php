<?php 
	class Applicant extends User
	{
		protected $_table;
		public $total = 0;

		public function __construct($user = null, $table) {
			$this->_db = DB::getInstance();
			//get the name of the session for the applicant
			$this->_sessionName = Config::get('session/session_name_applicant');
			$this->_table = $table;

			if(!$user) {
				if(Session::exists($this->_sessionName)) {
					$user = Session::get($this->_sessionName);

					if($this->find($user)) {
						$this->_isLoggedIn = true;
					} else {
						//process logout.
					}
				}
			} else {
				$this->find($user);
			}
		}

		public function find($user = null) {
			if($user) {
				$field = (is_numeric($user)) ? 'id' : 'login';
				$data = $this->_db->get($this->_table, array($field, '=', $user));	//holds the result returned fron the database when loggin in

				if($data && $data->count()) {
					$this->_data = $data->first();
					return true;
				}
			} 
			return false;	
		}

		public function login($username = null, $password = null, $remember = false) {
		
			//checks if no data has been passed to login and if an user data is available
			//user data is available if exists() returns true ie _data has value
			if(!$username && !$password && $this->exists()) {
				Session::put($this->_sessionName, $this->data()->id);
			} else {
				//logs user in by finding the user data in the database with username
				$user = $this->find($username);	
				if($user) {
					//if user exist check the password supplied with the stored password and log the user in
					if($this->data()->password === Hash::make($password,$this->data()->salt)) {
						
							//creates a session if the password checking passes
							Session::put($this->_sessionName, $this->data()->id);

							if($remember) {
								$hash = Hash::unique();
								$hashCheck  = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));
								//if user has not been remembered before,remember user by storing the hash in the database
								if(!$hashCheck->count()) {
									$this->_db->insert('users_session', array(
										'user_id' => $this->data()->id,
										'hash' => $hash
									));
								} else {
									//else get the hash from the database and save it as a cookie in the browser
									$hash = $hashCheck->first()->hash;
								}
								//sets cookie if user asks to be remembered
								Cookie::put($this->_cookieName, $hash, Config::get('cookie/expiry_one_week'));
							}
							return true;
						
					} else {
						$this->addError('wrong password');
					}
				} else {
					$this->addError('username does not exist');
				}
			}
			return false;
		}

		public function get($where = array(), $fields = '*') {
			if(!$info = $this->_db->get($this->_table, $where, $fields)) {
				throw new Exception("Error getting applicants info");
			}
			$this->total = $info->count();
			return $info->results();
		}
	}
?>
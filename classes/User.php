<?php
namespace dk\lightsaber\milage;

	Class User extends Persistent{
		public $firstName;
		public $lastName;
		public $email;
		protected $password;
		protected $accountId = -1;
		public $accessRights;
		
		protected $account;
	
		protected $table = 'user';
		protected $meta_mapping = array(
			'id' => 'id',
			'firstName' => 'first_name',
			'lastName' => 'last_name',
			'email' => 'email',
			'password' => 'password',
			'accessRights' => 'access_rights',
			'accountId' => 'account_id'
		);
	
		protected $meta_mapping_type = array(
			'id' => PersistentConst::NUMERIC,
			'firstName' => PersistentConst::STRING,
			'lastName' => PersistentConst::STRING,
			'email' => PersistentConst::STRING,
			'password' => PersistentConst::STRING,
			'accountId' => PersistentConst::NUMERIC,
			'accessRights' => PersistentConst::NUMERIC
		);

		/***********
		 * Standard Persistency methods for retrieving objects from the database
		 ***********/		
		public static function load($id) {
			return parent::_loadInstance('\dk\lightsaber\milage\User',$id);
		}
		
		public static function loadInstanceWhere($where) {
			return parent::_loadInstanceWhere('\dk\lightsaber\milage\User', $where);
		}
		
		public static function loadList($where) {
			return parent::_loadList('\dk\lightsaber\milage\User', $where);
		}
		
		public static function loadAll() {
			return parent::_loadAll('\dk\lightsaber\milage\User');
		}

		/***********
		 * custom methods
		 ***********/
		public function getAccount() {
			if(!isset($this->account)) {
				if($this->accountId != -1){
					$this->account = Account::load($this->accountId);
				} else {
					PersistentLog::warn("User does not have an account association.");
					return null;
				}
			}
			return $this->account;
		}
		
		public function setAccount($account) {
			if($account) {
				$this->account = $account;
				$this->accountId = $account->id;
				if(!$account->persist) {
					PersistentLog::warn("Account object has not yet been persisted! Relations might be incorrect if not saved correctly.");
				}
			} else {
				$this->account = null;
			}
		}
		
		public static function logIn($email, $password) {
			$where = "WHERE email='$email' AND password='$password'";
			return User::loadInstanceWhere($where);
		}
		
		/*
		 * Methods for retreiving Milage records for this user.
		 */
		public function getMilages() {
			$where = "WHERE user_id = $this->id ORDER BY date DESC";
			return Milage::loadList($where);
		}
		
		public function getMilagesFromTo($from, $to) {
			$where = "WHERE date BETWEEN ";
			$where .= "STR_TO_DATE('" . $from . "','%d-%m-%Y') and ";
			$where .= "STR_TO_DATE('" . $to . "','%d-%m-%Y') and ";
			$where .= "user_id = $this->id ";
			$where .= "ORDER BY date DESC";
			return Milage::loadList($where);
		}
		
		public function getMilagesLastXMonths($months) {
			$where = "WHERE date >= DATE_SUB(now(), INTERVAL " . $months . " MONTH) ";
			$where .= "AND user_id = $this->id ORDER BY date DESC";
			
			return Milage::loadList($where);
		}
		
		/*
		 * Methods relating to the users car(s)
		 */
		public function getCars() {
			$where = "WHERE user_id = " . $this->getId();
			return Car::loadList($where);
		}
		
		/***********
		 * toString method
		 ***********/
		public function __toString() {
			$string = 'Name: ' . $this->firstName . ' ' . $this->lastName . '<br/>';
			$string .= 'Email: ' . $this->email . '<br/>';
			$string .= 'Access Rights: ' . $this->accessRights . '<br/>';
			$string .= 'Password: ' . $this->password . '<br/>';
			$string .= 'ID: ' . $this->id . '<br/>';
			$string .= 'Account id: ' . $this->accountId . '<br/>';
			if(PersistentLog::$debug) {
				$string .= 'Persistent: ' . ($this->persist ? 'TRUE' : 'FALSE') . '<br/>';
			}
			
			return $string;
		
		}

		/***********
		 * Getter/Setters
		 ***********/		
		public function getFirstName() {
			return $this->firstName;
		}
		public function setFirstName($firstName) {
			$this->firstName=$firstName;
		}
		
		public function getLastName() {
			return $this->lastName;
		}
		public function setLastName($lastName) {
			$this->lastName=$lastName;
		}
		
		public function getEmail() {
			return $this->email;
		}
		public function setEmail($email) {
			$this->email=$email;
		}
		
		public function getPassword() {
			return $this->Password;
		}
		public function setPassword($password) {
			$this->password=$password;
		}
		
		public function getAccessRights() {
			return $this->accessRights;
		}
		public function setAccessRights($accessRights) {
			$this->accessRigts=$accessRights;
		}


	}

?>
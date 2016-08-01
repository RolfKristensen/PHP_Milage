<?php
namespace dk\lightsaber\milage;

	Class Account extends Persistent {
		public $accountName;
		public $accountOwnerId;
		public $status;
		
		public $accountOwner;
		public $users;
		
		protected $table = 'account';
		protected $meta_mapping = array(
			'id' => 'id',
			'accountName' => 'account_name',
			'status' => 'status'
		);
		protected $meta_mapping_type = array(
			'id' => PersistentConst::NUMERIC,
			'accountName' => PersistentConst::STRING,
			'status' => PersistentConst::NUMERIC
		);

		
		/***********
		 * Standard Persistency methods for retrieving objects from the database
		 ***********/		
		public static function load($id) {
			return parent::_loadInstance('\dk\lightsaber\milage\Account',$id);
		}
		
		public static function loadInstanceWhere($where) {
			return parent::_loadInstanceWhere('\dk\lightsaber\milage\Account', $where);
		}
		
		public static function loadList($where) {
			return parent::_loadList('\dk\lightsaber\milage\Account', $where);
		}
		
		public static function loadAll() {
			return parent::_loadAll('\dk\lightsaber\milage\Account');
		}

		/*
		 * Custom methods
		 */
		public function getUsers() {
			if(!isset($this->users)) {
				$where = "WHERE account_id=" . $this->id;
				$this->users = User::loadList($where);
			}
			return $this->users;
		}

		public function clearUsers() {
			$this->users = null;
		}
		
		/***********
		 * toString method
		 ***********/
		public function __toString() {
			$string = 'ID: ' . $this->id . '<br/>';
			$string .= 'Account name: ' . $this->accountName . '<br/>';
			$string .= 'Status: ' . $this->status . '<br/>';
			if(PersistentLog::$debug) {
				$string .= 'Persistent: ' . ($this->persist ? 'TRUE' : 'FALSE') . '<br/>';
			}
			return $string;
		}	

		/***********
		 * Getter/Setters
		 ***********/		
		public function getAccountName() {
			return $this->accountName;
		}
		public function setAccountName($accountName) {
			$this->accountName=$accountName;
		}
		
		public function getStatus() {
			return $this->status;
		}
		public function setStatus($status) {
			$this->status=$status;
		}


	}

?>
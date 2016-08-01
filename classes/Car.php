<?php
namespace dk\lightsaber\milage;

	Class Car extends Persistent {
		public $name;
		public $userId;
		public $make;
		public $model;
		public $modelSpecific;
		public $fuelType;
		public $milageMixed;
		
		public $user;
		
		protected $table = 'car';
		protected $meta_mapping = array(
			'id' => 'id',
			'name' => 'name',
			'userId' => 'user_id',
			'make' => 'make',
			'model' => 'model',
			'modelSpecific' => 'model_specific',
			'fuelType' => 'fuel_type',
			'milageMixed' => 'milage_mixed'
		);
		protected $meta_mapping_type = array(
			'id' => PersistentConst::NUMERIC,
			'name' => PersistentConst::STRING,
			'userId' => PersistentConst::NUMERIC,
			'make' => PersistentConst::STRING,
			'model' => PersistentConst::STRING,
			'modelSpecific' => PersistentConst::STRING,
			'fuelType' => PersistentConst::STRING,
			'milageMixed' => PersistentConst::NUMERIC
		);

		
		/***********
		 * Standard Persistency methods for retrieving objects from the database
		 ***********/
		public static function load($id) {
			return parent::_loadInstance('\dk\lightsaber\milage\Car',$id);
		}
		
		public static function loadInstanceWhere($where) {
			return parent::_loadInstanceWhere('\dk\lightsaber\milage\Car', $where);
		}
		
		public static function loadList($where) {
			return parent::_loadList('\dk\lightsaber\milage\Car', $where);
		}
		
		public static function loadAll() {
			return parent::_loadAll('\dk\lightsaber\milage\Car');
		}

		/*
		 * Custom methods
		 */
		public function getUser() {
			if(!isset($this->user)) {
				$this->user = User::load($this->userId);
			}
			return $this->user;
		}

		public function clearUser() {
			$this->users = null;
		}
		
		/***********
		 * toString method
		 ***********/
		public function __toString() {
			$string = '<h1>Car</h1>';
			$string .= 'ID: ' . $this->getId() . '<br/>';
			$string .= 'Car name: ' . $this->name . '<br/>';
			$string .= 'Make: ' . $this->make . '<br/>';
			$string .= 'Model: ' . $this->model . '<br/>';
			$string .= 'Model Specific: ' . $this->modelSpecific . '<br/>';
			$string .= 'Fuel type: ' . $this->fuelType . '<br/>';
			$string .= 'Milage mixed: ' . $this->milageMixed . ' km/l<br/>';
			if(PersistentLog::$debug) {
				$string .= 'Persistent: ' . ($this->persist ? 'TRUE' : 'FALSE') . '<br/>';
			}
			$string .= '<h2>User</h2>';
			if($this->getUser() != null) {
				$string .= $this->getUser()->__toString();
			} else {
				$string .= 'N/A <br/>';
			}
			return $string;
		}	
		/***********
		 * Getter/Setters
		 ***********/		
		public function getName() {
			return $this->name;
		}
		public function setName($name) {
			$this->name=$name;
		}
		
		public function getUserId() {
			return $this->userId;
		}
		public function setUserId($userId) {
			$this->userId=$userId;
		}
		
		public function getMake() {
			return $this->make;
		}
		public function setMake($make) {
			$this->make=$make;
		}
		
		public function getModel() {
			$this->model;
		}
		public function setModel($model) {
			$this->model=$model;
		}
		
		public function getModelSpecific() {
			return $this->modelSpecific;
		}
		public function setModelSpecific($modelSpecific) {
			$this->modelSpecific=$modelSpecific;
		}
		
		public function getFuelType() {
			return $this->fuelType;
		}
		public function setFuelType($fuelType) {
			$this->fuelType=$fuelType;
		}
		
		public function getMilageMixed() {
			return $this->milageMixed;
		}
		public function setMilageMixed($milageMixed) {
			$this->milageMixed=$milageMixed;
		}

	}

?>
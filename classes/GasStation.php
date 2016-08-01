<?php
namespace dk\lightsaber\milage;

	Class GasStation extends Persistent {
		public $name;
	
		protected $table = 'gas_station';
		protected $meta_mapping = array(
			'id' => 'id',
			'name' => 'name'
		);
		protected $meta_mapping_type = array(
			'id' => PersistentConst::NUMERIC,
			'name' => PersistentConst::STRING
		);

		/***********
		 * Standard Persistency methods for retrieving objects from the database
		 ***********/		
		public static function load($id) {
			return parent::_loadInstance('\dk\lightsaber\milage\GasStation',$id);
		}
		
		public static function loadInstanceWhere($where) {
			return parent::_loadInstanceWhere('\dk\lightsaber\milage\GasStation', $where);
		}
		
		public static function loadList($where) {
			return parent::_loadList('\dk\lightsaber\milage\GasStation', $where);
		}
		
		public static function loadAll() {
			return parent::_loadAll('\dk\lightsaber\milage\GasStation');
		}

		/***********
		 * toString method
		 ***********/
		public function __toString() {
			$string = $this->name. "<br/>" . $this->id . "<br/>";
			if(PersistentLog::$debug) {
				$string .= 'Persistent: ' . ($this->persist ? 'TRUE' : 'FALSE') . '<br/>';
			}
			
			return $string;
		}
	
		/***********
		 * Getter/Setters
		 ***********/		
		public function getName() {
			return $this->name;
		}

	}

?>
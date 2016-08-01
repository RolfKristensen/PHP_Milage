<?php
namespace dk\lightsaber\milage;

	Class Milage extends Persistent {
		// database columns
		public $date;
		public $litre;
		public $km;
		public $priceL;
		public $gasTypeId;
		public $gasStationId;
		public $kmL;
		public $priceKm;
		public $priceSum;
		public $userId;
		public $carId;
		
		public $car;
	
		protected $table = 'milage';
		protected $meta_mapping = array(
			'id' => 'id',
			'date' => 'date',
			'litre' => 'litre',
			'km' => 'km',
			'priceL' => 'price_l',
			'gasTypeId' => 'gas_type_id',
			'gasStationId' => 'gas_station_id',
			'kmL' => 'km_l',
			'priceKm' => 'price_km',
			'priceSum' => 'price_sum',
			'userId' => 'user_id',
			'carId' => 'car_id'
		);
		protected $meta_mapping_type = array(
			'id' => PersistentConst::NUMERIC,
			'date' => PersistentConst::DATE,
			'litre' => PersistentConst::NUMERIC,
			'km' => PersistentConst::NUMERIC,
			'priceL' => PersistentConst::NUMERIC,
			'gasTypeId' => PersistentConst::NUMERIC,
			'gasStationId' => PersistentConst::NUMERIC,
			'kmL' => PersistentConst::NUMERIC,
			'priceKm' => PersistentConst::NUMERIC,
			'priceSum' => PersistentConst::NUMERIC,
			'userId' => PersistentConst::NUMERIC,
			'carId' => PersistentConst::NUMERIC
		);

		
		
		/***********
		 * Standard Persistency methods for retrieving objects from the database
		 ***********/		
		public static function load($id) {
			return parent::_loadInstance('\dk\lightsaber\milage\Milage',$id);
		}
		
		public static function loadInstanceWhere($where) {
			return parent::_loadInstanceWhere('\dk\lightsaber\milage\Milage', $where);
		}
		
		public static function loadList($where) {
			return parent::_loadList('\dk\lightsaber\milage\Milage', $where);
		}
		
		public static function loadAll() {
			return parent::_loadAll('\dk\lightsaber\milage\Milage');
		}

		/*
		 * Custom methods
		 */
		public function getCar() {
			if(!isset($this->car)) {
				$this->setCar(Car::load($this->getCarId()));
			}
			return $this->car;
		}
		
		public function setCar($car) {
			$this->car=$car;
			if(!isset($this->carId)) {
				$this->carId=$car->getId();
			}
		}
		
		public function getDateStr() {
			return date('d-m-Y', strtotime($this->date));
		}

		/***********
		 * toString method
		 ***********/
		public function __toString() {
			$string = "<h1>Milage</h1> ";
			$string .= $this->id . "\t" . $this->getDateStr() . "\t" . $this->litre . "\t";
			$string .= $this->km . "\t" . $this->priceL . "\t" . $this->gasTypeId . "\t";
			$string .= $this->gasStationId . "\t" . $this->kmL . "\t";
			$string .= $this->priceKm;
			$string .= $this->getCar()->__toString();
			if(PersistentLog::$debug) {
				$string .= "\tPersistent: " . ($this->persist ? 'TRUE' : 'FALSE') . '<br/>';
			}

			return $string;
		}
		
		// Update methods of dynamic content
		public function calculateDynamicValues() {
			$this->calculateKmL();
			$this->calculatePriceKm();
			$this->calculatePriceSum();
		}
		
		protected function calculateKmL() {
			if($this->litre != 0) {
				$this->kmL = $this->km/$this->litre;
			}
		}
		
		protected function calculatePriceKm() {
			if($this->km != 0) {
				$this->priceKm = ($this->priceL * $this->litre)/$this->km;
			}
		}

		protected function calculatePriceSum() {
			$this->priceSum = $this->priceL*$this->litre;
		}
		
		
		
		// Getter/Setters

		public function getDate() {
			return $this->date;
		}
		public function setDate($date) {
			$this->date=$date;
		}
		
		public function getLitre() {
			return $this->litre;
		}
		public function setLitre($litre) {
			$this->litre=$litre;
		}
		
		public function getKm() {
			return $this->km;
		}
		public function setKm($km) {
			$this->km=$km;
		}
		
		public function getPriceL() {
			return $this->priceL;
		}
		public function setPriceL($priceL) {
			$this->priceL=$priceL;
		}
		
		public function getGasTypeId() {
			return $this->gasTypeId;
		}
		public function setGasTypeId($gasTypeId) {
			$this->gasTypeId=$gasTypeId;
		}
		
		public function getGasStationId() {
			return $this->gasStationId;
		}
		public function setGasStationId($gasStationId) {
			$this->gasStationId=$gasStationId;
		}
		
		public function getKmL() {
			return $this->kmL;
		}
		public function setKmL($kmL) {
			$this->kmL=$kmL;
		}
		
		public function getPriceKm() {
			return $this->priceKm;
		}
		public function setPriceKm($priceKm) {
			$this->priceKm=$priceKm;
		}

		public function getPriceSum() {
			return $this->priceSum;
		}
		public function setPriceSum($priceSum) {
			$this->priceSum=$priceSum;
		}
		
		public function getUserId() {
			return $this->userId;
		}
		public function setUserId($userId) {
			$this->userId=$userId;
		}
		
		public function getCarId() {
			return $this->carId;
		}
		public function setCarId($carId) {
			$this->carId=$carId;
		}
	}

?>
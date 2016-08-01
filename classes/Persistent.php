<?php
namespace dk\lightsaber\milage;

	Abstract Class Persistent {
		public $id=-1;
		
		/* 
		 * true/false defining if the object is persistent.
		 * This must only be changed by the persistency layer!
		 */
		protected $persist = false; 

		/*
		 * defines the tablename of the object
		 */
		protected $table;
		/*
		 * Meta mapping used for mapping a variable name to a column name in the database.
		 * array('class_variable_name1' => 'database_column_name1',
		 *		 'class_variable_name2' => 'database_column_name2', ...);
		 */
		protected $meta_mapping;
		protected $meta_mapping_type;
		
		/*
		 * Loads an instance of a given class with the specified id
		 * @param $class Class name of the object that should be instantiated
		 * @param $id The id of the record that should be used for instantiating the object
		 * @return and object of type $class, if the record exists in the database
		 */
		public static function _loadInstance($class, $id) {
			$obj = new $class();
			$where = 'WHERE ' . $obj->meta_mapping['id'] . ' = ' . $id;
			
			return Persistent::_loadInstanceWhere($class, $where);
		}
		
		public static function _loadInstanceWhere($class, $where) {
			$list = Persistent::_loadList($class, $where);
			
			if($list) {
				$count = sizeof($list);
				if($count != 1) {
					PersistentLog::error("Instance error... $count records found in the database.");
				} else {
					return $list[0];
				}
			}
			
			return Null;
		}
		
		/*
		 * Loads a list of objects based on the object type this method is called on.
		 * @param $class The class name that the list should contain objects of
		 * @param $where The where clause (user must write 'where' as well
		 * @return A list of the given object (if any results are available)
		 */
		public static function _loadList($class, $where) {
			$obj = new $class;
			$sql = 'SELECT * FROM ' . $obj->table . ' ' . $where;
			PersistentLog::info($sql);
			
			$list = array();
			
			$query = mysql_query($sql);
			$count = mysql_num_rows($query);

			if($count >= 1) {
				while($row = mysql_fetch_assoc($query)) {
					foreach($obj->meta_mapping as $variable => $column) {
						$obj->$variable = $row[$column];
					}
					$obj->persist=true;
					$list[] = $obj;
					$obj = new $class;
				}
				return $list;
			} else {
				PersistentLog::warn("Query didn't return any result");
			}
		}
		
		public static function _loadAll($class) {
			$where = "WHERE 1=1";
			return Persistent::_loadList($class, $where);
		}
		
		public function save() {
			if($this->persist) {
				// update
				$sql = $this->makeUpdateSQL();
				PersistentLog::info($sql);
				error_log($sql);
				if(PersistentConst::$doInserts) {
					$result = mysql_query($sql);
					
					if(!$result) {
						PersistentLog::error("Error saving persistent object: $this->table with id=$this->id");
					}
				} else {
					error_log("Inserts/updates are disabled!");
				}
			} else {
				// insert
				$sql = $this->makeInsertSQL();
				PersistentLog::info($sql);
				error_log($sql);
				if(PersistentConst::$doInserts) {
					$result = mysql_query($sql);
					
					if($result) {
						$this->id = mysql_insert_id();
						$this->persist = true;
					} else {
						PersistentLog::error("Error saving the object: $this->table to database.");
					}
				} else {
					error_log("Inserts/updates are disabled!");
				}
			}
		}
		
		private function makeUpdateSQL() {
			$sql = "UPDATE $this->table ";
			$where;
			$count = 0;
			foreach($this->meta_mapping as $variable => $column) {
				if($variable != 'id') {
					if($count == 0) {
						$sql .= 'set ';
					} else {
						$sql .= ', ';
					}
					if($this->meta_mapping_type[$variable] == PersistentConst::NUMERIC) {
						$sql .= "$column = " . mysql_real_escape_string($this->$variable);
					} else if($this->meta_mapping_type[$variable] == PersistentConst::STRING) {
						$sql .= "$column = '" . mysql_real_escape_string($this->$variable) . "'";
					} else if($this->meta_mapping_type[$variable] == PersistentConst::DATE) {
						$sql .= "$column = STR_TO_DATE('" . mysql_real_escape_string($this->$variable);
						$sql .= "','" . PersistentConst::DATE_FORMAT . "')";
					} else if($this->meta_mapping_type[$variable] == PersistentConst::TIME) {
						$sql .= "$column = STR_TO_DATE('" . mysql_real_escape_string($this->$variable);
						$sql .= "','" . PersistentConst::TIME_FORMAT . "')";
					} else if($this->meta_mapping_type[$variable] == PersistentConst::DATE_TIME) {
						$sql .= "$column = STR_TO_DATE('" . mysql_real_escape_string($this->$variable);
						$sql .= "','" . PersistentConst::DATE_TIME_FORMAT . "')";
					} else {
						PersistentLog::error("Could not detect $variable type... please check the type definition in meta_mapping_type");
					}
					$count++;
				} else {
					$where = " WHERE $column = " . $this->$variable;
				}
			}
			$sql .= $where;

			return $sql;
		}
		
		private function makeInsertSQL() {
			$sql = 'INSERT INTO ' . $this->table;
			$sql_into = '(';
			$sql_values= ' VALUES(';
			$count = 0;
			foreach($this->meta_mapping as $variable => $column) {
				if($variable != 'id') {
					if(isset($this->$variable)) {
						if($count!=0) {
							$sql_into .= ',';
							$sql_values .= ',';
						}
						$sql_into .= " $column";
						if($this->meta_mapping_type[$variable] == PersistentConst::NUMERIC) {
							$sql_values .= $this->$variable;
						} else if($this->meta_mapping_type[$variable] == PersistentConst::STRING) {
							$sql_values .= "'" . mysql_real_escape_string($this->$variable) . "'";
						} else if($this->meta_mapping_type[$variable] == PersistentConst::DATE) {
							$sql_values .= "STR_TO_DATE('" . mysql_real_escape_string($this->$variable) . "','";
							$sql_values .= PersistentConst::DATE_FORMAT . "')";
						} else if($this->meta_mapping_type[$variable] == PersistentConst::TIME) {
							$sql_values .= "STR_TO_DATE('" . mysql_real_escape_string($this->$variable) . "','";
							$sql_values .= PersistentConst::TIME_FORMAT . "')";
						} else if($this->meta_mapping_type[$variable] == PersistentConst::DATE_TIME) {
							$sql_values .= "STR_TO_DATE('" . mysql_real_escape_string($this->$variable) . "','";
							$sql_values .= PersistentConst::DATE_TIME_FORMAT . "')";
						} else {
							PersistentLog::error("Could not detect $variable type... please check the type definition in meta_mapping_type");
						}
						$count++;
					}
				}
			}
			$sql_into .= ')';
			$sql_values .= ')';
			$sql .= $sql_into . $sql_values;

			return $sql;
		}

		public function isDateTime() {
			if( $this->meta_mapping_type[$variable] == PersistentConst::DATE ||
				$this->meta_mapping_type[$variable] == PersistentConst::TIME ||
				$this->meta_mapping_type[$variable] == PersistentConst::DATE_TIME) {
				return true;
			}
			return false;

		
		}
		
		/**
		 * Magic method used to set the value and check if it's valid
		 * 
		 * @param string $name name of the value
		 * @param string $value value itself
		 * @return bool 
		 */
		public function __set($name, $value)
		{
			try
			{
				$this->$name = trim($value);
				return true;
			}
			catch (Exception $e)
			{
				echo "Error setting ".$name.": ".$e->getMessage();
			}
		}

		/**
		 * Magic method used to get the value of an attribute
		 * 
		 * @param string $name name of the value
		 * @return mixed 
		 */
		public function __get($name)
		{
			return $this->$name;
		}

		/***********
		 * Getter/Setters
		 ***********/		
		public function getId() {
			return $this->id;
		}
		public function setId($id) {
			$this->id=$id;
		}
	}
?>
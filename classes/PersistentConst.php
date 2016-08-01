<?php
namespace dk\lightsaber\milage;

	Class PersistentConst {

		/*
		 * Type definitions used for the meta_mapping_type array of the persistent objects.
		 */
		const NUMERIC = 0;
		const STRING = 1;
		const DATE = 2;
		const TIME = 3;
		const DATE_TIME = 4;
		
		const DATE_FORMAT = "%d-%m-%Y";
		const TIME_FORMAT = "%H:%i:%s";
		const DATE_TIME_FORMAT = "%d-%m-%Y %H:%i:%s";

		public static $doInserts=true;
	}

?>

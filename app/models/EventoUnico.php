<?php

namespace LBBAS\app\models;

class EventoPeriodico extends Evento {

	/// @Constructor
	public function __construct(int $ID, int $CREADOR, string $DESC, int $FECHA) {
		parent::__construct($ID, $CREADOR, $DESC, $FECHA);
	}

	/// @Destructor
	public function __destruct() {
		parent::__destruct();
	}

	/// @Getters

	/// @Setters

	/// @Methods
	
}

?>
<?php

namespace LBBAS\app\models;

class EventoPeriodico extends Evento {
	private int $periodo;

	/// @Constructor
	public function __construct(int $ID, int $CREADOR, string $DESC, int $FECHA, int $PERIODO) {
		parent::__construct($ID, $CREADOR, $DESC, $FECHA);
		$this->periodo = $PERIODO;
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
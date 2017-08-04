<?php

namespace Picahoo\Communicator\Facades;

use Illuminate\Support\Facades\Facade;

class CommunicatorFacade extends Facade{
	protected static function getFacadeAccessor(){
		return "picahoo-communicator";
	}
}
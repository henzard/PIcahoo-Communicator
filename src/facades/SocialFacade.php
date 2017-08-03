<?php

namespace honin\social\facades;

use Illuminate\Support\Facades\Facade;

class SocialFacade extends Facade{
	protected static function getFacadeAccessor(){
		return "honin-social";
	}
}
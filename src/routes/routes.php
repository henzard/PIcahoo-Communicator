<?php

Route::any('test', function(){
		if($result = HSocial::send_email("admin@weboccult.com", "123456", "henzard@picahoo.co.za", "testing the communicator", "Testing"))
		{
			echo "Test send email passed ".$result." <br>";
		}
		else
		{
			echo "Test send email FAILED <br>";
		}
		if($result = HSocial::send_sms("admin@weboccult.com", "123456", "27711304241", "testing the communicator"))
		{
			echo "Test send sms passed ".$result." <br>";
		}
		else
		{
			echo "Test send sms FAILED <br>";
		}
});

Route::any('login', function(){
	return HSocial::login("admin@weboccult.com", "123456");
});

Route::any('contacts', function(){
	return HSocial::get_contacts("admin@weboccult.com", "123456", "+27");
});

Route::any('addcontact', function(){
	return HSocial::add_contact("admin@weboccult.com", "123456", "sam", "phillop", "dumbymail@gmail.com", "+27748697417");
});

Route::any('getemail', function(){
	return HSocial::get_email();
});

Route::any('sendemail', function(){
	return HSocial::send_email("admin@weboccult.com", "123456", 'dgmon.mail@gmail.com', "this is a message", "test mail");
});

Route::get('send_sms', function(){
	return HSocial::send_sms("admin@weboccult.com", "123456", 5, "this is a message");
});

Route::get('get_sms', function(){
	return HSocial::get_sms("admin@weboccult.com", "123456");
});


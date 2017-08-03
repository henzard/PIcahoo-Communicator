<?php

namespace Honin\Social;
use Session;
class Social{


	// Login function returns token on sucsess
	public function login($username, $password){
		
		$data = ["email"=>$username, "password"=>$password];
		
		$auth = json_decode($this->request("POST", $data, [], "user/authenticate"), true);

		

		if(isset($auth['token'])){
			return $auth;
		}else{
			return false;
		}
	}
	

	/*		CONTACT THINGS	*/

	// Fetches all the contacts
	public function get_contacts($username, $password, $filter=""){
		$token = $this->login($username, $password);
		if(!$token)
			return false;
		$token = $token['token'];
		$data = json_decode($this->request("GET", [], ["authorization: Bearer <".$token.">"], "user/contact/all"), true)['contacts'];		

		global $return;
		$return = [];
		
		
		$test = function($item, $key, $check){
			
			global $return;

			$tests = ['id', 'email', 'phone'];
			foreach($tests as $test){
				if(empty($check)){
					array_push($return, $item);
					return;
				}else{
					$has = strpos(" ".$item[$test], $check);
					if($has){
						array_push($return, $item);
						return;
					}	
				}
				
			}
		};
		array_walk($data, $test, $filter);

		return $return;
	}

	public function add_contact($username, $password, $first_name, $last_name, $email, $phone){
		$email_taken = $this->get_contacts($username, $password, $email);
		$number_taken = $this->get_contacts($username, $password, $phone);


		if($number_taken or $email_taken)
			return false;

		$auth = $this->login($username, $password);
		return $this->request("POST", [	"first_name"=>$first_name, 
									"last_name"=>$last_name, 
									"email"=>$email, 
									"phone"=>$phone
								], 
								["authorization: Bearer <".$auth['token'].">"],
								"user/contact/create");
	}

	/*		END CONTACT THINGS	*/

	// send an email
	public function send_email($username, $password, $to, $message, $subject){
		$token = $this->login($username, $password);
		if(!$token)
			return false;
		$token = $token['token'];

		$contact = $this->get_contacts($username, $password, $to);
		if(empty($contact))
			return false;
		$contact = $contact[0];
		return $this->request("POST", ["message"=>$message, "contact_id"=>$contact['id'],"subject"=>$subject], ["authorization: Bearer <".$token.">"], "mandrill/send");
	}

	// sends an sms
	public function send_sms($username, $password, $to, $message){
		$token = $this->login($username, $password);
		if(!$token)
			return false;
		$token = $token['token'];


		$contact = $this->get_contacts($username, $password, $to);
		
		if(empty($contact))
			return false;

		$contact = $contact[0];

		return $this->request("POST", ["message"=>$message, "contact_id"=>$contact['id']], ["authorization: Bearer <".$token.">"], "sms/send");
	}

	public function get_email(){
		return $this->request("GET", [], [], "mandrill/receive");
	}

	public function get_sms($username, $password){
		$token = $this->login($username, $password)['token'];
		return $this->request("GET", [], [], "sms/receive?token=".$token);
	}


	private function request($type="GET", $data=[], $headers=[], $url){
		$curl = curl_init();
		
		$HEADERS = ["cache-control: no-cache",
			"content-type: application/json; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
			"postman-token: d01b2ab1-dc2a-dc18-1199-47f83f334200"];

		$HEADERS = array_merge($HEADERS, $headers);

		//dd($query);
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://picahooapi.test4you.in/api/v1/".$url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 25,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => $type,
		  CURLOPT_POSTFIELDS => json_encode($data),
		  CURLOPT_HTTPHEADER => $HEADERS,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		if ($err) {
		  dd("cURL Error #:" . $err);
		} else {
			return $response;
		}
	}
}
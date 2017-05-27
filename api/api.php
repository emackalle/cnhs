<?php

class control{
	function validate_request($uri, $type){		
		if( in_array($type, ["GET", "POST", "DELETE", "PUT"]) ){
			$arr_api = explode("/", $_GET["request"]);
			$this->get_api($arr_api, $type);
		}else{
			$this->response(400);
		}
	}
	function get_api($arr_api, $type){
		$group = $arr_api[0];
		$fn = $arr_api[1];
		$rs = new $group();
		switch($type){
			case "GET":				
				$fn = explode("?", $fn)[0];
				$data = isset($arr_api[2]) ? $arr_api[2] : $_GET;
				break;
			case "POST":
				$data = $_POST;
				break;
			case "PUT":
				$data = $this->get_input();
				break;
			case "DELETE":
				$data = isset($arr_api[2]) ? $arr_api[2] : $this->get_input();
				break;
		}
		$rs->$fn($this, $data);
	}
	function is_ajax() { return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'; }
	function get_input(){ $query_str = file_get_contents("php://input"); $array = array(); parse_str($query_str, $array); return $array;}
	function response($status, $data){
		header("Content-Type: application/json");
		header("HTTP/1.1 $status");
		echo json_encode($data);
	}
}

class member{
	function login($http, $data){
		$http->response(200, $data);
	}
	function info($http, $data){
		$http->response(200, $data);
	}
}





$control = new control();
// if($api->is_ajax()){
	$control->validate_request($_SERVER["REQUEST_URI"], $_SERVER["REQUEST_METHOD"]);
// }

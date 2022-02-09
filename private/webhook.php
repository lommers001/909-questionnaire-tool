<?php

//$post_data = $_POST;
//$post = json_decode(file_get_contents('php://input'), true);
$post = file_get_contents('php://input');
//$post = json_decode('{"event":"lead_update","payload":{"leadId":133,"campaignId":34,"supplierId":34,"previousStatusId":2,"statusId":1,"receivedAt":1638952837,"instance":"crsadvertising","data":{"1":"jan@kok.nl","3":"Jan","4":"Kok","5":"1999-05-05","6":"","7":"","8":"","9":"","11":"","12":"+31206667890","18":22,"133":"Kersenvlaai, Fijne Cake","166":""},"optinData":[],"message":""}}');


update_webhook($post);
http_response_code(200);
exit();

function create_webhook($data){
	$success = false;
	$request = json_encode($data);
	try {
		$dbh = get_connection_dbh();
		$sql = "INSERT INTO webhook_data (`data`) VALUES (?)";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $request, PDO::PARAM_STR);
		$stmt->execute();
		$success = $stmt->rowCount() > 0;
	} catch (PDOException $e) {
		//
	}
}

function update_webhook($data){
	//$lead_id = $data->payload->leadId;
	//$status = $data->payload->statusId;
	//$message = $data->payload->message;
	//$request = json_encode($data->payload);
	$lead_id = 133;
	$status = 1;
	$message = "";
	$request = $data;
	try {
		$dbh = get_connection_dbh();
		$sql = "UPDATE webhook_data SET `data` = ?, `status` = ?, `message` = ? WHERE `lead_id` = ?";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $request, PDO::PARAM_STR);
		$stmt->bindParam(2, $status, PDO::PARAM_STR);
		$stmt->bindParam(3, $message, PDO::PARAM_STR);
		$stmt->bindParam(4, $lead_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->rowCount() > 0;
	} catch (PDOException $e) {
		return null;
	}
}

function delete_webhook($data){
	$success = false;
	try {
		$dbh = get_connection_dbh();
		$sql = "UPDATE landings SET deleted_at = NOW() WHERE id = ?";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $request['id'], PDO::PARAM_INT);
		$stmt->execute();
		$success = $stmt->rowCount() > 0;
	} catch (PDOException $e) {
		//
	}
}

function get_connection_dbh(){
	$is_local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);
	$c_data = [
		"host" => $is_local ? "127.0.0.1" : "10.0.0.2",
		"user" => "prelander",
		"name" => "prelander",
		"pw" => "RIpaQIFoH4F1",
		"port" => $is_local ? "3307" : "3306"
	];
	return new pdo("mysql:host=".$c_data['host'].";dbname=".$c_data['name'].";port=".$c_data['port'], $c_data['user'], $c_data['pw'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}

function pdo_get($table, $key, $value, $fetch_all = false){
	try {
		$dbh = get_connection_dbh();
		$sql = "SELECT * FROM ".$table." WHERE ".$key." = ?";
		$stmt = $dbh->prepare($sql);
		$is_number = intval($value) !== 0;
		$stmt->bindParam(1, $value, $is_number ? PDO::PARAM_INT : PDO::PARAM_STR);
		$stmt->execute();
		$result = $fetch_all ? $stmt->fetchAll() : $stmt->fetch();
		return $result;
	} catch (PDOException $e) {
		var_dump($e);
		return null;
	}
}
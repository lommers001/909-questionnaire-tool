<?php

if (isset($_POST['response'])){
	save_response($_POST);
	exit();
}
if (isset($_POST['legaltext'])){
	if($_POST['action'] == "create_new"){
		create_legal($_POST);
	}
	if($_POST['action'] == "update"){
		update_legal($_POST);
	}
}
if (isset($_POST['action'])){
	if($_POST['action'] == "create_new"){
		create_prelanding($_POST);
	}
	if($_POST['action'] == "update"){
		update_prelanding($_POST);
	}
	if($_POST['action'] == "activate"){
		update_activity($_POST);
	}
	if($_POST['action'] == "copy"){
		copy_prelanding($_POST);
	}
	if($_POST['action'] == "delete"){
		delete_prelanding($_POST);
	}
}
if (isset($_POST['slug'])){
	if (isset($_POST['locales'])){
		save_locales($_POST['slug'], $_POST['locales']);
	}
	if (isset($_POST['id'])){
		save_image($_POST, $_FILES);
	}
	if (isset($_POST['content'])){
		save_content($_POST['slug'], $_POST['content']);
	}
}

http_response_code(404);
echo "nothing: " . count($_POST) . " in post, " . count($_FILES) . " in files";
exit();

//Locales
function save_locales($slug, $request){
	if(pdo_save_locales($slug, $request)){
		http_response_code(200);
		exit();
	}
	http_response_code(500);
	die();
}

//Image
function save_image($request, $images){
	$directory = './storage/editor/' . $request['slug'] . '/';
	$file_name = $request['id'] . '.jpg';
	echo($directory . $file_name . "\n");
	// Save image
	if (count($images) > 0){
		foreach($images as $image){
			echo $image["tmp_name"];
			if(!is_dir($directory)){
				mkdir($directory);
			}
			move_uploaded_file($image["tmp_name"], $directory . $file_name);
			break;
		}
	}
	// Remove image
	else if (file_exists($directory . $file_name)){
		echo "removing img";
		unlink($directory . $file_name);
	}
	http_response_code(201);
	exit();
}

//Content
function save_content($slug, $content){
	if(pdo_save_landing($slug, $content)){
		http_response_code(200);
		exit();
	}
	http_response_code(201);
	die();
}

function create_legal($request){
	$success = false;
	try {
		$dbh = get_connection_dbh();
		$sql = "INSERT INTO legals (`name`, `text`, `title`, `c2a`) VALUES (?, ?, ?, ?)";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $request['name'], PDO::PARAM_STR);
		$stmt->bindParam(2, $request['legaltext'], PDO::PARAM_STR);
		$stmt->bindParam(3, $request['title'], PDO::PARAM_STR);
		$stmt->bindParam(4, $request['c2a'], PDO::PARAM_STR);
		$stmt->execute();
		$success = $stmt->rowCount() > 0;
	} catch (PDOException $e) {
		//
	}
	header('Location: '.'legals.php?msg='. ($success ? 'created' : 'fail' ));
	exit();
}

function update_legal($request){
	$success = false;
	try {
		$dbh = get_connection_dbh();
		$sql = "UPDATE legals SET `name` = ?, `text` = ?, `title` = ?, `c2a` = ? WHERE id = ?";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $request['name'], PDO::PARAM_STR);
		$stmt->bindParam(2, $request['legaltext'], PDO::PARAM_STR);
		$stmt->bindParam(3, $request['title'], PDO::PARAM_STR);
		$stmt->bindParam(4, $request['c2a'], PDO::PARAM_STR);
		$stmt->bindParam(5, $request['id'], PDO::PARAM_INT);
		$stmt->execute();
		$success = $stmt->rowCount() > 0;
	} catch (PDOException $e) {
		//
	}
	header('Location: '.'legals.php?msg='. ($success ? 'updated' : 'fail' ));
	exit();
}

function create_prelanding($request, $content = null, $dbh = null){
	$success = false;
	$msg = $content == null ? 'created' : 'copied';
	$databowl_id = $request['databowl_id'] ? $request['databowl_id'] : '0';
	try {
		if($content == null){
			$content = '{"pages":[],"style":{"header":["Roboto","normal","28","#000000","center"],"par":["Roboto","normal","16","#000000","center"],"fph":["Roboto","normal","16","#000000","left"],"flabel":["Roboto","normal","16","#000000","left"],"fanswer":["Roboto","normal","16","#000000","center"],"fbutton":["Roboto","normal","16","#000000","center"],"bround":4,"gcolor":"#ffffff","acolor":"#20833a","bcolor":"#ec5522","img":["G",0,0,0]}}';
		}
		if ($dbh == null){
			$dbh = get_connection_dbh();
		}
		
		$sql = "INSERT INTO landings (country_id, name, active, slug, page_title, landing_settings, redirect, legal_id, databowl_id) VALUES (?, ?, ".($request['active'] ? 1 : 0).", ?, ?, ?, ?, ?, ?)";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $request['country_id'], PDO::PARAM_INT);
		$stmt->bindParam(2, $request['name'], PDO::PARAM_STR);
		$stmt->bindParam(3, $request['slug'], PDO::PARAM_STR);
		$stmt->bindParam(4, $request['page_title'], PDO::PARAM_STR);
		$stmt->bindParam(5, $content, PDO::PARAM_STR);
		$stmt->bindParam(6, $request['tracking'], PDO::PARAM_STR);
		$stmt->bindParam(7, $request['legal_id'], PDO::PARAM_INT);
		$stmt->bindParam(8, $databowl_id, PDO::PARAM_INT);
		$stmt->execute();
		$success = $stmt->rowCount() > 0;
	} catch (PDOException $e) {
		//
	}
	header('Location: '.'/?msg='. ($success ? $msg : 'fail' ));
	exit();
}

function update_prelanding($request){
	$success = false;
	$databowl_id = $request['databowl_id'] ? $request['databowl_id'] : '0';
	try {
		$dbh = get_connection_dbh();
		$sql = "UPDATE landings SET country_id = ?, name = ?, active = ".($request['active'] ? 1 : 0).", slug = ?, page_title = ?, legal_id = ?, redirect = ?, databowl_id = ?, updated_at = NOW() WHERE id = ?";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $request['country_id'], PDO::PARAM_INT);
		$stmt->bindParam(2, $request['name'], PDO::PARAM_STR);
		$stmt->bindParam(3, $request['slug'], PDO::PARAM_STR);
		$stmt->bindParam(4, $request['page_title'], PDO::PARAM_STR);
		$stmt->bindParam(5, $request['legal_id'], PDO::PARAM_INT);
		$stmt->bindParam(6, $request['tracking'], PDO::PARAM_STR);
		$stmt->bindParam(7, $databowl_id, PDO::PARAM_INT);
		$stmt->bindParam(8, $request['id'], PDO::PARAM_INT);
		$stmt->execute();
		$success = $stmt->rowCount() > 0;
	} catch (PDOException $e) {
		var_dump($e);
		die();
	}
	header('Location: '.'/edit.php?id='.$request['id'].'&msg='.($success?'edited':'fail'));
	exit();
}

function update_activity($request){
	$success = false;
	try {
		$dbh = get_connection_dbh();
		$sql = "UPDATE landings SET active = ".($request['active'] ? 1 : 0).", updated_at = NOW() WHERE id = ?";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $request['id'], PDO::PARAM_INT);
		$stmt->execute();
		$success = $stmt->rowCount() > 0;
	} catch (PDOException $e) {
		//
	}
	exit();
}

function copy_prelanding($request){
	$success = false;
	try {
		$dbh = get_connection_dbh();
		$sql = "SELECT landing_settings FROM landings WHERE id = ?";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $request['id'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch();
		$copied_content = $result['landing_settings'];
		$copied_content = preg_replace('/"track":".+"/', '"track":"'.$request['tracking'].'"', $copied_content);
		create_prelanding($request, $copied_content, $dbh);
	} catch (PDOException $e) {
		//
	}
	header('Location: '.'/?msg=fail');
	exit();
}

function delete_prelanding($request){
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
	header('Location: '.'/?msg='.($success?'deleted':'fail'));
	exit();
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

function pdo_save_landing($slug, $content){
	try {
		$dbh = get_connection_dbh();
		$data = htmlspecialchars($content, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML401);
		$sql = "UPDATE landings SET landing_settings = ?, updated_at = NOW() WHERE slug = ?";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $data, PDO::PARAM_STR);
		$stmt->bindParam(2, $slug, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->rowCount() > 0;
	} catch (PDOException $e) {
		return null;
	}
}

function pdo_save_locales($slug, $content){
	try {
		$dbh = get_connection_dbh();
		$input = htmlspecialchars($content, ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML401);
		$data = explode("¿", $input);
		//Get country ID
		$sql = "SELECT country_id FROM landings WHERE slug = ?";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $slug, PDO::PARAM_STR);
		$stmt->execute();
		$country_id = $stmt->fetch()['country_id'];
		//Get previous locales
		$sql = "SELECT * FROM locales WHERE country_id = ?";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $country_id, PDO::PARAM_INT);
		$stmt->execute();
		$prev_locales = $stmt->fetchAll();
		//Update locales
		$sql = "UPDATE locales SET content = ? WHERE id = ?";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(1, $locale_content, PDO::PARAM_STR);
		$stmt->bindParam(2, $locale_id, PDO::PARAM_INT);
		foreach($prev_locales as $id => $locale){
			//Don't update if nothing changed
			if($data[$id] == $locale['content']) continue;
			$locale_id = $locale['id'];
			$locale_content = $data[$id];
			$stmt->execute();
		}
		return true;
	} catch (PDOException $e) {
		echo $e->get_message();
		return false;
	}
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

//Save response of filled in pre-landers
function save_response($request)
{
	$landing = pdo_get("landings", "slug", $request['slug']);
	$databowl_id = $landing['databowl_id'];
	if($databowl_id == null || $databowl_id == 0){
		echo json_encode(["debugMessage" => "No integration"]);
		exit();
	}
	$pages = json_decode($landing['landing_settings'])->pages;
	$missing_xids = false;
	$field_ids = [];
	$page_fields = [];
	foreach($pages as $page_index => $page){
		if (isset($page->xid)){
			if($page->xid[0] == ""){
				$missing_xids = true;
			}
			foreach($page->xid as $field_index => $field){
				array_push($field_ids, $field);
				array_push($page_fields, ["page" => $page_index, "field" => $field, "index" => $field_index]);
			}
		}
	}
	if($missing_xids){
		echo json_encode(["debugMessage" => "No integration"]);
		exit();
	}

	$is_updating = isset($request['lead_id']) && $request['lead_id'] != null;

	$url = $is_updating ? ("https://crsadvertising.databowl.com/api/v1/lead-data/update/".$request['lead_id']) : "https://crsadvertising.databowl.com/api/v1/lead";
    $fields = "cid=" . $databowl_id . "&sid=34";
	if($is_updating){
		$fields = "key=3DNRQ6N2SIFLCG4P&reprocess=true&validate=true";
	}
	$i = 0;
	foreach($request['response'] as $answer){
		if(strpos($answer, '¿') !== false) {
			$answers_to_add = explode('¿', $answer);
			foreach($answers_to_add as $answer_to_add){
				$fields .= '&' . $field_ids[$i] . '=' . utf8_encode($answer_to_add);
				$i++;
			}
		}
		else{
			$fields .= '&' . $field_ids[$i] . '=' . utf8_encode($answer);
			$i++;
		}
	}
    $certificate = "cacert.pem";

    $defaults = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded','Content-Length: '.strlen($fields),'Host: crsadvertising.databowl.com'),
        CURLOPT_URL => $url,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => $is_updating ? 10 : 5,
        CURLOPT_POSTFIELDS => $fields,
        CURLOPT_CAINFO => $certificate,
        CURLOPT_CAPATH => $certificate
    );

    $ch = curl_init();
    curl_setopt_array($ch, $defaults);
    if( ! $result = curl_exec($ch))
    {
		http_response_code(500);
        echo(curl_error($ch));
		exit();
    }
	$httpcode = curl_getinfo($ch);
    curl_close($ch);

    $json = json_decode($result);
    
    if(isset($json->error) && $json->error){
        http_response_code(400);
		foreach($page_fields as $field){
			if($field['field'] == $json->fields[0]){
				$json->page = $field['page'];
				$json->field = $field['index'];
				break;
			}
		}
		$result = json_encode($json);
    }
	echo $result;
	exit();
}
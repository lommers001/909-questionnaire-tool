<?php

function index(){
	$filters = $_GET;
	$current_page = isset($filters["page"]) ? intval($filters["page"]) : 0;
	$max_items_per_page = 20;
	return pdo_get_index($filters, $current_page, $max_items_per_page);
}

function get_content_editor($slug)
{
	$record = pdo_get("landings", "slug", $slug);
	if($record == null){
		return null;
	}
	$content = utf8_encode($record['landing_settings']);
	$content = str_replace("\\", "\\\\", $content);
	$content = str_replace("'", "", $content);
	$content = str_replace("\"", "&quot;", $content);

	$locales = pdo_get("locales", "country_id", $record["country_id"], true);
	$legal_record = pdo_get("legals", "id", $record['legal_id']);
	$legal = $legal_record ? ("<h2>".$legal_record['title']."</h2><br>".$legal_record['text']) : "<h2>Legal</h2><br>Legal text goes here";
	$legal = str_replace('"', '&quot;', $legal);
	$legal_c2a = $legal_record["c2a"];

	$return = [
		"content" => $content,
		"country" => $record["country_id"],
		"locales" => $locales,
		"title" => $record["name"],
		"legal" => $legal,
		"legal_c2a" => $legal_c2a
	];
	
	return $return;
}

function get_content($slug)
{
	require "mobiledetect/Mobile_Detect.php";
	$landing = pdo_get("landings", "slug", $slug);
	if($landing == null){
		return null;
	}
	$data = $landing['landing_settings'];
	//$content = str_replace("\\", "\\\\", $content);
	$data = utf8_encode(str_replace("'", "", $data));
    $content = json_decode($data);

    //contstants
    $DEVICE_NONE = 0;
    $DEVICE_DESKTOP = 1;
    $DEVICE_MOBILE = 2;
    $DEVICE_ALL = 3;
	$VISIBILITY_NONE = 0;
	$VISIBILITY_MOBILE = 1;
	$VISIBILITY_DESKTOP = 2;
	$VISIBILITY_ALL = 3;
	$NO_LEGAL = -999;

    //Get device type
    $agent = new Mobile_Detect();
    $device_type = $agent->isTablet() ? $DEVICE_MOBILE : ($agent->isMobile() ? $DEVICE_MOBILE : $DEVICE_DESKTOP);
	if(isset($_GET["device"])){
		$device_type = $_GET["device"] == "mobile" ? $DEVICE_MOBILE : $DEVICE_DESKTOP;
	}

    //Edit IDs and style
    $id_minus = 0;
    $legal_at = $NO_LEGAL;
	$count = count($content->pages);
    for ($i = 0; $i < $count; $i++){
        if (isset($content->pages[$i]->device)){
            if ($content->pages[$i]->device == $VISIBILITY_NONE || $content->pages[$i]->device > 4){
				array_splice($content->pages, $i, 1);
				$i -= 1;
				continue;
			}
			if ($content->pages[$i]->device == $VISIBILITY_DESKTOP && $device_type == $DEVICE_MOBILE){
				array_splice($content->pages, $i, 1);
				$i -= 1;
				continue;
			}
			if ($content->pages[$i]->device == $VISIBILITY_MOBILE && $device_type == $DEVICE_DESKTOP){
				array_splice($content->pages, $i, 1);
				$i -= 1;
				continue;
			}
        }
        
        if ($content->pages[$i]->type == 8 && $device_type == $DEVICE_MOBILE){
            $content->pages[$i]->id = $i;
            if ($legal_at == $NO_LEGAL)
                $legal_at = $i;
        }

        if(!isset($content->pages[$i]->style) || $content->pages[$i]->style === [])
            $content->pages[$i]->style = $content->style;
        else{
            foreach($content->style as $key => $value){
                if(!isset($content->pages[$i]->style->$key)){
                    $content->pages[$i]->style->$key = $value;
                }
                /*else if ($key == "img"){
                    for($ii = 1; $ii < 4; $ii++){
                        if($content->pages[$i]->style->img[$ii] == 0)
                            $content->pages[$i]->style->img[$ii] = $content->style->img[$ii];
                    }
                }*/
            }
        }
    }

	$content->device = ($device_type == 2 ? 3 : 1);
	$content->country = $landing["country_id"];
	$content->redirect = $landing["redirect"];
	$content->legal_at = $legal_at;

	$legal_record = pdo_get("legals", "id", $landing['legal_id']);
	$content->legal_text = $legal_record ? $legal_record : ["title" => "Legal", "text" => "Legal text goes here", "c2a" => "OK"];
	$content->legal_text['text'] = preg_replace("/\s<\//", "&nbsp;</", $content->legal_text['text']);

	return $content;
}

function setActivity($request){
	$landing = Landing::where('id', $request->id)->first();
	$landing->active = $request->active;
	$landing->save();
	return $landing->active;
}

function getAddress($request){
	//Client params: key, secret, platform
	include "PostcodeNl/Client.php";
	$client = new Client("803D1VXYMNZhSoyiRJl4W3TWRcuVIoiBX9haTvuvjLg", "A6YOkIrL3FYPcnI02hl7xLmueWc5WxuJZPsqLPFfFD794MUQRK", 'example proxy');
	try{
		return $client->dutchAddressByPostcode($request->post_code, intval($request->house_number), $request->h_addition);
	}
	catch(\App\Services\PostcodeNl\Exception\NotFoundException $e){
		return 404;
	}
	catch(\Throwable $e){
		return 400;
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

function pdo_get_all($table){
	try {
		$dbh = get_connection_dbh();
		$sql = "SELECT * FROM ".$table;
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	} catch (PDOException $e) {
		var_dump($e);
		return null;
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

function pdo_get_index($filters, $current_page, $max_items_per_page){
	try {
		$dbh = get_connection_dbh();
		$sql = "SELECT COUNT(id) AS `count` FROM landings WHERE deleted_at IS NULL";
		if (isset($filters['id']) && $filters['id']){
			$sql .= " AND id = " . htmlspecialchars($filters['id']);
		}
		if (isset($filters['name']) && $filters['name']){
			$sql .= " AND LOWER(name) LIKE LOWER('%" . htmlspecialchars($filters['name']) . "%')";
		}
		if (isset($filters['country_id']) && $filters['country_id']){
			$sql .= " AND country_id = " . htmlspecialchars($filters['country_id']);
		}
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		$count = ceil($stmt->fetch()["count"] / $max_items_per_page);
		$sql = str_replace("COUNT(id) AS `count`", "*", $sql);
		$sql .= " ORDER BY id DESC";
		$sql .= " LIMIT " . $max_items_per_page . " OFFSET " . ($current_page * $max_items_per_page);
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return [ "items" => $result, "count" => $count, "current" => $current_page, "prev" => $current_page == 0 ? 0 : $current_page - 1, "next" => $current_page + 1 == $count ? $current_page : $current_page + 1 ];
	} catch (PDOException $e) {
		var_dump($e);
		return null;
	}
}

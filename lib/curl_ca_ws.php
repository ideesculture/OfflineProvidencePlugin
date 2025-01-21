<?php

function getAuthToken() {
	$curl = curl_init(__CA_SITE_PROTOCOL__."://".__DH_CA_WS_USER__.":".__DH_CA_WS_PASS__."@".__CA_SITE_HOSTNAME__.__CA_URL_ROOT__."/service.php/auth/login");
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	if(curl_error($curl)) {
		die("probleme connexion curl");
	} else {
		if(defined("__DH_CA_NO_VALID_SSL__") && constant("__DH_CA_NO_VALID_SSL__") === true ) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		}

		$content = curl_exec($curl);

		// Check the return value of curl_exec(), too
		if ($content === false) {
			var_dump(curl_error($curl));
			die();
			throw new Exception(curl_error($curl), curl_errno($curl));
		}
		$result = json_decode($content, true);
		$result = $result["authToken"];
		curl_close ($curl);
	}

	return $result;
}

function getObjDetail($id, $authToken) {
	$url = __CA_SITE_PROTOCOL__."://".__DH_CA_WS_USER__.":".__DH_CA_WS_PASS__."@".__CA_SITE_HOSTNAME__.__CA_URL_ROOT__."/service.php/item/ca_objects/id/".$id."?pretty=1&authToken=".$authToken;
	//var_dump($url);
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	if(curl_error($curl)) {
		$result = false;
		die("probleme connexion curl");
	} else {
		if(defined("__DH_CA_NO_VALID_SSL__") && constant("__DH_CA_NO_VALID_SSL__") === true ) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		}		
		$result = json_decode(curl_exec($curl));
		//print $result ;
		//$result = json_decode(curl_exec ($curl), true);
		curl_close ($curl);
	}
	return $result;
}

function getOccurrenceDetails($id, $authToken) {
	$url = __CA_SITE_PROTOCOL__."://".__DH_CA_WS_USER__.":".__DH_CA_WS_PASS__."@".__CA_SITE_HOSTNAME__.__CA_URL_ROOT__."/service.php/item/ca_occurrences/id/".$id."?pretty=1&authToken=".$authToken;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	if(curl_error($curl)) {
		$result = false;
		die("probleme connexion curl");
	} else {
		if(defined("__DH_CA_NO_VALID_SSL__") && constant("__DH_CA_NO_VALID_SSL__") === true ) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		}
		//$result = json_decode(curl_exec($curl));
		//print $result ;
		$result = curl_exec ($curl);
		curl_close ($curl);
	}
	return $result;
}

function writeOccurrenceDetails($id, $json, $authToken) {
	$url = __CA_SITE_PROTOCOL__."://".__DH_CA_WS_USER__.":".__DH_CA_WS_PASS__."@".__CA_SITE_HOSTNAME__.__CA_URL_ROOT__."/service.php/item/ca_occurrences/id/".$id."?pretty=1&authToken=".$authToken;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	if($msg = curl_error($curl)) {
		$result = false;
		var_dump($msg);
		die("probleme connexion curl");
	} else {
		if(defined("__DH_CA_NO_VALID_SSL__") && constant("__DH_CA_NO_VALID_SSL__") === true ) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		}
		//$result = json_decode(curl_exec($curl));
		//print $result ;
		$result = curl_exec ($curl);
		curl_close ($curl);
	}
	return $result;
}


function getModelObject($type,$authToken){
	$url = __CA_SITE_PROTOCOL__."://".__DH_CA_WS_USER__.":".__DH_CA_WS_PASS__."@".__CA_SITE_HOSTNAME__.__CA_URL_ROOT__."/service.php/model/ca_objects?pretty=1&format=edit&authToken=".$authToken;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	if(curl_error($curl)) {
		$result = false;
		die("probleme connexion curl");
	} else {
		if(defined("__DH_CA_NO_VALID_SSL__") && constant("__DH_CA_NO_VALID_SSL__") === true ) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		}
		$result = json_decode(curl_exec($curl));
		//print $result ;
		//$result = json_decode(curl_exec ($curl), true);
		curl_close ($curl);
	}
	return $result->{$type};
}


/**
 * 
 * @param mixed $model : Model du type d'objet
 * @param mixed $metadata 
 * @param mixed $submetadata 
 * @return mixed 
 */
function getMetadataLabelFromModel($model,$metadata, $submetadata = null){
	if (!$submetadata){
		$label = $model->elements->{$metadata}->name;
	}
	if ($submetadata){
		$label = $model->elements->{$metadata}->elements_in_set->$submetadata->display_label;
	}
	
	return $label;
}

/**
 * 
 * Function that returns the object type idno 
 * 
 * @param int $type_id ID Of the Object Type
 * @param string $authToken Authentification token for the webservice
 * @return string  
 */
function getObjectTypeCode($type_id, $authToken){
	$url = __CA_SITE_PROTOCOL__."://".__DH_CA_WS_USER__.":".__DH_CA_WS_PASS__."@".__CA_SITE_HOSTNAME__.__CA_URL_ROOT__."/service.php/item/ca_list_items/id/".$type_id."?pretty=1&authToken=".$authToken;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	if(curl_error($curl)) {
		$result = false;
		die("probleme connexion curl");
	} else {
		if(defined("__DH_CA_NO_VALID_SSL__") && constant("__DH_CA_NO_VALID_SSL__") === true ) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		}
		$result = json_decode(curl_exec($curl));
		//print $result ;
		//$result = json_decode(curl_exec ($curl), true);
		curl_close ($curl);
	}
	
	return $result->intrinsic->idno;
}
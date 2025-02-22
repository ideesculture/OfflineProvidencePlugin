<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
require_once(__CA_MODELS_DIR__."/ca_objects.php");


/* SETTING */
// These need to be adapted to the current CollectiveAccess configuration
$nonpreferred_label_type = "alt";
$metadata_elements_with_html_editor_enabled = ["internal_notes"];

$globalModifications = json_decode($_POST["globalModifications"], 1);

$logFilePointer = fopen(__CA_BASE_DIR__."/app/plugins/OfflineProvidence/logs/sync_".date("Ymd").".log", "a");

function logSync($message, $logFilePointer) {
	fwrite($logFilePointer, date("Y-m-d H:i:s")." ".$message."\n");
	print nl2br($message."\n");
}

foreach($globalModifications as $type_with_id => $modifications) {

	// id is the last part of the type_with_id after the _
	$id = explode("_", $type_with_id)[1];
	print "<p>id: ".$id."</p>";

	$vt_object = new ca_objects($id);
	$vt_object->setMode(ACCESS_WRITE);

	foreach($modifications as $key=>$modification) {
		//var_dump($modification);
		switch($modification["kind"]) {
			case "N":
				// if $modification["path"] starts with anything than ca_objects. ignore it
				if(strpos($modification["path"], "ca_objects.") !== 0) { unset($modifications[$key]); continue;}

				// remove the ca_objects. prefix
				$modification["path"] = substr($modification["path"], 11);

				logSync("Ajout ".$modification["path"]." à l'objet <a href='/gestion/index.php/editor/objects/ObjectEditor/Summary/object_id/".$id."'>{$id}</a> : \"".$modification["rhs"]."\"", $logFilePointer);

				$vt_object->addAttribute([$modification["path"] => $modification["rhs"]], $modification["path"]);
				$vt_object->update();

				break;
			case "D":
				// if $modification["path"] starts with anything than ca_objects. ignore it
				if(strpos($modification["path"], "ca_objects.") !== 0) { unset($modifications[$key]); continue;}

				// remove the ca_objects. prefix
				$modification["path"] = substr($modification["path"], 11);
				logSync("Suppression ".$modification["path"]." de l'objet <a href='/gestion/index.php/editor/objects/ObjectEditor/Summary/object_id/".$id."'>{$id}</a>", $logFilePointer);

				// option force is used to remove the attribute even if one value is required
				$vt_object->removeAttributes($modification["path"], ["force"=>true]);
				$vt_object->update();

				break;
			case "AD":
				// if $modification["path"] starts with anything than ca_objects. ignore it
				if(strpos($modification["path"], "ca_objects.") !== 0) { unset($modifications[$key]); continue;}

				// remove the ca_objects. prefix
				$modification["path"] = explode(".", substr($modification["path"], 11));
				var_dump($modification);
				if(!$modification["rhs"]) {
					unset($modifications[$key]);
					continue;
				}
				//var_dump($modification);
				//die();
				break;
			case "AN": // new value in an array
				// if $modification["path"] starts with anything than ca_objects. ignore it
				if(strpos($modification["path"], "ca_objects.") !== 0) { unset($modifications[$key]); continue;}

				// remove the ca_objects. prefix
				$modification["path"] = explode(".", substr($modification["path"], 11));
				
				if(!$modification["rhs"]) {
					unset($modifications[$key]);
					continue;
				}
				$modification["rhs"] = json_decode($modification["rhs"], 1);

				// TODO : add a check to see if the value is already in the actual values of the object before adding it
				$vt_object->addAttribute($modification["rhs"], $modification["path"][0]);
				$vt_object->update();
				logSync("Ajout de valeur ".$modification["path"][0]." de l'objet <a href='/gestion/index.php/editor/objects/ObjectEditor/Summary/object_id/".$id."'>{$id}</a>, nouvelle valeur : \"".json_encode($modification["rhs"])."\"", $logFilePointer);
				//var_dump($modification);
				//die();
				break;
			case "E":
				// if $modification["path"] starts with anything than ca_objects. ignore it
				if(strpos($modification["path"], "ca_objects.") !== 0) { unset($modifications[$key]); continue;}
				
				// remove the ca_objects. prefix
				$modification["path"] = substr($modification["path"], 11);

				switch($modification["path"]) {
					case "nonpreferred_labels":
						logSync("Modification nonpreferred_labels de l'objet <a href='/gestion/index.php/editor/objects/ObjectEditor/Summary/object_id/".$id."'>{$id}</a>, nouvelle valeur : \"".$modification["rhs"]."\"", $logFilePointer);

						// edit is the modification of nonpreferred_labels, we need to remove the existing nonpreferred_labels and add the new ones
						$vt_object->removeAllLabels(__CA_LABEL_TYPE_NONPREFERRED__);
						$vt_object->update();
						$vt_object->addLabel(array("name"=>$modification["rhs"]), null, $nonpreferred_label_type, false);
						$vt_object->update();

					case "preferred_labels":
						logSync("Modification preferred_labels de l'objet <a href='/gestion/index.php/editor/objects/ObjectEditor/Summary/object_id/".$id."'>{$id}</a>, nouvelle valeur : \"".$modification["rhs"]."\"", $logFilePointer);

						// edit is the modification of preferred_labels, we need to remove the existing preferred_labels and add the new ones
						$vt_object->removeAllLabels(__CA_LABEL_TYPE_PREFERRED__);
						$vt_object->update();
						$vt_object->addLabel(array("name"=>$modification["rhs"]), null, null, true);
						$vt_object->update();

						break;
					default:
						// check if the edit value is inside an array
						$path = explode(".", $modification["path"]);
						if(count($path) > 1) {
							// if the path is an array, we need to add the value to the array
							logSync("Modification ".$path[0]." (répétable) de l'objet <a href='/gestion/index.php/editor/objects/ObjectEditor/Summary/object_id/".$id."'>{$id}</a> : \"".$modification["lhs"]."\" remplacé par \"".$modification["rhs"]."\"", $logFilePointer);

							// get current values of the array
							$current_values = $vt_object->get($path[0], ["returnAsArray"=>true]);

							// path $current_values to the right value
							$current_values[$path[1]] = $modification["rhs"];

							// remove the former values
							$vt_object->removeAttributes($path[0], ["force"=>true]);
							// add the patched values
							foreach($current_values as $key=>$value) {
								$vt_object->addAttribute([$path[0]=>$value], $path[0]);
							}
							$vt_object->update();
							break;
						}
						// if the path is not an array, we can directly add the value

						logSync("Modification ".$modification["path"]." de l'objet <a href='/gestion/index.php/editor/objects/ObjectEditor/Summary/object_id/".$id."'>{$id}</a>, nouvelle valeur : \"".$modification["rhs"]."\"", $logFilePointer);
						$vt_object->removeAttributes($modification["path"], ["force"=>true]);
						if(in_array($modification["path"], $metadata_elements_with_html_editor_enabled)) {
							$modification["rhs"] = htmlspecialchars(nl2br($modification["rhs"]));
						}
						$vt_object->addAttribute([$modification["path"]=>$modification["rhs"]], $modification["path"]);
						$vt_object->update();
						break;
				}
				break;
		}

		// basic error handling
		if($vt_object->numErrors()) {
			print "Error altering/adding/removing attributes ".$modification["path"]." from object ".$id;
			exit();
		} else {
			// modification handled, remove it
			unset($modifications[$key]);
		}
	}
	$globalModifications[$type_with_id] = $modifications;
	$vt_object->update();
}
//var_dump($globalModifications);
//print "<p>Problème durant la synchronisation, merci de contacter un administrateur.</p>";
fclose($logFilePointer);
?>


<?php

use Swaggest\JsonDiff\JsonDiff;

require_once __CA_MODELS_DIR__ . "/ca_objects.php";
require_once __CA_MODELS_DIR__ . "/ca_editor_uis.php";
require_once __CA_MODELS_DIR__ . "/ca_metadata_elements.php";
require_once __CA_MODELS_DIR__ . "/ca_attributes.php";
//require_once __CA_MODELS_DIR__ . "/ca_editor_ui_screens";
require_once __CA_APP_DIR__ . "/plugins/OfflineProvidence/lib/curl_ca_ws.php";
error_reporting(E_ERROR);
ini_set("display_errors", "on");


class StoreController extends ActionController
{
	private $opo_config;
	private $ps_plugin_path;
	private $authToken;
	# -------------------------------------------------------
	#
	# -------------------------------------------------------
	public function __construct(&$po_request, &$po_response, $pa_view_paths = null)
	{
		parent::__construct($po_request, $po_response, $pa_view_paths);

		// Global vars for all children views
		$this->view->setVar('plugin_dir', __CA_BASE_DIR__ . "/app/plugins/OfflineProvidence");
		$this->view->setVar('plugin_url', __CA_URL_ROOT__ . "/app/plugins/OfflineProvidence");

		$this->ps_plugin_path = __CA_BASE_DIR__ . "/app/plugins/OfflineProvidence";

		if (file_exists($this->ps_plugin_path . '/conf/local/OfflineProvidence.conf')) {
			$this->opo_config = Configuration::load($this->ps_plugin_path . '/conf/local/OfflineProvidence.conf');
		} else {
			$this->opo_config = Configuration::load($this->ps_plugin_path . '/conf/OfflineProvidence.conf');
		}
		$this->authToken = getAuthToken();
	}

	public function Populate() {
		// Download occurrence editor
		$editors = [];
		// Todo fetch for all editors
		$vt_editor = new ca_editor_uis(19);
		$editor_target_ref = "67_142_editor";
		$editors[$editor_target_ref] = [];
		// Récupération des écrans
		$screens = $vt_editor->getScreens();
		foreach($screens as $key=>$screen) {
			$vt_screen = new ca_editor_ui_screens($screen["screen_id"]);
			$editors[$editor_target_ref][$key]["label"] = $vt_screen->getLabelForDisplay();
			$editors[$editor_target_ref][$key]["placements"]= [];
			//var_dump($vt_scree);die();
			foreach($vt_screen->getPlacements() as $placement) {
				// les emplacements ont des réglages pour l'affichage, inutiles ici
				unset($placement["settingsForm"]);
				unset($placement["settings"]);
				unset($placement["display"]);
				// Si attribut, on définit le type de l'attribut, et l'ID de la metadata
				if(strpos($placement["bundle_name"],"ca_attribute_") === 0) {
					$placement["attribute"] = str_replace("ca_attribute_", "", $placement["bundle_name"]);
					$placement["type"] = "attribute";
					$vt_metadata_element = new ca_metadata_elements();
					$vt_metadata_element->load(["element_code"=>$placement["attribute"]]);
					$placement["metadata_id"] = $vt_metadata_element->getPrimaryKey();
					$datatype = $vt_metadata_element->get("datatype");
					$placement["datatype"] = ca_metadata_elements::getAttributeNameForTypeCode($datatype);
				}
				$editors[$editor_target_ref][$key]["placements"][$placement["placement_id"]] = $placement;
			}
		}
		$this->view->setVar("editors", $editors);

		$campagne_id = $this->getRequest()->getParameter("campagne_id", pInteger);
		$o_data = new Db();
		$query = "
		SELECT co.occurrence_id as id,
		co.type_id as type_id
		FROM ca_occurrences co 
		WHERE deleted =0 
		AND co.type_id=142
		LIMIT 1
 		";

		$qr_res = $o_data->query($query);
		
		$fileList = [];
		while ($qr_res->nextRow()) {
			$infos = getOccurrenceDetails($qr_res->get("id"), $this->authToken);
			file_put_contents($this->ps_plugin_path . "/json/67_" . $qr_res->get("type_id") ."_". $qr_res->get("id") . ".json", $infos);
			$fileList[] = $this->ps_plugin_path . "/json/67_" . $qr_res->get("type_id") ."_". $qr_res->get("id") . ".json";
			
		}
		//var_dump($fileList);die();
		$this->view->setVar("fileList", $fileList);
		$this->render("store_populate_html.php");
	}

	public function PopulateFromParent() {
		$parent_id = $this->getRequest()->getParameter("parent_id", pInteger);
		$o_data = new Db();

		// Children
		$query = "SELECT co.object_id as id, co.type_id as type_id, children.object_id as child_id, children.type_id as child_type_id
		FROM ca_objects co LEFT JOIN ca_objects children ON children.parent_id=co.object_id WHERE co.parent_id = ".$parent_id." AND co.deleted = 0";
		$qr_res = $o_data->query($query);
		$results = $qr_res->getAllRows();
		$fileList = [];
		foreach($results as $key=>$value) {
			$filename = $this->ps_plugin_path . "/json/objects/" . $value["child_type_id"] ."_". $value["child_id"] . ".json";
			$infos = getObjDetail($value["child_id"], $this->authToken);
			$result = file_put_contents($filename, $infos);

			$fileList[$value["child_id"]] = $filename;
			$filename = $this->ps_plugin_path . "/json/objects/" . $value["type_id"] ."_". $value["id"] . ".json";

			// Store the object metadata in a json
			$infos = getObjDetail($value["child_id"], $this->authToken);
			$result = file_put_contents($filename, $infos);
			$fileList[$value["id"]] = $filename;

			// Store the object representation in a json
			$representations = json_decode($infos, 1)["representations"];
			foreach($representations as $representation) {
				// Ignore non default representation
				if(!$representation["is_primary"]) continue;
				$filename_repr = $this->ps_plugin_path . "/json/representations/" . $representation["representation_id"] . ".json";
				// fetch the image at $representation["paths"]["preview170"] and base64 encode it
				$repr_url = $representation["urls"]["preview170"];
				// development mode url patching
				$repr_url = str_replace("acf.lescollections.test", "acf.lescollections.be", $repr_url);
				$repr_data = base64_encode(file_get_contents($repr_url));
				// store it in the json file
				file_put_contents($filename_repr, json_encode(["preview170"=>$repr_data]));
			}
			$fileList["repr_"+$representation["representation_id"]] = $filename_repr;
		}
		$this->view->setVar("fileList", $fileList);
		
		print $this->render("store_populate_html.php");
		exit();
	}

	public function Sync() {
		print $this->render("store_sync_html.php");
		exit();
	}

	public function Import() {
		require __DIR__ . '/../vendor/autoload.php';
		error_reporting(E_ERROR);
		ini_set("display_errors", true);
		$vn_tablenum = $this->request->getParameter("tablenum", pInteger);
		$vn_id = $this->request->getParameter("id", pInteger);

		$vs_json = $this->getRequest()->getParameter("json", pString, "POST");
		$vt_json = json_decode($vs_json, true);
		// ICI FAIRE LA COMPARAISON
		$vs_actual_ca_json = getOccurrenceDetails($vn_id, $this->authToken);
		$vt_ca_json = json_decode($vs_actual_ca_json, true);
		
		$r = new JsonDiff(json_decode($vs_json), json_decode($vs_actual_ca_json));
		var_dump($vs_json);
		var_dump($vs_actual_ca_json);
		var_dump($r);
		// ICI AFFICHER LE DELTA
		die();
	}
}


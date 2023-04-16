<?php

use Swaggest\JsonDiff\JsonDiff;

require_once __CA_MODELS_DIR__ . "/ca_objects.php";
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

	public function Populate()
	{
		$campagne_id = $this->getRequest()->getParameter("campagne_id", pInteger);
		$o_data = new Db();
		$query = "
		SELECT coo.occurrence_left_id as id 
		FROM ca_occurrences co 
		LEFT JOIN ca_occurrences_x_occurrences coo on co.occurrence_id = coo.occurrence_left_id 
		WHERE deleted =0 
		AND relation_id is not null 
		AND occurrence_right_id =$campagne_id
 		";

		$qr_res = $o_data->query($query);
		
		$fileList = [];
		while ($qr_res->nextRow()) {
			$infos = getOccurrenceDetails($qr_res->get("id"), $this->authToken);
			file_put_contents($this->ps_plugin_path . "/json/67_" . $qr_res->get("id") . ".json", $infos);
			$fileList[] = $this->ps_plugin_path . "/json/67_" . $qr_res->get("id") . ".json";
		}

		$this->view->setVar("fileList", $fileList);
		$this->render("store_populate_html.php");
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


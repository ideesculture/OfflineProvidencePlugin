<?php


class OfflineProvidencePlugin extends BaseApplicationPlugin
{
	# -------------------------------------------------------
	private $opo_config;
	private $ops_plugin_path;

	# -------------------------------------------------------
	public function __construct($ps_plugin_path)
	{
		//var_dump($ps_plugin_path . '/conf/local/museesDeFrance.conf');die();
		$this->ops_plugin_path = $ps_plugin_path;
		$this->description = _t('Fonctionnalités pour modifier des objets en hors connexion.');
		parent::__construct();
        $ps_plugin_path = __CA_BASE_DIR__ . "/app/plugins/OfflineProvidence";

        if (file_exists($ps_plugin_path . '/conf/local/OfflineProvidence.conf')) {
            $this->opo_config = Configuration::load($ps_plugin_path . '/conf/local/OfflineProvidence.conf');
        } else {
            $this->opo_config = Configuration::load($ps_plugin_path . '/conf/OfflineProvidence.conf');
        }
		//var_dump($this->opo_config);die();
	}
	# -------------------------------------------------------
	/**
	 * Insert into ObjectEditor info (side bar)
	 */
	public function hookAppendToEditorInspector(array $va_params = array())
	{

        MetaTagManager::addLink('stylesheet', __CA_URL_ROOT__."/app/plugins/OfflineProvidence/assets/OfflineProvidence.css",'text/css');
				$vs_script = file_get_contents(__CA_BASE_DIR__."/app/plugins/OfflineProvidence/assets/offline_sync.js");
				$vs_script=str_replace("__CA_URL_ROOT__", __CA_URL_ROOT__, $vs_script);
				AssetLoadManager::addComplementaryScript($vs_script);
				$t_item = $va_params["t_item"];

				// basic zero-level error detection
				if (!isset($t_item)) return false;
		
				// fetching content of already filled vs_buf_append to surcharge if present (cumulative plugins)
				if (isset($va_params["vs_buf_append"])) {
					$vs_buf = $va_params["vs_buf_append"];
				} else {
					$vs_buf = "";
				}
		
				$vs_table_name = $t_item->tableName();
				$vn_item_id = $t_item->getPrimaryKey();

				if ($vs_table_name == "ca_occurrences") {
					$vs_buf .= "<div id='offlineImportButton'>"
						."</div>";
					$vs_buf .= "<script>
							let offlineImportId = \"67_".$vn_item_id."\";
							if(localStorage.getItem(\"67_".$vn_item_id."\")!='') { 
								$('#offlineImportButton').html('<a>Importer offline</a>');
							}
						</script>";
					
				}
				

				$va_params["caEditorInspectorAppend"] = $vs_buf;

				#var_dump($va_params);die();
				return $va_params;				
	}
	# -------------------------------------------------------
	/**
	 * Override checkStatus() to return true - the ampasFrameImporterPlugin plugin always initializes ok
	 */
	public function checkStatus()
	{
		return array(
			'description' => $this->getDescription(),
			'errors' => array(),
			'warnings' => array(),
			'available' => ((bool)$this->opo_config->get('enabled'))
		);
	}

	# -------------------------------------------------------
	/**
	 * Get plugin user actions
	 */

	static public function getRoleActionList() {
		return array(
			'can_use_offline_providence' => array(
				'label' => "Can use OfflineProvidence plugin",
				'description' => "Can use OfflineProvidence plugin"
			),
		);
	}

	# -------------------------------------------------------
	/**
	 * Add plugin user actions
	 */
	public function hookGetRoleActionList($pa_role_list) {
		$pa_role_list['can_use_offline_providence'] = array(
			'label' => _t('Plugin OfflineProvidence'),
			'description' => _t('Actions pour le plugin OfflineProvidence'),
			'actions' => array()
		);

		return $pa_role_list;
	}
}

?>
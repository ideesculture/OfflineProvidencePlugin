<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
$fileList = $this->getVar("fileList");
$editors = $this->getVar("editors");
$editorsJson = json_encode($editors);
$fileListJson = json_encode($fileList);
$entityList = $this->getVar("entityList");
$entityListJson = json_encode($entityList);
$storageLocationList = $this->getVar("storageLocationList");
$storageLocationListJson = json_encode($storageLocationList);
?>
<div id="progression_container" style="width:100%;background:white;">
	<div id="progression" style="width:0%;background:#5cb4c8;height:10px"></div>
</div>
<script src='<?= __CA_URL_ROOT__ ?>/assets/jquery/jquery.js' type='text/javascript'></script>
<script src="<?= __CA_URL_ROOT__ ?>/app/plugins/OfflineProvidence/assets/localforage.js"></script>
<script src="<?= __CA_URL_ROOT__ ?>/app/plugins/OfflineProvidence/assets/dexie.js"></script>
<style>
	*, html, body {
		margin: 0;
		padding: 0;
	}	
	</style>
<script>

	var db = new Dexie('collectiveaccessOffline');
	db.version(1).stores({ settings: 'id, type, table, data' });
	db.version(2).stores({db_objects: 'id,idno,data' });
	db.version(3).stores({ db_entities: 'id,idno,data' });
	db.version(4).stores({ db_collections: 'id,idno,data' });
	db.version(5).stores({ db_places: 'id,idno,data' });
	db.version(6).stores({ db_occurrences: 'id,idno,data' });
	db.version(7).stores({ db_storage_locations: 'id,idno,data' });
	db.version(8).stores({ db_list_items: 'id,idno,data' });
	db.version(9).stores({ db_lists: 'id,idno,data' });
	db.version(10).stores({ db_lists: 'id,list_code,data' });
	db.version(11).stores({ db_object_representations: 'id,data' });
	db.version(12).stores({ db_objects: 'id,idno,data,edit,_edit' });
	// adding labels for search speed increase
	db.version(13).stores({ db_entities: 'id,idno,label,data' });
	db.version(14).stores({ db_storage_locations: 'id,idno,label,data' });
	db.version(15).stores({ db_places: 'id,idno,label,data' });

    $(document).ready(function() {
		$("#progression").css("width", "5%");
        var fileListJson = <?= $fileListJson ?>;
		var editorsJson = <?= $editorsJson ?>;
		var entityListJson = <?= $entityListJson ?>;
		var storageLocationListJson = <?= $storageLocationListJson ?>;

		// remove all content from db.db_objects
		db.db_objects.clear();
		db.db_object_representations.clear();

		// loop through all files
		var elements = Object.keys(fileListJson).length;
		let i = 0;
        for (const id in fileListJson) {
			console.log("id", id);
			if(fileListJson[id]) {
				$.ajax("<?= __CA_URL_ROOT__ ?>" + fileListJson[id].replace("<?= __CA_BASE_DIR__ ?>", ""), {
					type: 'get',
					dataType: "json",
					cache: false,
					success: function(data) {
						// if filename contains /objects/
						if(fileListJson[id].includes("/objects/")) {
							let fileName = fileListJson[id].replace("<?= __CA_APP_DIR__ ?>/plugins/OfflineProvidence/json/objects/", "").replace(".json", "");
							db.db_objects.put({id: fileName, idno: data.idno.value, data: data});
							$("#progression").css("width", (i / elements) * 100 + "%");
						}
						if(fileListJson[id].includes("/representations/")) {
							let fileName = fileListJson[id].replace("<?= __CA_APP_DIR__ ?>/plugins/OfflineProvidence/json/representations/", "").replace(".json", "");
							db.db_object_representations.put({id: fileName, data: data});
							$("#progression").css("width", (i / elements) * 100 + "%");
						}
					},
					error: function(error){
						console.log("error", error);
					}
				})
			}
			i++;
        }

		// remove all content from db.db_entities
		db.db_entities.clear();
		for (const id in entityListJson) {
			console.log("id", id);
			if(entityListJson[id]) {
				let entity = entityListJson[id];
				db.db_entities.put({id: entity["entity_id"], idno: entity["idno"], label: entity["displayname"], data: {displayname: entity["displayname"]}});
			}
		}

		// remove all content from db.db_storage_locations
		db.db_storage_locations.clear();
		for (const id in storageLocationListJson) {
			console.log("id", id);
			if(storageLocationListJson[id]) {
				let storageLocation = storageLocationListJson[id];
				db.db_storage_locations.put({id: storageLocation["location_id"], idno: storageLocation["idno"], label: storageLocation["name"], data: {name: storageLocation["name"], parent_id: storageLocation["parent_id"]}});
			}
		}

    });
</script>
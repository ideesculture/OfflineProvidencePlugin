<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
$fileList = $this->getVar("fileList");
$editors = $this->getVar("editors");
$editorsJson = json_encode($editors);
$fileListJson = json_encode($fileList);
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

    $(document).ready(function() {

        var fileListJson = <?= $fileListJson ?>;
		var editorsJson = <?= $editorsJson ?>;
		
		var elements = Object.keys(fileListJson).length;
		let i = 0;
        for (const id in fileListJson) {
			//console.log("id", id);
            $.ajax("<?= __CA_URL_ROOT__ ?>" + fileListJson[id].replace("<?= __CA_BASE_DIR__ ?>", ""), {
                type: 'get',
                dataType: "json",
                cache: false,
                success: function(data) {
                    let fileName = fileListJson[id].replace("<?= __CA_APP_DIR__ ?>/plugins/OfflineProvidence/json/objects/", "").replace(".json", "");
					db.db_objects.put({id: fileName, idno: data.idno.value, data: data});
					$("#progression").css("width", (i / elements) * 100 + "%");

					
                },
                error: function(error){
                    console.log("error", error);
                }
            })
			i++;
        }


    });
</script>
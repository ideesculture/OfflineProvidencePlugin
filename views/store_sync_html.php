<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
?>
<div id="progression_container" style="width:100%;background:white;">
	<div id="progression" style="width:0%;background:#5cb4c8;height:10px"></div>
</div>
<script src='<?= __CA_URL_ROOT__ ?>/assets/jquery/jquery.js' type='text/javascript'></script>
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

    $(document).ready(function() {
		console.log("starting sync...");
		// get a list of all db.db_objects
		db.db_objects.toArray().then(function(objects) {
			console.log("objects", objects);
		});


    });
</script>
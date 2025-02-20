<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
?>
<div id="progression_container" style="width:100%;background:white;">
	<div id="progression" style="width:0%;background:#5cb4c8;height:10px"></div>
</div>
<div id="results"></div>
<script src='<?= __CA_URL_ROOT__ ?>/assets/jquery/jquery.js' type='text/javascript'></script>
<script src="<?= __CA_URL_ROOT__ ?>/app/plugins/OfflineProvidence/assets/dexie.js"></script>

<!-- require DeepDiff -->
<script src="<?= __CA_URL_ROOT__ ?>/app/plugins/OfflineProvidence/assets/deep-diff.min.js"></script>
<style>
	*, html, body {
		margin: 0;
		padding: 0;
		font-family: Geneva, Arial, Helvetica, sans-serif;
		font-size:13px;
	}	
	</style>
<script>
	async function countDbObjects() {
		let count = await db.db_objects.count();
		console.log("count", count);
		return count;
	}

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

	console.log("starting sync...");
	// get the size of db.db_objects
	let count = countDbObjects();
	console.log("count", count);
	var i = 0;
	var globalModifications = {};
	// get a list of all db.db_objects
	db.db_objects.each(async function(obj) {
		// check if the object has been edited, and contains an edit field
		if(obj.edit) {
			// check if there is a corresponding _edit field
			if(obj._edit) {
				let modifications = DeepDiff(obj.edit, obj._edit);

				if(modifications) {
					globalModifications[obj.id] = [];
					//loop through all keys of modifications
					for (var key in modifications) {
						// push modifications to display
						if(modifications[key].kind == "A") {
							// Array
							//this.modifications.push({kind:modifications[key].kind, path:modifications[key].path, lhs:modifications[key].lhs, rhs:modifications[key].rhs});
							console.log("Array", modifications[key].item);
							globalModifications[obj.id].push({kind:"A"+modifications[key].item.kind, path:modifications[key].path.join(".")+"."+modifications[key].index, lhs:"", rhs:JSON.stringify(modifications[key].item.rhs)});
						} else {
							globalModifications[obj.id].push({kind:modifications[key].kind, path:modifications[key].path.join("."), lhs:modifications[key].lhs, rhs:modifications[key].rhs});
						}
						
					}
					console.log("modifications", this.modifications);
				} else {
					this.modifications = [];
				}
			}
		}

		// Progression bar
		i++;
		let tempCount = await count;
		$("#progression").css("width", Math.round(i / tempCount) * 100 + "%");
	}).then(function() {
		console.log("done");
		console.log(globalModifications);
		// count objects modified
		let count = Object.keys(globalModifications).length;
		if(count >0) {
			// display modifications
			$("#results").html(count + " objets modifiés<br/>Vous devez regénérer le cache.");

		} else {
			$("#results").html("Aucune modification");
		}

	});

</script>
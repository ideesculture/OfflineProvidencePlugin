<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
$fileList = $this->getVar("fileList");
$fileListJson = json_encode($fileList);
?>
<h1>
	<?= _t("OfflineProvidence") ?>
</h1>
<p>Remplissage du cache local terminé.</p>
<script src="<?= __CA_URL_ROOT__ ?>/app/plugins/OfflineProvidence/assets/localforage.js"></script>
<script>
	var store = localforage.createInstance({
		name: "CollectiveAccess"
	});

    $(document).ready(function() {

        var fileListJson = <?= $fileListJson ?>;
        for (const id in fileListJson) {

            $.ajax("<?= __CA_URL_ROOT__ ?>" + fileListJson[id].replace("<?= __CA_BASE_DIR__ ?>", ""), {
                type: 'get',
                dataType: "json",
                cache: false,
                success: function(data) {
                    let fileName = fileListJson[id].replace("<?= __CA_APP_DIR__ ?>/plugins/OfflineProvidence/json/", "").replace(".json", "");
                    store.setItem(fileName, data);
                },
                error: function(error){
                    console.log("error", error);
                }
            })
        }


    });
</script>
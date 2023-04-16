<?php

$fileList = $this->getVar("fileList");
$fileListJson = json_encode($fileList);

?>
<h1>
	<?= _t("OfflineProvidence") ?>
</h1>
<p>Remplissage du cache local terminé.</p>
<script>
    $(document).ready(function() {

        var fileListJson = <?= $fileListJson ?>;
        for (const id in fileListJson) {

            $.ajax("/gestion" + fileListJson[id].replace("<?= __CA_BASE_DIR__ ?>", ""), {
                type: 'get',
                dataType: "json",
                cache: false,
                success: function(data) {
                    let fileName = fileListJson[id].replace("<?= __CA_APP_DIR__ ?>/plugins/OfflineProvidence/json/", "").replace(".json", "");
                    console.log(data);
                    localStorage.setItem(fileName, JSON.stringify(data));
                },
                error: function(error){
                    console.log("error", error);
                }
            })
        }


    });
</script>
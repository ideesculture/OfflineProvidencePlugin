<?php
 	$po_request 			= $this->getVar('request');
	$va_instances 			= $this->getVar('instances');
	$va_settings 			= $this->getVar('settings');
	$vs_widget_id 			= $this->getVar('widget_id');

	if($this->request->user->get("user_id") != 1) {
		$fabrique = $this->request->user->get("sms_number");
		require_once(__CA_LIB_DIR__."/Search/ObjectSearch.php");
		$o_search = new ObjectSearch(); //instantiate a new search object
		$qr_hits = $o_search->search("parent_id:".$fabrique);
		//$qr_hits = $o_search->search("*");
		print "<script>console.log('Requête = idno:".$fabrique."');</script>";
		$count=0;
		while($qr_hits->nextHit()) {
			if ($qr_hits->get('ca_objects.type_id') != 23 ) continue;
			$fabrique_label = $qr_hits->get('ca_objects.preferred_labels.name');
			$fabrique_id = $qr_hits->get('ca_objects.object_id');
			$fabrique_idno = $qr_hits->get('ca_objects.idno');
		}
	}
?>

<div class="dashboardWidgetContentContainer" style="font-size:13px; padding-right:10px;">
	<div id="onlineW">
		<p>🟢 en ligne — <a href="/offline" target="_blank">Saisie hors connexion</a></p>
	</div>
	<div id="offlineW" style="display:none;">
		<p>🔴 Vous êtes en mode hors connexion - <a href="/offline" target="_blank">Saisie hors connexion</a></p>
	</div>

	<p><?= $fabrique_label ?></p>
<div id="cache_creation">
	Création du cache<br/>
	<button id="CreerLeCache" onclick="CreerLeCache()">Créer le cache</button>
</div>
<div id="synchro" style="margin-top:8px">
	Synchronisation du mode offline<br/>
	<iframe src="<?= __CA_URL_ROOT__ ?>/index.php/OfflineProvidence/Store/Sync" style="height:auto;width:100%;border:none;"></iframe>
</div>
<?php

?>  
	<iframe src="/offline/" style="height:10px;width:10px;float:right;margin-top:-8px;opacity:0.1"></iframe>
	<script>
		window.onoffline = function() {
    	$("#onlineW").hide();
			$("#offlineW").show();
		}
		window.ononline = function() {
    	$("#onlineW").show();
			$("#offlineW").hide();
		}

		function CreerLeCache() {
			alert("Le cache 'offline' va maintenant se créer. Cette opération peut prendre un certain temps. Ne pas naviguer");
			$("#CreerLeCache").hide();
			$("#synchro").hide();
			$('#cache_creation').append("<img src='<?= __CA_URL_ROOT__ ?>/app/plugins/OfflineProvidence/assets/loading-spinning.gif'>");
			//$('#cache_creation_iframe').attr('src', $('#cache_creation_iframe').attr('loading'));
			$.ajax({
				url: "<?= __CA_URL_ROOT__ ?>/index.php/OfflineProvidence/Store/PopulateFromParent/parent_id/<?= $fabrique_id ?>",
				success: function(data) {
					$('#cache_creation').find("img").remove();
					alert("Le cache a été créé avec succès.");
					$("#synchro").show();
				}
				
			});
		}
	</script>
</div>
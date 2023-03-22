<?php
 	$po_request 			= $this->getVar('request');
	$va_instances 			= $this->getVar('instances');
	$va_settings 			= $this->getVar('settings');
	$vs_widget_id 			= $this->getVar('widget_id');
?>

<div class="dashboardWidgetContentContainer" style="font-size:13px; padding-right:10px;">
	<div id="onlineW">
		<p>🟢 en ligne</p>
		<p><a href="/offline" target="_blank"><button>Saisie hors connexion</button></a></p>
	</div>
	<div id="offlineW" style="display:none;">
		<p>🔴 Vous êtes en mode hors connexion.</p>
		<p><a href="/offline" target="_blank"><button>Saisie hors connexion</button></a></p>
	</div>
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
	</script>
</div>
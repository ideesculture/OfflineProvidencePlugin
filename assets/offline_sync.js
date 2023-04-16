$(document).ready(
	setTimeout(function() {
		$("#widgets").on("click", "#offlineImportButton", function() {
			let text;
			if (confirm("Voulez vous remplacer par la notice Offline modifiée ?") == true) {
				console.log(offlineImportId);
				let json = localStorage.getItem(offlineImportId);
				console.log(json);
				offlineImportId = offlineImportId.split("_");
				$("body").append($("<form id='offlineImportForm' style='position:absolute;top:60px;left:0;z-index:10000;padding:20px;background:white;' method='POST' action='__CA_URL_ROOT__/index.php/OfflineProvidence/Store/Import/tablenum/"+offlineImportId[0]+"/id/"+offlineImportId[1]+"'><textarea id='offlineImportJson' name='json'></textarea><button type='submit'>SEND</button></form>"))
				$("#offlineImportJson").text(json);
				//$("#offlineImportForm").submit();
			}
		})
	},100)
);
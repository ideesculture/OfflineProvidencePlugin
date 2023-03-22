<template>
  <div id="content">Content</div>
</template>

<script>
import { ref } from 'vue'
import $ from 'jquery'

export default {
  mounted() {
    for (var i = 0; i < localStorage.length; i++){
    	$('#content').append(localStorage.getItem(localStorage.key(i)));
		}
		$.each(localStorage, function(key, value){
			$('#content').append(key);
		});
  }
}

const count = ref(0)
</script>

<style scoped>
.read-the-docs {
  color: #888;
}
#content {
	clear:both;
	text-align: left;
	margin:auto;
	width:1000px;
}
</style>

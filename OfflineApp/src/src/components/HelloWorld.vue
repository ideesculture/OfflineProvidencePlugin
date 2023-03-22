<template>
  <div id="content">Content</div>
</template>

<script>
import { ref } from 'vue'
import $ from 'jquery'

export default {
  mounted() {
		$('#content').append($("<ul></ul>"));
		$.each(localStorage, function(key, value){
			if(/[0-9]+_[0-9]+/.test(key)) {
				$('#content ul').append($("<li>"+key+"</li>"));
			}
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

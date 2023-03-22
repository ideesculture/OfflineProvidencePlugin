<template>
  <div id="content"></div>
</template>

<script>
import { ref } from 'vue'
import $ from 'jquery'
import useLocalStorage from '../useLocalStorage'

export default {
  mounted() {
		$('#content').append($("<ul></ul>"));
		$.each(localStorage, function(key, value){
			if(/[0-9]+_[0-9]+/.test(key)) {
				$('#content ul').append($("<a href='/offline/edit/"+key+"'><li>"+key+"</li></a>"));
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

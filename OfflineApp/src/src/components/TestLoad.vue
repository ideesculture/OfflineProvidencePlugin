<template>
  <div id="content">TestLoad</div>
</template>

<script>
import { ref } from 'vue'
//import $ from 'jquery'

export default {
	data() {
		return {
			defaultdata : "{\"50_530\":{\"idno\":\"test\"}, \"50_531\":{\"idno\":\"test\"}, \"50_532\":{\"idno\":\"test\"}}",
		}
	},
  mounted() {
		console.log(this.defaultdata);
		var data = JSON.parse(this.defaultdata);
		Object.keys(data).forEach(function (k) {
			localStorage.setItem(k, JSON.stringify(data[k]));
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

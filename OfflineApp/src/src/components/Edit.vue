<template>
  <div id="edit">
		<p>Modification de <span v-html="id"></span></p>
		<textarea v-model="name">
			
		</textarea>

	</div>
</template>

<script>
import { ref } from 'vue'
//import $ from 'jquery'

export default {
	data() {
		return {
			name : "",
			id : this.$route.params.id
		}
	},
  mounted() {
		console.log();
		let thisname = this.$route.params.id;
  	if (localStorage[thisname]) {
      this.name = localStorage[thisname];
    }
  },
  watch: {
    name(newName) {
			let thisname = this.$route.params.id;
      localStorage[thisname] = newName;
    }
  }
}

const count = ref(0)
</script>

<style lang="scss" scoped>
#edit {
	clear:both;
	text-align: left;
	margin:auto;
	max-width:1000px;
	padding:8px;
	textarea {
		width:calc(100% - 28px);
		min-height:1000px;
	}
}
</style>

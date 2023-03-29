<template>
  <div id="edit">
		<p>Modification de <span v-html="id"></span></p>
		<textarea v-model="name">
			
		</textarea>
		<div id="additions" v-html="additions"></div>
		<div id="modifications" v-html="modifications"></div>
		<div id="deletions" v-html="deletions"></div>
		<div id="diffs" v-html="diffs"></div>
	</div>
</template>

<script>
import { ref } from 'vue'
//import $ from 'jquery'
import { diff, addedDiff, deletedDiff, updatedDiff, detailedDiff } from 'deep-object-diff';

export default {
	data() {
		return {
			name : "",
			id : this.$route.params.id,
			additions: "",
			deletions: "",
			modifications: "",
			diffs: ""
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
      //localStorage[thisname] = newName;
			//this.diffs = JSON.stringify(diff(JSON.parse(newName), JSON.parse(localStorage[thisname])));
			this.additions = JSON.stringify(addedDiff(JSON.parse(localStorage[thisname]), JSON.parse(newName)));
			this.modifications = JSON.stringify(updatedDiff(JSON.parse(localStorage[thisname]), JSON.parse(newName)));
			this.deletions = JSON.stringify(deletedDiff(JSON.parse(localStorage[thisname]), JSON.parse(newName)));
			this.diffs = JSON.stringify(detailedDiff(JSON.parse(localStorage[thisname]), JSON.parse(newName)));

    }
  },
	methods: {
    submit(_e) {
      alert(JSON.stringify(this.model));
    },
    reset() {
      this.$refs.JsonEditor.reset();
    },
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
		min-height:300px;
	}
}
#additions {
	background:lightgreen;
}
#modifications {
	background: lightblue;
}
#deletions {
	background: red;
}
</style>

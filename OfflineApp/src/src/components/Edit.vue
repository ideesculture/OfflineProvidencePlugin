<template>
  <div id="edit">
		<p>Modification de <span v-html="id"></span></p>
		<FormKit type="form" v-model="data" @submit="register">
    	<FormKitSchema :schema="schema" :data="data" />
  	</FormKit>
  	<pre wrap>{{ data }}</pre>
<!--		<div id="additions" v-html="additions"></div>
		<div id="modifications" v-html="modifications"></div>
		<div id="deletions" v-html="deletions"></div>
		<div id="diffs" v-html="diffs"></div> -->
		<pre>
			{{ original }}
		</pre>
	</div>
</template>

<script>
/*
TODO : Utiliser les deep-references pour ne modifier que
 certains points de l'arbre de l'occurrence en mémoire
 https://formkit.com/essentials/schema#deep-references
*/

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
			diffs: "",
			data: {},
			original: {},
			titre: "titre",
			schema: [
        {
          $formkit: 'text',
          name: 'Titre',
          label: 'Titre',
          validation: 'required'
        },
				{
          $formkit: 'text',
          name: 'idno',
          label: 'Référence',
          help: 'Référence',
          validation: 'required'
        }
      ]
		}
	},
  mounted() {
		console.log();
		let thisname = this.$route.params.id;
  	if (localStorage[thisname]) {
      this.name = localStorage[thisname];
			this.original = JSON.parse(this.name);
    }
		this.data.Titre = this.original.preferred_labels.fr_FR[0].name;
		this.data.idno = this.original.idno.value;
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
		async register() {
      await new Promise((r) => setTimeout(r, 2000))
      alert('Account created!')
    }
  }
}
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

<template>
  <div id="edit">
		<p>Modification de <span v-html="id"></span></p>
		<FormKit type="form" v-model="data" @submit="register">
    	<FormKitSchema :schema="schema" :data="data" />
  	</FormKit>
  	<pre wrap>{{ data }}</pre>
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
        },
				{
					$el: 'hr', // try an h1!
					attrs: {
						class: 'ruler'
					}
				},
				{
          $formkit: 'checkbox',
          name: 'numinventaire_expertise_numinventaire_marque_etiquette',
          label: "Numéro d'inventaire marqué sur l'étiquette"
        },
				{
          $formkit: 'checkbox',
          name: 'numinventaire_expertise_numinventaire_marque_objet',
          label: "Numéro d'inventaire marqué sur l'objet"
        },
				{
          $formkit: 'checkbox',
          name: 'numinventaire_expertise_numinventaire_marque_support',
          label: "Numéro d'inventaire marqué sur un autre support"
        },
				{
          $formkit: 'checkbox',
          name: 'numinventaire_expertise_numinventaire_nonid',
          label: "Numéro d'inventaire non identifiable"
        },
				{
					$el: 'hr', // try an h1!
					attrs: {
						class: 'ruler'
					}
				},
				{
          $formkit: 'checkbox',
          name: 'occurrence_media_occurrence_photo_existante',
          label: "Numéro d'inventaire marqué sur l'étiquette"
        },
				{
          $formkit: 'checkbox',
          name: 'occurrence_media_occurrence_photo_marques',
          label: "Numéro d'inventaire marqué sur l'objet"
        },
				{
          $formkit: 'checkbox',
          name: 'occurrence_media_occurrence_photo_numinventaire',
          label: "Numéro d'inventaire marqué sur un autre support"
        },
				{
          $formkit: 'checkbox',
          name: 'occurrence_media_occurrence_photo_recolement',
          label: "Numéro d'inventaire non identifiable"
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
		this.data.numinventaire_expertise_numinventaire_marque_etiquette = (this.original["ca_occurrences.numinventaire_expertise"][648135].fr_FR.numinventaire_marque_etiquette == "Oui");
		this.data.numinventaire_expertise_numinventaire_marque_objet = (this.original["ca_occurrences.numinventaire_expertise"][648135].fr_FR.numinventaire_marque_objet == "Oui");
		this.data.numinventaire_expertise_numinventaire_marque_support = (this.original["ca_occurrences.numinventaire_expertise"][648135].fr_FR.numinventaire_marque_support == "Oui");
		this.data.numinventaire_expertise_numinventaire_nonid = (this.original["ca_occurrences.numinventaire_expertise"][648135].fr_FR.numinventaire_nonid == "Oui");

		this.data.occurrence_media_occurrence_photo_existante = (this.original["ca_occurrences.occurrence_media"][648202].fr_FR.occurrence_photo_existante == "Oui");
		this.data.occurrence_media_occurrence_photo_marques = (this.original["ca_occurrences.occurrence_media"][648202].fr_FR.occurrence_photo_marques == "Oui");
		this.data.occurrence_media_occurrence_photo_numinventaire = (this.original["ca_occurrences.occurrence_media"][648202].fr_FR.occurrence_photo_numinventaire == "Oui");
		this.data.occurrence_media_occurrence_photo_recolement = (this.original["ca_occurrences.occurrence_media"][648202].fr_FR.occurrence_photo_recolement == "Oui");
  },
  watch: {
    name(newName) {
			let thisname = this.$route.params.id;
      localStorage[thisname] = newName;
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

<style lang="scss">
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
hr.ruler {
	border-bottom: 1px solid rgba(0,0,0,0.1);
	margin:30px 100px 30px 100px;
}
</style>

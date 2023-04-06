<template>
	<div id="edit">
		<p>Modification de <span v-html="id"></span></p>
		<FormKit type="form" v-model="data" @submit="register">
			<FormKitSchema :schema="schema" :data="data" />
		</FormKit>
		<pre wrap>{{ data }}</pre>
		<pre>{{ original }}</pre>
	</div>
</template>

<script>
/*
TODO : Utiliser les deep-references pour ne modifier que
 certains points de l'arbre de l'occurrence en mémoire
 https://formkit.com/essentials/schema#deep-references
*/
function showStep() {
  console.log("showStep");
}

const randomColor = (e) => {
  const hex = Math
    .floor(Math.random()*16777215)
    .toString(16)
  e.target.setAttribute(
    'style',
    'background-color: #' + hex
  )
}

//import $ from 'jquery'
import { diff, addedDiff, deletedDiff, updatedDiff, detailedDiff } from 'deep-object-diff';

export default {
	data() {
		return {
			name: "",
			id: this.$route.params.id,
			additions: "",
			deletions: "",
			modifications: "",
			diffs: "",
			data: {},
			original: {},
			titre: "titre",
			schema:
				[{
					$el: "div",
					type: "group",
					attrs: {
						class: "steps"
					},
					children: [
						{
							$el: 'h2',
							attrs: {
								class: "step1"
								
							},
							
							children: "Récolement - identification"
						},{
							$el: 'h2',
							attrs: {
								class: "step2"
							},
							children: "Récolement de l'objet"
						},{
							$el: 'h2',
							attrs: {
								class: "step3"
							},
							children: "Suites à donner"
						}
					]
				},{
					$el: "div",
					type: "group",
					attrs: {
						class: "step step1",
						"data-stepnum": "step1"
					},
					children: [
						
					]
				},{
					$el: "div",
					type: "group",
					attrs: {
						class: "step step2",
						"data-stepnum": "step2"
					},
					children: [
						{
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									children: "Objet lié"
								},
								{
									$formkit: 'text',
									name: 'Objet lié',
									disabled: 'disabled',
									value: "Le taureau camargue (Pesca-Luno) et poème en son honneur par Pierre PERRIN. 986.978 (est le récolement de)"
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									children: "Photographie spécifique au récolement"
								},
								{
									$formkit: 'file',
									accept: ".jpg",
									help: "Choisir un fichier",
									multiple: true
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									children: "Photographie documentaire"
								},
								{
									$formkit: 'checkbox',
									name: 'occurrence_media_occurrence_photo_existante',
									label: "Photographie existante"
								},
								{
									$formkit: 'checkbox',
									name: 'occurrence_media_occurrence_photo_marques',
									label: "Photographies des marques"
								},
								{
									$formkit: 'checkbox',
									name: 'occurrence_media_occurrence_photo_numinventaire',
									label: "Photographie du / des numéros d'inventaire"
								},
								{
									$formkit: 'checkbox',
									name: 'occurrence_media_occurrence_photo_recolement',
									label: "Photographie réalisée lors du récolement"
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									children: "Numéro de fiche du récolement précédent"
								},
								{
									$formkit: 'text',
									name: 'idno_recolement_precedent',
									validation: 'required'
								}
								]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									children: "N° de fiche de récolement"
								},

								{
									$formkit: 'text',
									name: 'idno',
									validation: 'required'
								}
								]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									children: "Nom de l'objet récolé"
								},
								{
									$formkit: 'text',
									name: 'nom_objet_recole',
									validation: 'required'
								}
								]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[
									{
										$el: 'p',
										children: "Présence du numéro d'inventaire"
									}, {
										$formkit: 'checkbox',
										name: 'nom_objet_recole',
										label: "Présence du numéro d'inventaire"
									}, {
										$formkit: 'text',
										name: 'releve_numinventaire',
										label: "Relevé du numéro d'inventaire",
										validation: 'required'
									}
								]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[
									{
										$el: 'p',
										attrs: { class: 'ruler' },
										children: "Expertise du numéro d'inventaire"
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
									}
								]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[
									{
										$el: 'p',
										attrs: { class: 'ruler' },
										children: "Présence d'un autre numéro"
									},
									{
										$formkit: 'checkbox',
										name: 'presence_autre_numero',
										label: "Présence d'un autre numéro"
									}, {
										$formkit: 'text',
										name: 'releve_numinventaire',
										label: "Relevé du numéro d'inventaire",
										validation: 'required'
									}, {
										$formkit: 'text',
										name: 'Relevé du numéro',
										label: "Relevé du numéro"
									}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									children: "Problème d'identification"
								}, {
									$formkit: 'checkbox',
									name: 'probleme_identification',
									label: "Problème d'identification"
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									children: "Présence du bien dans le récolement précédent"
								}, {
									$formkit: 'checkbox',
									name: 'presence_bien_recolement_precedent',
									label: "Présence du bien dans le récolement précédent"
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									children: "Présence du bien dans le récolement en cours"
								}, {
									$formkit: 'checkbox',
									name: 'presence_bien_recolement_en_cours',
									label: "Vu"
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									children: "Date du précédent récolement"
								}, {
									$formkit: 'date',
									name: 'date_precedent_recolement',
									label: "Date du précédent récolement"
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									children: "Date du récolement en cours"
								}, {
									$formkit: 'date',
									name: 'date_precedent_recolement',
									label: "Date du récolement en cours"
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[
									{
										$el: 'p',
										attrs: { class: 'ruler' },
										children: "Localisation dans le précédent récolement"
									}, {
										$formkit: 'date',
										name: 'date_visualisation_precedent_recolement',
										label: "Date de visualisation"
									}, {
										$formkit: 'select',
										name: 'localisation_precedent_recolement',
										label: "Localisation",
										options: ["loc1", "loc2", "loc3"]
									}
								]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[
									{
										$el: 'p',
										attrs: { class: 'ruler' },
										children: "Emplacements liés"
									}, {
										$formkit: 'text',
										name: 'Emplacements liés',
										disabled: 'disabled',
										value: "Salle 3"
									}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									attrs: { class: 'ruler' },
									children: "Campagne liée"
								},
								{
									$formkit: 'text',
									name: 'Campagne liée',
									disabled: 'disabled',
									value: "Campagne n°1 – arts graphiques R4-1 (est lié à)"
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									attrs: { class: 'ruler' },
									children: "Date de visualisation"
								}, {
									$formkit: 'date',
									name: 'date_visualisation_precedent_recolement',
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{
									$el: 'p',
									attrs: { class: 'ruler' },
									children: "Date de validation"
								}, {
									$formkit: 'date',
									name: 'date_visualisation_precedent_recolement',
									label: "Date de validation sur pièce et sur place"
								}, {
									$formkit: 'date',
									name: 'date_visualisation_precedent_recolement',
									label: "Date de validation définitive"
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{ $el: 'p', children: "Valideur" }, {
									$formkit: 'text',
									name: 'Valideur'
								}]
						}, {
							$el: "div",
							type: "group",
							attrs: {
								class: "container"
							},
							children:
								[{ $el: 'p', children: "Récolement fait" }, {
									$formkit: 'checkbox',
									name: 'Récolement fait',
									label: "Récolement fait"
								}]
						}

					]
				},{
					$el: "div",
					type: "group",
					attrs: {
						class: "step step3",
						"data-stepnum": "step3"
					},
					children: [
						
					]
				}]
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
		this.data.step1 = false;
		this.data.step2 = true;
		this.data.step3 = false;
		let h2s = document.querySelectorAll("h2");
		h2s.forEach(function(h2){
			h2.addEventListener('click', function(e) {
				let steps = document.querySelectorAll("div.step");
				steps.forEach(function(step){
					step.hidden = true;
				});
				let stepnum = e.srcElement.className;
				console.log(stepnum);
				document.querySelector("div."+stepnum).hidden = false;
			});
		});
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
	clear: both;
	text-align: left;
	margin: auto;
	max-width: 1000px;
	padding: 8px;

	textarea {
		width: calc(100% - 28px);
		min-height: 300px;
	}
}

#additions {
	background: lightgreen;
}

#modifications {
	background: lightblue;
}

#deletions {
	background: red;
}

hr.ruler {
	border-bottom: 1px solid rgba(0, 0, 0, 0.1);
	margin: 30px 100px 30px 100px;
}

.formkit-wrapper,
.formkit-fieldset {
	max-width: 1000px !important;
}

div.container {
	background-color: #f9f9f9;
	box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;
	padding: 20px 20px 8px 20px;
	margin-top: 0px;
	margin-bottom: 34px;

	input,
	select {
		background-color: white;
	}

	p {
		margin-top: -40px;
		display: inline-block;
		margin-left: -20px;
		padding: 4px 10px 4px 20px;
		font-size: 1.1em;
		background-color: #19b4c8;
		color: white;
		border-radius: 0 10px 10px 0;
	}
}
.steps {
	margin:0;
	padding:0;
	h2 {
		margin:0 !important;
		display: inline-block;
		background-color: #bfbfbf;
		border-radius: 10px 10px 0 0;
		padding: 12px;
		font-size: 1.1em;
		font-weight: normal;
	}
	h2.step2 {
		background-color: #19b4c8;
		color: white;
	}
}
.step1 {
	.container {
		display: none;
	}
}
.step2 {
	border-top: 1px solid rgba(0, 0, 0, 0.3);
	padding-top:20px;
	.container {
	}
}
</style>

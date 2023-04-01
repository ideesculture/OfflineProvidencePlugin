<template>
  <div id="edit">
		<p>Modification de <span v-html="id"></span></p>
		<FormKit type="text" />
		<FormKit type="form" v-model="data" @submit="register">
    	<FormKitSchema :schema="schema"/>
  	</FormKit>
  	<pre wrap>{{ data }}</pre>
		<textarea v-model="name">
			
		</textarea>
		<div id="additions" v-html="additions"></div>
		<div id="modifications" v-html="modifications"></div>
		<div id="deletions" v-html="deletions"></div>
		<div id="diffs" v-html="diffs"></div>

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
			schema: [
        {
          $el: 'h1',
          children: 'Register'
        },
        {
          $formkit: 'text',
          name: 'email',
          label: 'Email',
          help: 'This will be used for your account.',
          validation: 'required|email'
        },
        {
          $formkit: 'password',
          name: 'password',
          label: 'Password',
          help: 'Enter your new password.',
          validation: 'required|length:5,16'
        },
        {
          $formkit: 'password',
          name: 'password_confirm',
          label: 'Confirm password',
          help: 'Enter your new password again to confirm it.',
          validation: 'required|confirm',
          validationLabel: 'password confirmation',
        },
        {
          $cmp: 'FormKit',
          props: {
            name: 'eu_citizen',
            type: 'checkbox',
            id: 'eu',
            label: 'Are you a european citizen?',
          }
        },
        {
          $formkit: 'select',
          if: '$get(eu).value', // 👀 Oooo, conditionals!
          name: 'cookie_notice',
          label: 'Cookie notice frequency',
          options: {
            refresh: 'Every page load',
            hourly: 'Ever hour',
            daily: 'Every day'
          },
          help: 'How often should we display a cookie notice?'
        }
      ]
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

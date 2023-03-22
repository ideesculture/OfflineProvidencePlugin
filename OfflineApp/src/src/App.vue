<script setup>
import HelloWorld from './components/HelloWorld.vue'
</script>

<template>
  <div class="navbarContainer">
		<div class="navbar">
			<div id="logo"><img src="https://demo.ideesculture.fr/gestion/themes/default/graphics/logos/menu_logo.png" /></div>
			<div id="items">OFFLINE</div>
			<div id="searchBar"><input type="text">🔎</div>
		</div>
  </div>
  <HelloWorld msg="Vite + Vue" />
</template>

<style lang="scss" scoped>
@font-face {font-family: 'DIN-Regular';src: url('./assets/28EC2C_0_0.eot');src: url('./assets/28EC2C_0_0.eot?#iefix') format('embedded-opentype'),url('./assets/28EC2C_0_0.woff') format('woff'),url('./assets/28EC2C_0_0.ttf') format('truetype');}
.navbar {
	text-align: right;
	max-width: 1200px;
	margin:auto;
	div {
		display: inline-block;
		font-family: 'DIN-Regular';
	}
}

#logo {
	float:left;
	margin-top:6px;
	height:45px;
	img {
		height:45px;
	}
}
#searchBar {
	width:174px;
	border:1px solid #ccc;
	border-radius: 3px;
	padding:4px 10px;
	margin-top:12px;
	margin-right:10px; 
	INPUT {
		border:none;
	}
}

#items {
	padding:10px 10px;
}
.navbarContainer {
	box-shadow: 0 0 5px #888;
	height:58px;
	clear:both;
}
</style>

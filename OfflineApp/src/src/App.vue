<script setup>
import List from './components/List.vue'
</script>

<template>
  <div class="navbarContainer">
		<div class="navbar">
			<div id="logo"><img src="/menu_logo.png" /></div>
			<div id="items">
				<router-link class="routerlink" to="/offline/">OFFLINE</router-link>
				<router-link class="routerlink" to="/offline/testload">TESTLOAD</router-link>
			</div>
			<div id="searchBar"><input type="text">🔎</div>
		</div>
		<router-view/>
  </div>
	
</template>

<style lang="scss" scoped>
@font-face {font-family: 'DIN-Regular';src: url('./assets/28EC2C_0_0.eot');src: url('./assets/28EC2C_0_0.eot?#iefix') format('embedded-opentype'),url('./assets/28EC2C_0_0.woff') format('woff'),url('./assets/28EC2C_0_0.ttf') format('truetype');}
.navbar {
	text-align: right;
	max-width: 1200px;
	margin:auto;
	height: 58px;
	div {
		display: inline-block;
		font-family: 'DIN-Regular';
		.routerlink {
			color:black;
			padding:0 10px;
		}
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

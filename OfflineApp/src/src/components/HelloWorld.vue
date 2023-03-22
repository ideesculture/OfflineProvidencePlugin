<script setup>
import { ref } from 'vue'

defineProps({
  msg: String,
})

const count = ref(0)
</script>

<template>
  <div id="content">Content</div>
</template>

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

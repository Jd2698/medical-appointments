<script setup>
	import { FwbAlert } from "flowbite-vue";
	const props = defineProps({ data: Object });
</script>

<template>
	<div v-show="data.status" class="vp-raw grid gap-2">
		<template v-if="data.type == 'success'">
			<fwb-alert type="success" @click="data.status = false">
				{{data.message}}
			</fwb-alert>
		</template>
		<template v-else-if="data.type == 'danger'">
			<fwb-alert icon type="danger" @click="data.status = false">
				{{data.message}}
			</fwb-alert>
		</template>
	</div>
</template>

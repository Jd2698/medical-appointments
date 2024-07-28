<script setup>
	const props = defineProps({ data: Object });
</script>

<template>
	<div v-show="data.status" class="vp-raw grid gap-2">

		<template v-if="data.type == 'success'">
			<div @click="data.status = false" class="p-2 text-white bg-green-700">
				{{data.message}}
			</div>
		</template>

		<template v-else-if="data.type == 'danger'">
			<div @click="data.status = false">
				{{data.message}}
			</div>
		</template>

	</div>
</template>

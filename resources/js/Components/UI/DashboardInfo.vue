<script setup>
	const props = defineProps([
		"title",
		"quantity",
		"faIcon",
		"active",
		"statuses",
		"adicional",
	]);
	const iconClasses = `mt-6 fas ${props.faIcon} text-4xl`;
</script>
<template>
	<div class="p-8 bg-main-900 min-w-40 rounded-sm text-white flex flex-col gap-2 items-center shadow-lg shadow-zinc-400 relative hover:scale-105 transition-transform">

		<h2 class="absolute left-2 top-2 text-sm font-semibold">{{title}}</h2>

		<i :class="iconClasses" fill="currentColor"></i>
		<p>Quantity: {{quantity}}</p>

		<div class="w-full mt-2">

			<template v-if="adicional">
				<p>{{adicional}}</p>
			</template>

			<template v-if="active || active == 0">
				<p>Disabled: {{active}}</p>
			</template>

			<!-- for appointments statuses -->
			<template v-if="statuses">
				<p v-for="key, value in statuses" :key="key">{{value}}: {{key}}</p>
			</template>
		</div>

	</div>
</template>

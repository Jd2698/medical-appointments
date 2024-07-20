<script setup>
	import { computed } from "vue";
	import { Link } from "@inertiajs/vue3";

	const props = defineProps({
		href: String,
		active: Boolean,
	});

	const classes = computed(() => {
		return props.active
			? "is-active inline-flex items-center px-2 pt-1 bg-main-800 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-slate-600 transition duration-150 ease-in-out"
			: "inline-flex items-center px-2 pt-1  border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out";
	});
</script>

<template>
	<Link :href="href" :class="'w-full h-14 flex items-center gap-2 group ' + classes">
	<slot />
	</Link>
</template>

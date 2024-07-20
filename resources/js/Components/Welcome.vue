<script setup>
	import ApplicationLogo from "@/Components/ApplicationLogo.vue";
	import { usePage } from "@inertiajs/vue3";
</script>

<template>
	<template v-if="$page.props.user.roles == 'admin'">
		<h1 class="p-2">Welcome admin</h1>
	</template>
	<div>
		<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
			<ApplicationLogo class="block h-12 w-auto" />

			<h1 class="mt-8 text-2xl font-medium text-gray-900">
				Welcome to your Jetstream application!
			</h1>
		</div>
	</div>
</template>

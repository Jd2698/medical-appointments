<script setup>
	import { Head, Link } from "@inertiajs/vue3";

	import HeartIcon from "@/Components/Icons/HeartIcon.vue";
</script>

<template>

	<Head title="Welcome" />

	<div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-zinc-50 selection:bg-main-800 selection:text-white">

		<div v-if="!$page.props.auth.user" class="w-full sm:fixed sm:top-0 sm:end-0 p-6 text-end z-10 bg-zinc-50 shadow-sm">

			<Link href="/">
			<heart-icon class="text-main-900 absolute hover:scale-125 transition-transform" />
			</Link>

			<Link v-if="$page.props.auth.user" :href="route('dashboard')" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</Link>

			<template v-else>
				<Link :href="route('login')" class="font-semibold p-2 text-zinc-900 rounded-lg hover:text-main-900  focus:outline focus:outline-2  focus:outline-main-800">Log in</Link>

				<Link :href="route('register')" class="ms-4 p-2 font-normal text-zinc-100 rounded-lg hover:text-white focus:outline focus:outline-2  focus:outline-main-800 bg-main-900">Register</Link>
			</template>
		</div>

		<div class="max-w-7xl mx-auto p-6 lg:p-8">
			<div class="flex justify-center mt-16 px-6 sm:items-center sm:justify-between">
				<slot />
			</div>
		</div>
	</div>
</template>

<style>
</style>

<script setup>
	import { ref, computed } from "vue";
	import { Head, Link, router } from "@inertiajs/vue3";
	import NavLink from "@/Components/NavLink.vue";

	import { FwbSidebar, FwbSidebarItem } from "flowbite-vue";
	import UsersIcon from "@/Components/Icons/UsersIcon.vue";
	import UserMdIcon from "@/Components/Icons/UserMdIcon.vue";
	import CalendarIcon from "@/Components/Icons/CalendarIcon.vue";
	import LogoutIcon from "@/Components/Icons/LogoutIcon.vue";
	import HomeIcon from "@/Components/Icons/HomeIcon.vue";

	defineProps({
		title: String,
	});

	const isOpenSidebar = ref(false);
	const onHandleSidebar = () => {
		isOpenSidebar.value = !isOpenSidebar.value;
	};

	const sidebarClasses = computed(() => {
		if (isOpenSidebar.value) {
			return "w-52 h-screen bg-main-900 text-slate-200 text-sm shadow-lg";
		} else {
			return "hidden md:block w-52 h-screen bg-main-900 text-slate-200 text-sm shadow-lg";
		}
	});

	const logout = () => {
		router.post(route("logout"));
	};
</script>

<template>

	<Head :title="title" />
	<div class="flex">
		<!-- sidebar -->
		<div :class="sidebarClasses">
			<div class="h-20 border-b border-zinc-900 flex items-center justify-center">
				<span class="text-main-fontSide text-lg">Hospital</span>
			</div>
			<nav>
				<NavLink :href="route('dashboard')" :active="route().current('dashboard')">
					<CalendarIcon />
					<span class="text-main-fontSide group-hover:text-main-hover group-[.is-active]:text-main-active">
						Appointments
					</span>
				</NavLink>

				<NavLink :href="route('users.index')" :active="route().current('users.index')">
					<UsersIcon />
					<span class="text-main-fontSide group-hover:text-main-hover group-[.is-active]:text-main-active">
						Users
					</span>
				</NavLink>

				<NavLink :href="route('doctors.index')" :active="route().current('doctors.index')">
					<UserMdIcon height="30" />
					<span class="text-main-fontSide group-hover:text-main-hover group-[.is-active]:text-main-active">
						Doctors
					</span>
				</NavLink>

				<NavLink as="button" @click="logout" href="">
					<LogoutIcon />
					<span class="text-main-fontSide group-hover:text-main-hover group-[.is-active]:text-main-active">
						Logout
					</span>
				</NavLink>
			</nav>
		</div>

		<!-- Page Content -->
		<main class="h-screen bg-main-bg grow overflow-x-hidden overflow-y-scroll p-10 space-y-2">
			<div class="flex flex-col md:flex-row md:justify-between relative">
				<button class="md:hidden absolute -right-4 -top-4" @click="onHandleSidebar">
					<i class="fas fa-bars"></i>
				</button>
				<slot name="mainHeader" />
			</div>
			<slot />
		</main>
	</div>

</template>

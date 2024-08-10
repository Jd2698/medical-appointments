<script setup>
	import { ref, computed } from "vue";
	import { Head, Link, router } from "@inertiajs/vue3";
	import NavLink from "@/Components/NavLink.vue";

	import UsersIcon from "@/Components/Icons/UsersIcon.vue";
	import UserMdIcon from "@/Components/Icons/UserMdIcon.vue";
	import UserPatientIcon from "@/Components/Icons/UserPatientIcon.vue";
	import CalendarIcon from "@/Components/Icons/CalendarIcon.vue";
	import LogoutIcon from "@/Components/Icons/LogoutIcon.vue";
	import HomeIcon from "@/Components/Icons/HomeIcon.vue";
	import HeartIcon from "@/Components/Icons/HeartIcon.vue";

	defineProps({
		title: String,
	});

	const isOpenSidebar = ref(true);
	const onHandleSidebar = () => {
		isOpenSidebar.value = !isOpenSidebar.value;
	};

	const sidebarClasses = computed(() => {
		if (isOpenSidebar.value) {
			// return "w-52 transition-all h-screen bg-main-900 text-slate-200 text-sm shadow-lg";
			return "block";
		} else {
			// return "w-3 transition-all h-screen bg-main-900 text-slate-200 text-sm shadow-lg";
			return "hidden";
		}
	});

	const logout = () => {
		router.post(route("logout"));
	};

	function closeMobilSidebar() {
		onHandleSidebar();
		// console.log(e);
	}
</script>

<template>

	<Head :title="title" />
	<div class="flex">
		<!-- sidebar -->
		<div class="hidden md:block w-52 h-screen bg-main-900 text-slate-200 text-sm shadow-lg">
			<div class="h-20 border-b border-zinc-900 flex items-center gap-1 pl-2">
				<HeartIcon class="text-main-800" />
				<span class="text-lg text-main-fontSide">Hospital</span>
			</div>
			<nav>
				<NavLink :href="route('appointments.index')" :active="route().current('appointments.index')">
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

				<NavLink :href="route('patients.index')" :active="route().current('patients.index')">
					<UserPatientIcon height="30" />
					<span class="text-main-fontSide group-hover:text-main-hover group-[.is-active]:text-main-active">
						Patients
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

		<!-- mobil sidebar -->
		<div :class="sidebarClasses + ' md:hidden  h-screen w-screen absolute left-0 top-0 z-50 bg-[rgba(200,200,200,.3)]'" @click="closeMobilSidebar">
			<div class="h-full w-52 transition-all bg-main-900 text-slate-200 text-sm shadow-lg relative" @click.stop="">
				<button class="absolute right-3 top-3" @click="onHandleSidebar">
					<i class="fa-solid fa-up-right-and-down-left-from-center"></i>
				</button>

				<div class="h-20 border-b border-zinc-900 flex items-center gap-1 pl-2">
					<HeartIcon class="text-main-800" />
					<span class="text-lg text-main-fontSide">Hospital</span>
				</div>
				<nav>
					<NavLink :href="route('appointments.index')" :active="route().current('appointments.index')">
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

					<NavLink :href="route('patients.index')" :active="route().current('patients.index')">
						<UserPatientIcon height="30" />
						<span class="text-main-fontSide group-hover:text-main-hover group-[.is-active]:text-main-active">
							Patients
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
		</div>

		<!-- Page Content -->
		<main class="h-screen bg-main-bg grow overflow-x-hidden overflow-y-scroll p-10 space-y-2">
			<div class="flex flex-col md:flex-row md:justify-between relative">
				<!-- show sidebar -->
				<button class="md:hidden absolute -left-6 -top-6" @click="onHandleSidebar">
					<i class="fa-solid fa-up-right-and-down-left-from-center"></i>
				</button>

				<slot name="mainHeader" />
			</div>
			<slot />
		</main>
	</div>

</template>

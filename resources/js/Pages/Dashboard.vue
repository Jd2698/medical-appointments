<script setup>
	import DashboardLayout from "@/Layouts/DashboardLayout.vue";
	import DashboardInfo from "@/Components/UI/DashboardInfo.vue";

	const props = defineProps([
		"appointmentsStatuses",
		"adminQuantity",
		"usersQuantity",
		"doctorsQuantity",
		"patientsQuantity",
		"specialtiesQuantity",
		"appointmentsQuantity",
		"disabledUsersQuantity",
		"disabledDoctorsQuantity",
		"disabledPatientsQuantity",
		"disabledSpecialtiesQuantity",
	]);
</script>

<template>
	<DashboardLayout title="Dashboard">
		<h1 class="text-3xl font-extrabold text-zinc-700 pb-4">Dashboard</h1>
		<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl">

			<DashboardInfo title="Users" :quantity="usersQuantity" :active="disabledUsersQuantity" faIcon="fa-users" :adicional="'Admins: '+adminQuantity" />
			<DashboardInfo title="Doctors" :quantity="doctorsQuantity" :active="disabledDoctorsQuantity" faIcon="fa-user-md" />
			<DashboardInfo title="Patients" :quantity="patientsQuantity" :active="disabledPatientsQuantity" faIcon="fa-user-injured" />

			<DashboardInfo title="Specialties" :quantity="specialtiesQuantity" :active="disabledSpecialtiesQuantity" faIcon="fa-briefcase-medical" />
			<DashboardInfo title="Appointments" :quantity="appointmentsQuantity" :statuses="appointmentsStatuses" faIcon="fa-hand-holding-medical" />

		</div>
	</DashboardLayout>
</template>

<script setup>
	import DashboardLayout from "@/Layouts/DashboardLayout.vue";
	const props = defineProps({ appointments: Object });
	// console.log(props.appointments);

	import DataTable from "datatables.net-vue3";
	import DataTablesCore from "datatables.net";
	import "datatables.net-responsive";

	DataTable.use(DataTablesCore);

	const columns = [
		{ data: "status", title: "Status" },
		{ data: "date", title: "Date" },
		{ data: "start_time", title: "Start time" },
		{ data: "end_time", title: "End time" },
		{ data: "specialty_name", title: "Specialty" },
		{ data: "doctor_name", title: "Doctor name" },
		{ data: "patient_name", title: "Patient name" },
	];

	const options = {
		responsive: true,
		pageLength: 8,
		info: true,
		lengthChange: false,
		language: {
			search: "Buscar:",
			// lengthMenu: "Mostrar _MENU_ registros por página",
			info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
			paginate: {
				first: "first",
				last: "last",
				next: "next",
				previous: "previous",
			},
		},
	};
</script>

<template>
	<DashboardLayout>
		<h1 class="text-3xl font-extrabold text-zinc-700 pb-4">Welcome to your appointments, {{$page.props.auth.user.name.split(" ")[0]}}</h1>

		<div>
			<DataTable :data="appointments" :columns="columns" :options="options" class="display table-bordered">
				<tbody @click="handleAction"></tbody>
			</DataTable>
		</div>
	</DashboardLayout>
</template>

<style>
@import "datatables.net-dt";
@import "datatables.net-responsive-dt";
</style>

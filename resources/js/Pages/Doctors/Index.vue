<script setup>
	import { Link, usePage, router } from "@inertiajs/vue3";
	import { ref, onMounted, computed } from "vue";
	import AppLayout from "@/Layouts/DashboardLayout.vue";
	import Welcome from "@/Components/Welcome.vue";
	import Modal from "./Modal.vue";
	import StatusAlerts from "@/Components/UI/StatusAlerts.vue";

	import DataTable from "datatables.net-vue3";
	import DataTablesCore from "datatables.net";
	import "datatables.net-responsive";

	DataTable.use(DataTablesCore);

	const props = defineProps(["doctors", "genders", "specialties"]);
	const modalShow = ref(false);
	const modalDoctor = ref(null);
	const alertStatus = ref({ status: false });

	const deleteDoctor = async (user) => {
		const res = await axios.delete(route("users.destroy", user));

		let doctorIndex = props.users.indexOf(user);
		props.doctors.splice(doctorIndex, 1);

		alertStatus.value = {
			status: true,
			type: "success",
			message: "Doctor has been deleted",
		};
	};

	const onHandleModal = (value, statusOjbect) => {
		modalShow.value = value;

		statusOjbect ? (alertStatus.value = statusOjbect) : "";

		//si no se manda un true o un false para abrir el modal se pone null
		if (!value) {
			modalDoctor.value = null;
		}
	};

	const handleAction = (event) => {
		const button = event.target;
		const doctor_id = button.getAttribute("data-id");

		const doctor = props.doctors.find((d) => d.id == doctor_id);

		if (button.getAttribute("role") == "edit") {
			onHandleDoctor(doctor);
		} else if (button.getAttribute("role") == "delete") {
			deleteDoctor(doctor);
		}
	};

	const onHandleDoctor = async (doctor) => {
		modalDoctor.value = doctor;
		onHandleModal(true);
	};

	const columns = [
		{ data: "user.name", title: "Name" },
		{ data: "user.documento_identidad", title: "Documento" },
		{ data: "specialty.name", title: "Specialty" },
		{ data: "ratings", title: "Ratings" },
		{
			data: null,
			title: "Actions",
			render: function (data, type, row) {
				return `<div class="flex gap-2 justify-center" data-role='actions'><button onclick='event.preventDefault();' data-id='${row.id}' role='edit' class="bg-main-800 px-2 py-1 rounded"><i class='' data-id='${row.id}' role='edit'>Edit</i></button><button onclick='event.preventDefault();' data-id='${row.id}' role='delete' class="bg-main-800 px-2 py-1 rounded">Delete<i class='' data-id='${row.id}' role='delete'></i></button></div>`;
			},
		},
	];

	const options = {
		responsive: true,
		select: {
			style: "single",
			className: "row-selected",
			blurable: true,
		},
		pageLength: 8,
		info: true,
		lengthChange: false,
		language: {
			// search: "Buscar:",
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
	// console.log(usePage().props.doctors[0].user.name);
</script>

<template>
	<!-- {{$page}} -->
	<AppLayout title="Dashboard Doctors">
		<StatusAlerts :data="alertStatus" />

		<template #mainHeader>
			<div class="w-full text-end">
				<button @click="onHandleModal(true)" class="w-full md:w-20 bg-main-800 font-medium p-2 rounded">
					Add
				</button>
			</div>
		</template>

		<Modal :show="modalShow" @close="onHandleModal" :doctor="modalDoctor" />

		<div>
			<DataTable :data="doctors" :columns="columns" :options="options" class="display table-bordered">
				<tbody @click="handleAction"></tbody>
			</DataTable>
		</div>
	</AppLayout>
</template>

<style>
@import "datatables.net-dt";
@import "datatables.net-responsive-dt";
</style>

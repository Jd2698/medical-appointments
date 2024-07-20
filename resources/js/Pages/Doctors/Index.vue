<script setup>
	import { Link, usePage, router } from "@inertiajs/vue3";
	import { ref, onMounted, computed } from "vue";
	import AppLayout from "@/Layouts/DashboardLayout.vue";
	import Welcome from "@/Components/Welcome.vue";
	import Modal from "./Modal.vue";
	import { FwbBreadcrumb, FwbBreadcrumbItem } from "flowbite-vue";
	import { FwbAlert } from "flowbite-vue";

	import DataTable from "datatables.net-vue3";
	import DataTablesCore from "datatables.net";
	import "datatables.net-responsive";

	DataTable.use(DataTablesCore);

	const props = defineProps(["doctors"]);
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
		{ data: "specialty", title: "Specialty" },
		{ data: "ratings", title: "Ratings" },
		{
			data: null,
			title: "Actions",
			render: function (data, type, row) {
				return `<div class="flex gap-2 justify-center" data-role='actions'><button onclick='event.preventDefault();' data-id='${row.id}' role='edit' class="bg-[#2d6a4f] px-2 py-1 rounded">Edit</button><button onclick='event.preventDefault();' data-id='${row.id}' role='delete' class="bg-[#1b4332] px-2 py-1 rounded">Delete</button></div>`;
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
		<div v-show="alertStatus.status" class="vp-raw grid gap-2">
			<template v-if="alertStatus.type == 'success'">
				<fwb-alert icon type="success" @click="alertStatus.status = false">
					{{alertStatus.message}}
				</fwb-alert>
			</template>
			<template v-else-if="alertStatus.type == 'danger'">
				<fwb-alert icon type="danger" @click="alertStatus.status = false">
					{{alertStatus.message}}
				</fwb-alert>
			</template>
		</div>

		<Modal :show="modalShow" @close="onHandleModal" :doctor="modalDoctor" />

		<template #mainHeader>
			<fwb-breadcrumb>
				<li class="inline-flex items-center">
					<Link :href="route('dashboard')" class="ml-1 inline-flex items-center text-sm font-medium  text-gray-700 hover:text-gray-900">
					Dashboard
					</Link>
				</li>
				<fwb-breadcrumb-item>
					Doctors
				</fwb-breadcrumb-item>
			</fwb-breadcrumb>
			<button @click="onHandleModal(true)" class="w-full md:w-20 bg-main-800 hover:bg-main-700 text-white font-medium p-2 rounded">
				Add
			</button>
		</template>

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

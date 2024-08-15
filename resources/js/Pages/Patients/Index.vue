<script setup>
	import { Link, usePage, router } from "@inertiajs/vue3";
	import { ref, onMounted, computed, onBeforeMount } from "vue";

	// components
	import AppLayout from "@/Layouts/DashboardLayout.vue";
	import Welcome from "@/Components/Welcome.vue";
	import Modal from "./Modal.vue";

	import DataTable from "datatables.net-vue3";
	import DataTablesCore from "datatables.net";
	import "datatables.net-responsive";

	DataTable.use(DataTablesCore);

	const props = defineProps(["patients", "genders"]);
	const isShowModalOpen = ref(false);
	const modalPatient = ref(null);
	const alertStatus = ref({ status: false });

	const onHandleModal = (showModal = false, statusOjbect) => {
		isShowModalOpen.value = showModal;

		if (statusOjbect) {
			Swal.fire({
				icon: statusOjbect.type,
				title: statusOjbect.message,
				showConfirmButton: false,
				timer: 1500,
			});
		}

		//si no se manda un true o un false para abrir el modal se pone null
		if (!showModal) {
			modalPatient.value = null;
		}
	};

	const handleAction = (event) => {
		const button = event.target;
		const patient_id = button.getAttribute("data-id");

		const patient = props.patients.find((d) => d.id == patient_id);

		if (button.getAttribute("role") == "edit") {
			onHandlePatient(patient);
		}
	};

	const onHandlePatient = async (patient) => {
		modalPatient.value = patient;
		onHandleModal(true);
	};

	let columns = [
		{ data: "user.name", title: "Patient name" },
		{ data: "user.documento_identidad", title: "Documento" },
		{ data: "eps", title: "EPS" },
		{
			data: null,
			title: "Actions",
			render: function (data, type, row) {
				return `<div class="flex gap-2 justify-center" data-role='actions'><button onclick='event.preventDefault();' data-id='${row.id}' role='edit' class="w-20 bg-main-800 px-2 py-1 rounded"><i class='' data-id='${row.id}' role='edit'>Edit</i></button>`;
			},
		},
	];

	onBeforeMount(() => {
		if (!usePage().props.auth.user.roles.some((r) => r.name == "admin")) {
			columns = columns.filter((f) => f.title != "Actions");
		}
	});

	const options = {
		responsive: true,
		pageLength: 8,
		info: true,
		lengthChange: false,
		language: {
			// search: "Buscar:",
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
	<!-- {{$page}} -->
	<AppLayout title="Admin / patients">

		<template #mainHeader v-if="$page.props.auth.user.roles.find((r) => r.name == 'admin')">
			<div class="w-full text-end">
				<button @click="onHandleModal(true)" class="w-full md:w-32 bg-main-800 font-medium p-2 rounded">
					Add patient
				</button>
			</div>
		</template>

		<Modal v-if="$page.props.auth.user.roles.find((r) => r.name == 'admin')" :show="isShowModalOpen" @close="onHandleModal" :patient="modalPatient" />

		<div>
			<DataTable :data="patients" :columns="columns" :options="options" class="display table-bordered">
				<tbody @click="handleAction"></tbody>
			</DataTable>
		</div>
	</AppLayout>
</template>

<style>
@import "datatables.net-dt";
@import "datatables.net-responsive-dt";
</style>

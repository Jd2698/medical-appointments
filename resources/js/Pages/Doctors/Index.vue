<script setup>
	import { Link, usePage, router } from "@inertiajs/vue3";
	import { ref, onMounted, computed } from "vue";

	// components
	import AppLayout from "@/Layouts/DashboardLayout.vue";
	import Welcome from "@/Components/Welcome.vue";
	import Modal from "./Modal.vue";

	import DataTable from "datatables.net-vue3";
	import DataTablesCore from "datatables.net";
	import "datatables.net-responsive";

	DataTable.use(DataTablesCore);

	const props = defineProps(["doctors", "genders", "specialties"]);
	const isShowModalOpen = ref(false);
	const modalDoctor = ref(null);
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
			modalDoctor.value = null;
		}
	};

	const handleAction = (event) => {
		const button = event.target;
		const doctor_id = button.getAttribute("data-id");

		const doctor = props.doctors.find((d) => d.id == doctor_id);

		if (button.getAttribute("role") == "edit") {
			onHandleDoctor(doctor);
		}
	};

	const onHandleDoctor = async (doctor) => {
		modalDoctor.value = doctor;
		onHandleModal(true);
	};

	const columns = [
		{ data: "user.name", title: "Doctor name" },
		{ data: "user.documento_identidad", title: "Documento" },
		{ data: "specialty.name", title: "Specialty" },
		{ data: "ratings", title: "Ratings" },
		{
			data: null,
			title: "Actions",
			render: function (data, type, row) {
				return `<div class="flex gap-2 justify-center" data-role='actions'><button onclick='event.preventDefault();' data-id='${row.id}' role='edit' class="w-20 bg-main-800 px-2 py-1 rounded"><i class='' data-id='${row.id}' role='edit'>Edit</i></button>`;
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
</script>

<template>
	<!-- {{$page}} -->
	<AppLayout title="Dashboard Doctors">

		<template #mainHeader>
			<div class="w-full text-end">
				<button @click="onHandleModal(true)" class="w-full md:w-32 bg-main-800 font-medium p-2 rounded">
					Add doctor
				</button>
			</div>
		</template>

		<Modal :show="isShowModalOpen" @close="onHandleModal" :doctor="modalDoctor" />

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

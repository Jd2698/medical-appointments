<script setup>
	import { Link, usePage, router } from "@inertiajs/vue3";
	import { ref, onMounted, computed, onBeforeMount } from "vue";

	// components
	import AppLayout from "@/Layouts/DashboardLayout.vue";
	import Modal from "./Modal.vue";

	import DataTable from "datatables.net-vue3";
	import DataTablesCore from "datatables.net";
	import "datatables.net-responsive";

	DataTable.use(DataTablesCore);

	const props = defineProps(["specialties"]);
	// console.log(props.specialties);
	const isModalOpen = ref(false);
	const modalSpecialty = ref(null);

	const onHandleModal = (showModal = false, statusOjbect) => {
		isModalOpen.value = showModal;

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
			modalSpecialty.value = null;
		}
	};

	const handleAction = (event) => {
		const button = event.target;
		const specialty_id = button.getAttribute("data-id");

		const specialty = props.specialties.find((u) => u.id == specialty_id);

		if (button.getAttribute("role") == "edit") {
			onHandleSpecialty(specialty);
		}
	};

	const onHandleSpecialty = async (specialty) => {
		modalSpecialty.value = specialty;
		onHandleModal(true);
	};

	let columns = [
		{ data: "doctors_count", title: "# doctors" },
		{
			data: "is_active",
			title: "Status",
			render: function (data, type, row) {
				if (data == 0) {
					return "inactive";
				} else {
					return "active";
				}
			},
		},
		{ data: "name", title: "Specialty name" },
		{
			data: null,
			title: "Actions",
			render: function (data, type, row) {
				return `<div class="flex gap-2 justify-center" data-role='actions'><button onclick='event.preventDefault();' data-id='${row.id}' role='edit' class="w-20 bg-main-800 px-2 py-1 rounded"><i class='' data-id='${row.id}' role='edit'>Edit</i></button></div>`;
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
	<!-- {{$page}} -->
	<AppLayout title="Admin / specialties">

		<template #mainHeader v-if="$page.props.auth.user.roles.find((r) => r.name == 'admin')">
			<div class="w-full text-end">
				<button @click="onHandleModal(true)" class="w-full md:w-32 bg-main-800 font-medium p-2 rounded">
					Add specialty
				</button>
			</div>
		</template>

		<Modal v-if="$page.props.auth.user.roles.find((r) => r.name == 'admin')" :show="isModalOpen" @close="onHandleModal" :specialty="modalSpecialty" />

		<div>
			<DataTable :data="specialties" :columns="columns" :options="options" class="display table-bordered">
				<tbody @click="handleAction"></tbody>
			</DataTable>
		</div>
	</AppLayout>
</template>

<style>
@import "datatables.net-dt";
@import "datatables.net-responsive-dt";
</style>

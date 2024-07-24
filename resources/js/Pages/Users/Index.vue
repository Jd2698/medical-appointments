<script setup>
	import { Link, usePage, router } from "@inertiajs/vue3";
	import { ref, onMounted, computed } from "vue";
	import AppLayout from "@/Layouts/DashboardLayout.vue";
	import Welcome from "@/Components/Welcome.vue";
	import Modal from "./Modal.vue";
	import StatusAlerts from "@/Components/UI/StatusAlerts.vue";
	import { FwbBreadcrumb, FwbBreadcrumbItem } from "flowbite-vue";

	import DataTable from "datatables.net-vue3";
	import DataTablesCore from "datatables.net";
	import "datatables.net-responsive";

	DataTable.use(DataTablesCore);

	const props = defineProps(["users", "genders"]);
	const modalShow = ref(false);
	const modalUser = ref(null);
	const alertStatus = ref({ status: false });

	const deleteUser = async (user) => {
		const res = await axios.delete(route("users.destroy", user));

		if (usePage().props.auth.user.id == user.id) {
			router.post(route("logout"));
		}

		let userIndex = props.users.indexOf(user);
		props.users.splice(userIndex, 1);

		alertStatus.value = {
			status: true,
			type: "success",
			message: "User has been deleted",
		};
	};

	const onHandleModal = (value, statusOjbect) => {
		modalShow.value = value;

		statusOjbect ? (alertStatus.value = statusOjbect) : "";

		//si no se manda un true o un false para abrir el modal se pone null
		if (!value) {
			modalUser.value = null;
		}
	};

	const handleAction = (event) => {
		const button = event.target;
		const user_id = button.getAttribute("data-id");

		const user = props.users.find((u) => u.id == user_id);

		if (button.getAttribute("role") == "edit") {
			onHandleUser(user);
		} else if (button.getAttribute("role") == "delete") {
			deleteUser(user);
		}
	};

	const onHandleUser = async (user) => {
		modalUser.value = user;
		onHandleModal(true);
	};

	const columns = [
		{
			data: "roles[0].name",
			title: "Role",
			render: function (data, type, row) {
				if (data && data.length > 0) {
					return data;
				} else {
					return "Sin role";
				}
			},
		},
		{ data: "name", title: "Name" },
		{ data: "documento_identidad", title: "Documento" },
		{ data: "phone", title: "Phone" },
		{ data: "email", title: "Email" },
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
</script>

<template>
	<!-- {{$page}} -->
	<AppLayout title="Dashboard Users">
		<StatusAlerts :data="alertStatus" />

		<template #mainHeader>
			<fwb-breadcrumb>
				<li class="inline-flex items-center">
					<Link :href="route('dashboard')" class="ml-1 inline-flex items-center text-sm font-medium  text-gray-700 hover:text-gray-900">
					Dashboard
					</Link>
				</li>
				<fwb-breadcrumb-item>
					Users
				</fwb-breadcrumb-item>
			</fwb-breadcrumb>
			<button @click="onHandleModal(true)" class="w-full md:w-20 bg-main-800 font-medium p-2 rounded">
				Add
			</button>
		</template>

		<Modal :show="modalShow" @close="onHandleModal" :user="modalUser" />

		<div>
			<DataTable :data="users" :columns="columns" :options="options" class="display table-bordered">
				<tbody @click="handleAction"></tbody>
			</DataTable>
		</div>
	</AppLayout>
</template>

<style>
@import "datatables.net-dt";
@import "datatables.net-responsive-dt";
</style>

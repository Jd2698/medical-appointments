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

	const props = defineProps(["users", "genders"]);
	// console.log(props.users);
	const isModalOpen = ref(false);
	const modalUser = ref(null);

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
			modalUser.value = null;
		}
	};

	const handleAction = (event) => {
		const button = event.target;
		const user_id = button.getAttribute("data-id");

		const user = props.users.find((u) => u.id == user_id);

		if (button.getAttribute("role") == "edit") {
			onHandleUser(user);
		}
	};

	const onHandleUser = async (user) => {
		modalUser.value = user;
		onHandleModal(true);
	};

	const columns = [
		{
			data: "roles",
			title: "Role",
			render: function (data, type, row) {
				if (data && data.length > 0) {
					return data.map((d) => d.name).join(" - ");
				} else {
					return "Sin role";
				}
			},
		},
		{
			data: "is_active",
			title: "Active",
			render: function (data, type, row) {
				if (data == 0) {
					return "inactive";
				} else {
					return "active";
				}
			},
		},
		{ data: "name", title: "User name" },
		{ data: "documento_identidad", title: "Documento" },
		{ data: "phone", title: "Phone" },
		{ data: "email", title: "Email" },
		{
			data: null,
			title: "Actions",
			render: function (data, type, row) {
				return `<div class="flex gap-2 justify-center" data-role='actions'><button onclick='event.preventDefault();' data-id='${row.id}' role='edit' class="w-20 bg-main-800 px-2 py-1 rounded"><i class='' data-id='${row.id}' role='edit'>Edit</i></button></div>`;
			},
		},
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
	<!-- {{$page}} -->
	<AppLayout title="Dashboard Users">

		<template #mainHeader>
			<div class="w-full text-end">
				<button @click="onHandleModal(true)" class="w-full md:w-32 bg-main-800 font-medium p-2 rounded">
					Add user
				</button>
			</div>
		</template>

		<Modal :show="isModalOpen" @close="onHandleModal" :user="modalUser" />

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

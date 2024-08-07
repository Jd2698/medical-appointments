<script setup>
	import { Link } from "@inertiajs/vue3";
	import { ref, computed } from "vue";

	// components
	import DialogModal from "@/Components/DialogModal.vue";
	import SecondaryButton from "@/Components/SecondaryButton.vue";
	import UserForm from "./UserForm.vue";

	const props = defineProps({ show: Boolean, user: Object });
	const emit = defineEmits(["close"]);

	const closeModal = (alertStatus) => {
		alertStatus ? emit("close", false, alertStatus) : emit("close", false);
	};

	const action = computed(() => (props.user ? "Editar" : "Crear"));
</script>

<template>
	<DialogModal :show="show" @close='closeModal' maxWidth="xl">
		<template #title>
			{{action + " usuario"}}
		</template>

		<template #content>
			<div class="mt-4">
				<UserForm :user="user" @close='closeModal' />
			</div>
		</template>
	</DialogModal>
</template>

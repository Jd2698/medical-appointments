<script setup>
	import { Link } from "@inertiajs/vue3";
	import { ref, computed } from "vue";
	import DialogModal from "@/Components/DialogModal.vue";
	import SecondaryButton from "@/Components/SecondaryButton.vue";
	import AppointmentForm from "./AppointmentForm.vue";

	const props = defineProps({ show: Boolean, appointment: Object });
	const emit = defineEmits(["close"]);

	const closeModal = (alertStatus) => {
		alertStatus ? emit("close", false, alertStatus) : emit("close", false);
	};

	const title = computed(() => (props.user ? "Editar cita" : "Crear cita"));
</script>

<template>
	<DialogModal :show="show" @close='closeModal' maxWidth="xl">
		<template #title>
			{{title}}
		</template>

		<template #content>
			<div class="mt-4">
				<AppointmentForm :appointment="appointment" @close='closeModal' />
			</div>
		</template>
	</DialogModal>
</template>

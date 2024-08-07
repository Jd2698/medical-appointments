<script setup>
	import { Link } from "@inertiajs/vue3";
	import { ref, computed } from "vue";

	// components
	import DialogModal from "@/Components/DialogModal.vue";
	import SecondaryButton from "@/Components/SecondaryButton.vue";
	import PatientForm from "./PatientForm.vue";

	const props = defineProps({ show: Boolean, patient: Object });
	const emit = defineEmits(["close"]);

	const closeModal = (alertStatus) => {
		alertStatus ? emit("close", false, alertStatus) : emit("close", false);
	};

	const title = computed(() =>
		props.patient ? "Editar patient" : "Crear patient"
	);
</script>

<template>
	<DialogModal :show="show" @close='closeModal' maxWidth="xl">
		<template #title>
			{{title}}
		</template>

		<template #content>
			<div class="mt-4">
				<PatientForm :patient="patient" @close='closeModal' />
			</div>
		</template>
	</DialogModal>
</template>

<script setup>
	import { useForm } from "@inertiajs/vue3";
	import { ref } from "vue";
	import InputError from "@/Components/InputError.vue";

	const props = defineProps({ selectedAppointment: Object });
	// console.log(props.selectedAppointment);
	const emit = defineEmits(["close"]);

	const isButtonEditClick = ref(false);
	const buttonName = ref("Edit");

	const form = useForm({
		status: props.selectedAppointment.allData.status,
	});

	const handleEditClick = (methodObject) => {
		isButtonEditClick.value = !isButtonEditClick.value;
		const nameChange = buttonName.value == "Edit" ? "Cancel" : "Edit";
		buttonName.value = nameChange;

		if (methodObject.method == "update") {
			form.put(
				route("appointments.update", props.selectedAppointment.allData.id),
				{
					onSuccess: () => {
						emit("close", {
							status: true,
							type: "success",
							message: "Status has been updated",
						});
					},
				}
			);
		}
	};
</script>
<template>
	<div class="mt-4 space-y-4">

		<!-- button -->
		<div class="text-lg absolute top-5 right-5 space-x-2">
			<button @click="handleEditClick">{{buttonName}}</button>
			<button v-if="isButtonEditClick" @click="handleEditClick({method: 'update'})">Save</button>
		</div>

		<div class="text-center space-y-4">

			<span class="block">Estado</span>
			<span v-if="!isButtonEditClick" class="text-lg">{{selectedAppointment.allData.status}}</span>

			<!-- form -->
			<select v-if="isButtonEditClick" v-model="form.status" class="text-center mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
				<template v-for="status in $page.props.appointmentsStatuses" :key="status">
					<option :value="status">{{ status }}</option>
				</template>
			</select>
			<InputError class="mt-2" :message="form.errors.status" />

			<span class="block">Fecha</span>
			<span class="text-lg">
				<!-- mejorar esto, hacer un get en el model -->
				{{
                    selectedAppointment.fecha
                    +" | "+
                    selectedAppointment.allData.format_start_time
                    +" - "+
                    selectedAppointment.allData.format_end_time
                }}
			</span>

			<span class="block">Especialidad</span>
			<span class="text-lg">{{selectedAppointment.allData.specialty.name}}</span>

			<span class="block">Paciente</span>
			<span class="text-lg">{{selectedAppointment.allData.patient.name}}</span>

			<span class="block">Doctor</span>
			<span class="text-lg">{{selectedAppointment.allData.doctor.user.name}}</span>

			<template v-if="selectedAppointment.allData.comment">
				<span class="block">Comment</span>
				<span class="text-lg">{{selectedAppointment.allData.comment}}</span>
			</template>
		</div>
	</div>
</template>

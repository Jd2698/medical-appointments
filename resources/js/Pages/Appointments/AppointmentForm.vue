<script setup>
	import { Link, useForm, usePage } from "@inertiajs/vue3";
	import Checkbox from "@/Components/Checkbox.vue";
	import InputError from "@/Components/InputError.vue";
	import InputLabel from "@/Components/InputLabel.vue";
	import PrimaryButton from "@/Components/PrimaryButton.vue";
	import TextInput from "@/Components/TextInput.vue";
	import ActionMessage from "@/Components/ActionMessage.vue";
	import { onMounted, watch, ref } from "vue";

	import { dateFormat, addMinutes, rangeNotValid } from "@/Helpers/calendar.js";

	const props = defineProps({ appointment: Object, date: String, dates: Object });
	const emit = defineEmits(["close"]);

	const form = useForm({
		patient_id: props.appointment ? props.appointment.patient_id : "select",
		doctor_id: props.appointment ? props.appointment.doctor_id : "select",
		appointment_date: props.appointment
			? props.appointment.appointment_date
			: props.date,
		notes: props.appointment ? props.appointment.notes : "",
		status: props.appointment
			? props.appointment.status
			: usePage().props.appointmentsStatuses[0],
	});

	const inputDate = ref(null);
	const hourFormat = ref(null);
	const rangeError = ref(false);

	const hoursRanges = props.dates.map((date) => {
		const dateLength = date.appointment_date.length;
		const start = date.appointment_date.substring(dateLength - 5, dateLength);
		const end = addMinutes(start, 30);

		return { start, end };
	});

	const timeChange = () => {
		hourFormat.value = form.appointment_date.substring(
			form.appointment_date.length - 5,
			form.appointment_date.length
		);
	};

	const addLimitInputDate = () => {
		const fechaActual = new Date();
		fechaActual.setDate(fechaActual.getDate() + 1);
		const fechaActualFormat = dateFormat(fechaActual);
		inputDate.value.min = fechaActualFormat;
	};

	const submit = () => {
		if (!rangeError.value) {
			// create
			if (!props.appointment) {
				form.post(route("appointments.store"), {
					onSuccess: () => {
						form.reset();
						emit("close", {
							status: true,
							type: "success",
							message: "Appointment has been created",
						});
					},
				});
			} else {
				const {
					format_notes,
					updated_at,
					created_at,
					deleted_at,
					...data
				} = props.appointment;

				form.put(route("appointments.update", data), {
					onSuccess: () => {
						form.reset();
						emit("close", {
							status: true,
							type: "success",
							message: "Appointment has been updated",
						});
					},
				});
			}
		}
	};

	onMounted(() => {
		addLimitInputDate();
		timeChange();
	});

	watch(hourFormat, async (newValue, oldValue) => {
		rangeError.value = false;
		hoursRanges.forEach((hour) => {
			if (rangeNotValid(hourFormat.value, hour)) {
				rangeError.value = true;
				return;
			}
		});
	});
</script>

<template>

	<form @submit.prevent="submit" class="grid grid-cols-2 gap-2">
		<!-- {{$page.props.patients[0]}} -->

		<!-- patient_id -->
		<div class="col-span-2">
			<InputLabel for="patient_id" value="Patient" />
			<select v-model="form.patient_id" id="patient_id" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
				<template v-for="patient in $page.props.patients" :key="patient.id">
					<option :value="patient.id">{{ patient.user.name}}</option>
				</template>
				<option value="select" selected disabled>SELECT</option>
			</select>
			<InputError class="mt-2" :message="form.errors.patient_id" />
		</div>

		<!-- doctor_id -->
		<div class="mt-4">
			<InputLabel for="doctor_id" value="Doctor" />
			<select v-model="form.doctor_id" id="doctor_id" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
				<template v-for="doctor in $page.props.doctors" :key="doctor">
					<option :value="doctor.id">{{ doctor.user.name}}</option>
				</template>
				<option value="select" selected disabled>SELECT</option>
			</select>
			<InputError class="mt-2" :message="form.errors.doctor_id" />
		</div>

		<!-- appointment_date -->
		<div class="mt-4">
			<InputLabel for="appointment_date" value="Appointment Date" />
			<input ref="inputDate" type="datetime-local" v-model="form.appointment_date" @change="timeChange" id="appointment_date" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
			<InputError class="mt-2" :message="form.errors.appointment_date" />
			<InputError v-if="rangeError" class="mt-2" message="Rango de fecha no válida" />
		</div>

		<!-- notes -->
		<div class="mt-4 col-span-2">
			<InputLabel for="notes" value="Notes" />
			<textarea id="notes" v-model="form.notes" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm"></textarea>
			<InputError class="mt-2" :message="form.errors.notes" />
		</div>

		<!-- status -->
		<div class="mt-4">
			<InputLabel for="status" value="Status" />
			<select v-model="form.status" name="status" id="status" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
				<template v-for="status in $page.props.appointmentsStatuses" :key="status">
					<option :value="status">{{ status }}</option>
				</template>
			</select>
		</div>

		<div class="col-span-2 flex items-center justify-end mt-4">
			<PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Submit
			</PrimaryButton>
		</div>
	</form>
</template>

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
	import axios from "axios";

	const props = defineProps({ appointment: Object, date: String, dates: Object });
	const emit = defineEmits(["close"]);

	const inputSelect = ref(null);
	const specialtyChange = ref("select");
	const doctors = ref(null);

	const form = useForm({
		patient_id: "select",
		doctor_id: "select",
		date: props.date,
		start_time: props.date,
		end_time: props.date,
		comment: "",
		status: usePage().props.appointmentsStatuses[0],
	});

	const inputDate = ref(null);
	const hourFormat = ref(null);
	const rangeError = ref(false);

	// const hoursRanges = props.dates.map((date) => {
	// 	const dateLength = date.appointment_date.length;
	// 	const start = date.appointment_date.substring(dateLength - 5, dateLength);
	// 	const end = addMinutes(start, 30);

	// 	return { start, end };
	// });

	const timeChange = () => {
		console.log("Hola");
		// hourFormat.value = form.appointment_date.substring(
		// 	form.appointment_date.length - 5,
		// 	form.appointment_date.length
		// );
	};

	const addLimitInputDate = () => {
		const currentDate = new Date();
		currentDate.setDate(currentDate.getDate() + 1);
		currentDate.setHours(0, 0, 0, 0);

		const currentDateFormat = dateFormat(currentDate);
		inputDate.value.min = currentDateFormat;
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
			}
			// else {
			// 	form.put(route("appointments.update", props.appointment), {
			// 		onSuccess: () => {
			// 			form.reset();
			// 			emit("close", {
			// 				status: true,
			// 				type: "success",
			// 				message: "Appointment has been updated",
			// 			});
			// 		},
			// 	});
			// }
		}
	};

	onMounted(() => {
		addLimitInputDate();
		timeChange();
		doctors.value = [];
	});

	// watch(hourFormat, async (newValue, oldValue) => {
	// 	rangeError.value = false;
	// 	hoursRanges.forEach((hour) => {
	// 		if (rangeNotValid(hourFormat.value, hour)) {
	// 			rangeError.value = true;
	// 			return;
	// 		}
	// 	});
	// });

	watch(doctors, async (newValue, oldValue) => {
		if (doctors.value.length > 0) {
			inputSelect.value.disabled = false;
		} else {
			inputSelect.value.disabled = true;
		}
	});

	// obtener doctores
	const handleSelectChange = async () => {
		const params = {
			id: specialtyChange.value,
		};

		try {
			const res = await axios("/dashboard/doctors/get-specialty-doctors", {
				params,
			});

			doctors.value = res.data.doctors;
			form.doctor_id = "select";
		} catch (error) {
			console.log(error.response.data.errors);
		}
	};
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

		<!-- choose specialty -->
		<div class="mt-4 ">
			<InputLabel for="specialty_id" value="Specialty" />
			<select v-model="specialtyChange" @change="handleSelectChange" id="specialty_id" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
				<template v-for="specialty in $page.props.specialties" :key="specialty">
					<option :value="specialty.id">{{ specialty.name}}</option>
				</template>
				<option value="select" selected disabled>SELECT</option>
			</select>
			<!-- <InputError class="mt-2" :message="form.errors.specialty_id" /> -->
		</div>

		<!-- doctor_id -->
		<div class="mt-4">
			<InputLabel for="doctor_id" value="Doctor" />
			<select ref="inputSelect" v-model="form.doctor_id" id="doctor_id" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
				<template v-for="doctor in doctors" :key="doctor">
					<option :value="doctor.id">{{ doctor.user.name}}</option>
				</template>
				<option value="select" selected disabled>SELECT</option>
			</select>
			<InputError class="mt-2" :message="form.errors.doctor_id" />
		</div>

		<!-- date -->
		<div class="mt-4 col-span-2">
			<InputLabel for="date" value="Appointment Date" />
			<input ref="inputDate" type="date" v-model="form.date" @change="timeChange" id="date" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
			<InputError class="mt-2" :message="form.errors.date" />
			<InputError v-if="rangeError" class="mt-2" message="Rango de fecha no válida" />
		</div>

		<!-- start_time -->
		<div class="mt-4">
			<InputLabel for="start_time" value="Start time" />
			<input type="time" v-model="form.start_time" @change="timeChange" id="start_time" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
			<InputError class="mt-2" :message="form.errors.start_time" />
		</div>

		<!-- end_time -->
		<div class="mt-4">
			<InputLabel for="end_time" value="End time" />
			<input type="time" v-model="form.end_time" @change="timeChange" id="end_time" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
			<InputError class="mt-2" :message="form.errors.end_time" />
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

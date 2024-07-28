<script setup>
	import { Link, useForm, usePage } from "@inertiajs/vue3";
	import { onMounted, watch, ref } from "vue";

	// components
	import Checkbox from "@/Components/Checkbox.vue";
	import InputError from "@/Components/InputError.vue";
	import InputLabel from "@/Components/InputLabel.vue";
	import PrimaryButton from "@/Components/PrimaryButton.vue";
	import TextInput from "@/Components/TextInput.vue";
	import ActionMessage from "@/Components/ActionMessage.vue";

	// timepicker - datepicker
	import flatpickr from "flatpickr";
	import { Spanish } from "flatpickr/dist/l10n/es";

	const props = defineProps({ appointment: Object, date: String });
	const emit = defineEmits(["close"]);

	const specialtyChange = ref("select");
	const inputSelect = ref(null);
	const doctors = ref(null);

	const dateInput = ref(null);
	const timeInput = ref(null);
	const startTimeInputValue = ref("07:00");
	const endTimeInputValue = ref("30");
	const rangeError = ref(false);

	const form = useForm({
		patient_id: "select",
		doctor_id: "select",
		date: props.date,
		start_time: "07:00",
		end_time: "30",
		comment: "",
		status: usePage().props.appointmentsStatuses[0],
	});

	const addflatPickr = () => {
		// flatpickr to date
		flatpickr(dateInput.value, {
			static: true,
			locale: Spanish,
			minDate: new Date().toISOString().slice(0, 7),
			disable: [
				{
					// "2024-07-01",
					from: new Date().toISOString().slice(0, 7) + "-01",
					to: "today",
				},
			],
			enableTime: false,
			dateFormat: "Y-m-d",
		});

		// flatpickr to time
		flatpickr(timeInput.value, {
			static: true,
			defaultDate: "07:00",
			enableTime: true,
			noCalendar: true,
			dateFormat: "H:i",
			time_24hr: false,
			minTime: "07:00",
			maxTime: "18:00",
		});
	};

	// obtener doctores
	const handleSelectChange = async () => {
		const params = {
			specialty_id: specialtyChange.value,
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

	// isTimeValid
	const validateTimeRange = async () => {
		const params = {
			date: form.date,
			start_time: startTimeInputValue.value,
			end_time: endTimeInputValue.value,
		};

		try {
			const res = await axios("/dashboard/appointments/range-validation", {
				params,
			});

			rangeError.value = !res.data.isValid;
		} catch (error) {
			console.log(error.response.data.errors);
		}
	};

	onMounted(() => {
		addflatPickr();
		validateTimeRange();

		// Initialize the watcher
		doctors.value = [];
	});

	watch(doctors, async (newValue, oldValue) => {
		if (doctors.value.length > 0) {
			inputSelect.value.disabled = false;
		} else {
			inputSelect.value.disabled = true;
		}
	});

	watch(startTimeInputValue, async (newValue, oldValue) => validateTimeRange());
	watch(endTimeInputValue, async (newValue, oldValue) => validateTimeRange());

	const submit = () => {
		if (!rangeError.value) {
			// create
			if (!props.appointment) {
				form.start_time = startTimeInputValue.value;
				form.end_time = endTimeInputValue.value;

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
		}
	};
</script>

<template>

	<form @submit.prevent="submit" class="grid grid-cols-2 gap-2">

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
			<InputError class="mt-2" :message="form.errors.specialty_id" />
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
			<input ref="dateInput" type="text" v-model="form.date" id="date" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
			<InputError class="mt-2" :message="form.errors.date" />
		</div>

		<!-- start_time -->
		<div class="mt-4">
			<InputLabel for="start_time" value="Start time" />
			<input ref="timeInput" type="text" v-model="startTimeInputValue" id="start_time" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
			<InputError class="mt-2" :message="form.errors.start_time" />
			<InputError v-if="rangeError" class="mt-2" message="Rango de hora no válida" />
		</div>

		<!-- end_time -->
		<div class="mt-4">
			<InputLabel for="end_time" value="End time" />
			<select v-model="endTimeInputValue" id="end_time" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
				<option value="15">15 min</option>
				<option value="30">30 min</option>
				<option value="60">1 h</option>
			</select>
			<InputError class="mt-2" :message="form.errors.end_time" />
		</div>

		<!-- comment -->
		<div class="mt-4 col-span-2">
			<InputLabel for="comment" value="comment" />
			<textarea id="comment" v-model="form.comment" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm"></textarea>
			<InputError class="mt-2" :message="form.errors.comment" />
		</div>

		<!-- statuses -->
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
<style>
@import "flatpickr/dist/flatpickr.min.css";
.flatpickr-wrapper {
	width: 100%;
}
</style>

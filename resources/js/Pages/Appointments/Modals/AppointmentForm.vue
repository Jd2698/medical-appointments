<script setup>
	import { Link, useForm, usePage } from "@inertiajs/vue3";
	import { onMounted, watch, ref } from "vue";

	// components
	import InputError from "@/Components/InputError.vue";
	import InputLabel from "@/Components/InputLabel.vue";
	import PrimaryButton from "@/Components/PrimaryButton.vue";

	// timepicker - datepicker
	import flatpickr from "flatpickr";
	import { Spanish } from "flatpickr/dist/l10n/es";

	const props = defineProps({ appointment: Object, date: String });
	// console.log(usePage().props.patients);
	const emit = defineEmits(["close"]);

	const specialtyChange = ref("select");
	const patientChange = ref(usePage().props.patients);
	const inputSelect = ref(null);
	const doctors = ref([]);

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

	const selectedPatient = ref("select");

	const addflatPickr = () => {
		// flatpickr to date
		flatpickr(dateInput.value, {
			static: true,
			locale: Spanish,
			minDate: new Date().fp_incr(1),
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

    // reiniciar campos
	const handlePatientChange = () => {
		form.patient_id = selectedPatient.value;

		// reiniciar los campos de especialidades y doctores
		doctors.value = [];
		form.doctor_id = "select";
		specialtyChange.value = "select";
	};

	// selección de especialidad -> obtener doctores
	const handleSelectChange = async () => {
		if (specialtyChange.value != "select") {
			const params = {
				specialty_id: specialtyChange.value,
			};

			try {
				const res = await axios(
					"/dashboard/doctors/get-specialty-doctors",
					{
						params,
					}
				);

				doctors.value = res.data.doctors;

				// excluir al doctor que es paciente
				if (doctors.value.some((d) => d.user_id == form.patient_id)) {
					doctors.value = doctors.value.filter(
						(d) => d.user_id != form.patient_id
					);
				}

				form.doctor_id = "select";
			} catch (error) {
				console.log(error.response.data.errors);
			}
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
	});

	watch(startTimeInputValue, async (newValue, oldValue) => validateTimeRange());
	watch(endTimeInputValue, async (newValue, oldValue) => validateTimeRange());
	watch(specialtyChange, async (newValue, oldValue) => handleSelectChange());
	watch(selectedPatient, async (newValue, oldValue) => handlePatientChange());

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

			<!-- el paciente es el modelo usuario -->
			<v-select id="patient_id" :options="$page.props.patients" v-model="selectedPatient" :reduce="patient => patient.id" label="name" :clearable="false" />

			<InputError class="mt-2" :message="form.errors.patient_id" />
		</div>

		<!-- choose specialty -->
		<div class="mt-4 ">
			<InputLabel for="specialty_id" value="Specialty" />

			<v-select id="specialty_id" :options="$page.props.specialties" v-model="specialtyChange" :reduce="specialty => specialty.id" label="name" :clearable="false" />

			<InputError class="mt-2" :message="form.errors.specialty_id" />
		</div>

		<!-- doctor_id -->
		<div class="mt-4">
			<InputLabel for="doctor_id" value="Doctor" />

			<v-select id="doctor_id" :options="doctors" v-model="form.doctor_id" :reduce="doctor => doctor.id" label="user_name" :clearable="false" />

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
@import "vue-select/dist/vue-select.css";
@import "flatpickr/dist/flatpickr.min.css";
.flatpickr-wrapper {
	width: 100%;
}
</style>

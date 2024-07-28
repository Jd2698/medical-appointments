<script setup>
	import { onMounted, ref, watch } from "vue";
	import flatpickr from "flatpickr";
	import { Spanish } from "flatpickr/dist/l10n/es";
	import "flatpickr/dist/flatpickr.min.css";
	import { useForm } from "@inertiajs/vue3";

	const dateInput = ref(null);
	const timeInput = ref(null);
	const defaultDate = ref("2024-07-28");
	const defaultTime = ref("07:00");
	const openModal = ref(false);

	const form = useForm({
		date: "2024-07-26",
		time: "07:00",
	});

	// time validation
	onMounted(() => {
		flatpickr(dateInput.value, {
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

		flatpickr(timeInput.value, {
			defaultDate: "07:00",
			enableTime: true,
			noCalendar: true,
			dateFormat: "H:i",
			time_24hr: false,
			minTime: "07:00",
			maxTime: "18:00",
		});
	});

	watch(defaultDate, (newValue) => {
		form.date = defaultDate.value;
		console.log(form.date);
	});

	watch(defaultTime, (newValue) => {
		form.time = defaultTime.value;
		console.log(form.time);
	});
</script>

<template>

	<div class="flex justify-center gap-4 p-4">
		<input ref="dateInput" v-model="defaultDate" type="text" placeholder="Select Date..." class="rounded-md focus:border-cyan-600">
		<input ref="timeInput" v-model="defaultTime" type="text" placeholder="Select Time..." class="rounded-md">
	</div>
</template>

<style>
input:focus {
	box-shadow: none !important;
}
</style>

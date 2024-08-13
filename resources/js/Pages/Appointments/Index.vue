<script setup>
	import { usePage, router } from "@inertiajs/vue3";
	import { ref } from "vue";

	// components
	import DashboardLayout from "@/Layouts/DashboardLayout.vue";
	import DialogModal from "@/Components/DialogModal.vue";
	import AppointmentForm from "@/Pages/Appointments/Modals/AppointmentForm.vue";
	import AppointmentUpdateStatus from "@/Pages/Appointments/Modals/AppointmentUpdateStatus.vue";

	import FullCalendar from "@fullcalendar/vue3";
	import esLocale from "@fullcalendar/core/locales/es";
	import dayGridPlugin from "@fullcalendar/daygrid";
	import interactionPlugin from "@fullcalendar/interaction";

	import { dateFormat, appointmentDateFormat } from "@/Helpers/calendar.js";

	const props = defineProps({ appointments: Object });
	// console.log(props.appointments);
	const isShowModalOpen = ref(false);
	const isFormModalOpen = ref(false);
	const selectedAppointment = ref(null);
	const selectedDate = ref(null);

	const handleDateClick = (arg) => {
		// console.log(arg.date);

		const currentDate = new Date();
		const appointmentDate = arg.date;

		// formatear fechas -> 2024-07-22
		const appointmentDateFormat = dateFormat(appointmentDate);

		// validar fecha futura
		if (appointmentDate > currentDate) {
			isFormModalOpen.value = true;
			selectedDate.value = appointmentDateFormat;
		}
	};

	// show appointment
	const handleEventClick = (info) => {
		isShowModalOpen.value = true;

		const eventDate = info.event.start;

		// 24 de julio del 2024 5:00
		const dateFormat = appointmentDateFormat(eventDate);

		selectedAppointment.value = {
			title: info.event.title,
			fecha: dateFormat,
			allData: info.event.extendedProps.allData,
		};
	};

	const calendarAppointmentsOptions = props.appointments.map((cita) => ({
		title: `${cita.status} / ${cita.start_time}`,
		start: cita.date,
		allData: cita,
	}));

	const calendarOptions = {
		plugins: [dayGridPlugin, interactionPlugin],
		locales: [esLocale],
		dateClick: handleDateClick,
		eventClick: handleEventClick,
		events: calendarAppointmentsOptions,
		headerToolbar: {
			left: "prev,next today",
			center: "title",
			right: "dayGridMonth,dayGridWeek,dayGridDay",
		},
		dayMaxEventRows: true,
		views: {
			timeGrid: {
				dayMaxEventRows: 3,
			},
		},
	};

	const closeModal = (statusObject) => {
		isShowModalOpen.value = false;
		isFormModalOpen.value = false;

		if (statusObject && statusObject.status) {
			Swal.fire({
				icon: statusObject.type,
				title: statusObject.message,
				showConfirmButton: false,
				timer: 1500,
			});
			router.get(route("appointments.index"));
		}
	};
</script>

<template>
	<DashboardLayout title="Admin / appointments">
		<template #mainHeader>
		</template>

		<FullCalendar :options='calendarOptions' />

		<!-- show appointment -->
		<DialogModal :show="isShowModalOpen" @close='closeModal' maxWidth="xl">
			<template #title>
				Información de la cita
			</template>

			<template #content>
				<AppointmentUpdateStatus @close='closeModal' :selectedAppointment="selectedAppointment" />
			</template>
		</DialogModal>

		<!-- create appointment -->
		<DialogModal :show="isFormModalOpen" @close='closeModal' maxWidth="xl">
			<template #title>
				Crear cita
			</template>

			<template #content>
				<AppointmentForm @close='closeModal' :date="selectedDate" />
			</template>
		</DialogModal>
	</DashboardLayout>
</template>

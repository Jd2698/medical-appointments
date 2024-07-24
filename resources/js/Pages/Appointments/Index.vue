<script setup>
	import { usePage, router } from "@inertiajs/vue3";
	import { ref } from "vue";

	import DashboardLayout from "@/Layouts/DashboardLayout.vue";
	import DialogModal from "@/Components/DialogModal.vue";
	import AppointmentForm from "@/Pages/Appointments/Modals/AppointmentForm.vue";
	import AppointmentUpdateStatus from "@/Pages/Appointments/Modals/AppointmentUpdateStatus.vue";
	import { FwbBreadcrumb, FwbBreadcrumbItem } from "flowbite-vue";

	import FullCalendar from "@fullcalendar/vue3";
	import esLocale from "@fullcalendar/core/locales/es";
	import dayGridPlugin from "@fullcalendar/daygrid";
	import interactionPlugin from "@fullcalendar/interaction";

	import { dateFormat, appointmentDateFormat } from "@/Helpers/calendar.js";

	const props = defineProps({ appointments: Object });
	const isShowModalOpen = ref(false);
	const isFormModalOpen = ref(false);
	const selectedAppointment = ref(null);
	const selectedDate = ref(null);
	const existingAppointmentsModalForm = ref(null);

	// console.log(props.appointments[0].date);

	const handleDateClick = (arg) => {
		// console.log(arg.date);

		const currentDate = new Date();
		const appointmentDate = arg.date;

		// formatear fechas -> 2024-07-22
		const currentDateFormat = dateFormat(currentDate);
		const appointmentDateFormat = dateFormat(appointmentDate);

		console.log(currentDateFormat);
		console.log(appointmentDateFormat);

		// validar fecha futura
		if (appointmentDate > currentDate) {
			isFormModalOpen.value = true;
			// obtener citas existentes
			// const existingAppointments = props.appointments.filter((cita) =>
			// 	cita.start_time.startsWith(appointmentDateFormat.substring(0, 10))
			// );
			selectedDate.value = appointmentDateFormat;
			// existingAppointmentsModalForm.value = existingAppointments;
		}
	};

	// show appointment
	const handleEventClick = (info) => {
		isShowModalOpen.value = true;

		const eventDate = info.event.start;

		// 24 de julio del 2024 5:00
		const dateFormat = appointmentDateFormat(eventDate);

		selectedAppointment.value = {
			estado: info.event.title,
			fecha: dateFormat,
			paciente: info.event.extendedProps.patientName,
			doctor: info.event.extendedProps.doctorName,
			allData: info.event.extendedProps.allData,
		};
	};

	const calendarAppointmentsOptions = props.appointments.map((cita) => ({
		title: cita.status,
		start: cita.date,
		patientName: cita.patient.user.name,
		doctorName: cita.doctor.user.name,
		allData: cita,
	}));

	const calendarOptions = {
		plugins: [dayGridPlugin, interactionPlugin],
		locales: [esLocale],
		// initialView: "dayGridMonth",
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

	const closeModal = (data) => {
		isShowModalOpen.value = false;
		isFormModalOpen.value = false;

		if (data && data.status) {
			router.get("/dashboard");
		}
	};
</script>

<template>
	<DashboardLayout title="Dashboard">
		<template #mainHeader>
			<fwb-breadcrumb>
				<fwb-breadcrumb-item>
					Dashboard
				</fwb-breadcrumb-item>
			</fwb-breadcrumb>
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
				<AppointmentForm @close='closeModal' :date="selectedDate" :dates="existingAppointmentsModalForm" />
			</template>
		</DialogModal>
	</DashboardLayout>
</template>

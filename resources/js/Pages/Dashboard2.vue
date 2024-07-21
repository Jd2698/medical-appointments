<script setup>
	import DashboardLayout from "@/Layouts/DashboardLayout.vue";
	import { FwbBreadcrumb, FwbBreadcrumbItem } from "flowbite-vue";
	import DialogModal from "@/Components/DialogModal.vue";
	import AppointmentForm from "@/Pages/Appointments/AppointmentForm.vue";

	import FullCalendar from "@fullcalendar/vue3";
	import dayGridPlugin from "@fullcalendar/daygrid";
	import interactionPlugin from "@fullcalendar/interaction";
	import { usePage, router } from "@inertiajs/vue3";
	import { ref } from "vue";

	import { dateFormat, appointmentDateFormat } from "@/Helpers/calendar.js";

	const props = defineProps({ appointments: Object });
	const isShowModalOpen = ref(false);
	const isFormModalOpen = ref(false);
	const selectedAppointment = ref(null);
	const selectedDate = ref(null);
	const existingAppointmentsModalForm = ref(null);

	// console.log(props.appointments[0].appointment_date);

	const handleDateClick = (arg) => {
		// console.log(arg);

		const currentDate = new Date();
		const appointmentDate = arg.date;

		// formatear fechas -> 2024-07-22 05:22
		const currentDateFormat = dateFormat(currentDate);
		const appointmentDateFormat = dateFormat(appointmentDate);

		// validar fecha futura
		if (appointmentDate > currentDate) {
			isFormModalOpen.value = true;

			// obtener citas existentes
			const existingAppointments = props.appointments.filter((cita) =>
				cita.appointment_date.startsWith(
					appointmentDateFormat.substring(0, 10)
				)
			);

			selectedDate.value = appointmentDateFormat;
			existingAppointmentsModalForm.value = existingAppointments;
		}
	};

	// show appointment
	const handleEventClick = (info) => {
		isShowModalOpen.value = true;

		const eventDate = info.event.start;

		// 24 de julio del 2024 5:00
		const dateFormat = appointmentDateFormat(eventDate);

		selectedAppointment.value = {
			Estado: info.event.title,
			Fecha: dateFormat,
			Paciente: info.event.extendedProps.patientName,
			Doctor: info.event.extendedProps.doctorName,
		};
	};

	const calendarAppointmentsOptions = props.appointments.map((cita) => ({
		title: cita.status,
		start: new Date(cita.appointment_date),
		patientName: cita.patient.user.name,
		doctorName: cita.doctor.user.name,
	}));

	const calendarOptions = {
		plugins: [dayGridPlugin, interactionPlugin],
		initialView: "dayGridMonth",
		dateClick: handleDateClick,
		eventClick: handleEventClick,
		events: calendarAppointmentsOptions,
		headerToolbar: {
			left: "prev,next today",
			center: "title",
			right: "dayGridMonth,dayGridWeek,dayGridDay",
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
				<div class="mt-4 space-y-4">
					<div v-for="(value, key) in selectedAppointment" :key="key" class="text-center">
						<span class="block">{{key}}</span>
						<span class="text-lg">{{value}}</span>
					</div>
				</div>
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

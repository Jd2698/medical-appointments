<script setup>
	import DashboardLayout from "@/Layouts/DashboardLayout.vue";
	import { FwbBreadcrumb, FwbBreadcrumbItem } from "flowbite-vue";
	import DialogModal from "@/Components/DialogModal.vue";

	import FullCalendar from "@fullcalendar/vue3";
	import dayGridPlugin from "@fullcalendar/daygrid";
	import interactionPlugin from "@fullcalendar/interaction";
	import { usePage } from "@inertiajs/vue3";
	import { ref } from "vue";

	const props = defineProps({ appointments: Object });
	const isOpenModal = ref(false);
	const appointmentDataModal = ref(null);

	console.log(props.appointments);

	const handleDateClick = (arg) => {
		console.log(arg);
	};

	const handleEventClick = (info) => {
		isOpenModal.value = true;

		const fecha = info.event.start;
		const dia = fecha.getDate();
		const mes = fecha.toLocaleString("es-ES", { month: "long" });
		const año = fecha.getFullYear();
		const hora = fecha.getHours();
		const minutos = fecha.getMinutes().toString().padStart(2, "0");

		const fechaFormateada = `${dia} de ${mes} del ${año} ${hora}:${minutos}`;
		console.log(fechaFormateada);

		appointmentDataModal.value = {
			Estado: info.event.title,
			Fecha: fechaFormateada,
			Paciente: info.event.extendedProps.patientName,
			Doctor: info.event.extendedProps.doctorName,
		};
	};

	const appointments = props.appointments.map((cita) => ({
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
		events: appointments,
		headerToolbar: {
			left: "prev,next today",
			center: "title",
			right: "dayGridMonth,dayGridWeek,dayGridDay",
		},
	};

	const closeModal = () => {
		isOpenModal.value = false;
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

		<DialogModal :show="isOpenModal" @close='closeModal' maxWidth="xl">
			<template #title>
				Información de la cita
			</template>

			<template #content>
				<div class="mt-4 space-y-4">
					<div v-for="(value, key) in appointmentDataModal" :key="key" class="text-center">
						<span class="block">{{key}}</span>
						<span class="text-lg">{{value}}</span>
					</div>
				</div>
			</template>
		</DialogModal>
	</DashboardLayout>
</template>

<script setup>
	import { Link, useForm, usePage } from "@inertiajs/vue3";
	import { computed, onMounted, ref } from "vue";

	import { Form, Field, ErrorMessage } from "vee-validate";
	import * as Yup from "yup";

	// components
	import InputError from "@/Components/InputError.vue";
	import InputLabel from "@/Components/InputLabel.vue";
	import PrimaryButton from "@/Components/PrimaryButton.vue";
	import TextInput from "@/Components/TextInput.vue";

	// timepicker - datepicker
	import flatpickr from "flatpickr";
	import { Spanish } from "flatpickr/dist/l10n/es";

	const props = defineProps({ doctor: Object });
	// console.log(usePage().props.specialties.map((s) => s.id));
	const emit = defineEmits(["close"]);

	const isCreate = props.doctor ? false : true;
	const optionsUserStatus = [
		{
			name: "active",
			status: 1,
		},
		{
			name: "inactive",
			status: 0,
		},
	];
	const dateInput = ref(null);

	const form = useForm({
		name: props.doctor ? props.doctor.user.name : "",
		documento_identidad: props.doctor
			? props.doctor.user.documento_identidad
			: "",
		gender: props.doctor ? props.doctor.user.gender : "select",
		birthdate: props.doctor ? props.doctor.user.birthdate : "",
		address: props.doctor ? props.doctor.user.address : "",
		phone: props.doctor ? props.doctor.user.phone : "",
		email: props.doctor ? props.doctor.user.email : "",
		specialty_id: props.doctor ? props.doctor.specialty.id : "select",
		password: "",
		password_confirmation: "",
		is_active: props.doctor ? props.doctor.user.is_active : "1",
	});

	const getMaxDate = () => {
		const maxDate = new Date();
		maxDate.setFullYear(maxDate.getFullYear() - 18);
		return maxDate;
	};

	const addflatPickr = () => {
		// flatpickr to date
		flatpickr(dateInput.value, {
			static: true,
			locale: Spanish,
			maxDate: getMaxDate(),
			enableTime: false,
			dateFormat: "Y-m-d",
		});
	};

	const passwordReset = () => {
		(form.password = ""), (form.password_confirmation = "");
	};

	onMounted(() => {
		addflatPickr();
	});

	const submit = () => {
		if (!props.doctor) {
			form.password = form.documento_identidad;
			form.password_confirmation = form.documento_identidad;

			form.post(route("doctors.store"), {
				onSuccess: () => {
					form.reset();
					emit("close", {
						status: true,
						type: "success",
						message: "Doctor has been created",
					});
				},
			});
		} else {
			form.put(route("doctors.update", props.doctor.id), {
				onSuccess: () => {
					form.reset();
					emit("close", {
						status: true,
						type: "success",
						message: "Doctor has been updated",
					});
				},
			});
		}
	};

	const schema = computed(() => {
		return Yup.object({
			name: Yup.string()
				.required("Name is required")
				.max(40, "Name must be at most 40 characters"),

			documento_identidad: Yup.string()
				.required("Identity document is required")
				.min(8, "Identity document must be at least 8 characters")
				.max(10, "Identity document must be at most 10 characters"),

			gender: Yup.string()
				.oneOf(
					usePage().props.genders,
					`Gender must be one of: ${usePage().props.genders.join(", ")}`
				)
				.required("Gender is required"),

			birthdate: Yup.date()
				.required("Birthdate is required")
				.typeError("Invalid date format"),

			email: Yup.string()
				.required("Email is required")
				.email("Invalid email format"),

			address: Yup.string()
				.required("Address is required")
				.max(30, "Address must be at most 30 characters"),

			phone: Yup.string()
				.required("Phone number is required")
				.min(10, "Phone number must be at least 10 characters")
				.max(10, "Phone number must be at most 10 characters"),

			password: Yup.string().notRequired(),

			password_confirmation: Yup.string().oneOf(
				[Yup.ref("password"), null],
				"Passwords must match"
			),
			specialty: Yup.number()
				.required("Gender is required")
				.typeError("Specialty does not exist")
				.oneOf(
					usePage().props.specialties.map((s) => s.id),
					"Specialty does not exist"
				),
			is_active: Yup.number()
				.notRequired()
				.typeError("Invalid status")
				.oneOf([0, 1], "Invalid status"),
		});
	});

	const inputClasses =
		"mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm";
</script>

<template>

	<Form @submit="submit" :validation-schema="schema" class="grid grid-cols-2 gap-2">
		<!-- {{$page.props.genders}} -->

		<!-- name -->
		<div class="">
			<InputLabel for="name" value="name" />
			<Field id="name" name="name" :validateOnInput="true" v-model="form.name" type="text" placeholder="Ej: juana mendez" :class="inputClasses" />

			<ErrorMessage name="name" class="text-red-800" />
			<InputError class="mt-2" :message="form.errors.name" />
		</div>

		<!-- documento -->
		<div class="">
			<InputLabel for="documento_identidad" value="Documento" />
			<Field id="documento_identidad" name="documento_identidad" :validateOnInput="true" v-model="form.documento_identidad" type="text" placeholder="Ej: 1004233281" :class="inputClasses" />

			<ErrorMessage name="documento_identidad" class="text-red-800" />
			<InputError class="mt-2" :message="form.errors.documento_identidad" />
		</div>

		<!-- gender -->
		<div class="mt-4">
			<Field name="gender" v-model="form.gender" v-slot="{ field, errorMessage, valid }">
				<InputLabel for="gender" value="Gender" />

				<v-select id="gender" :options="$page.props.genders" v-model="form.gender" :clearable="false" v-bind="field" />

				<span class="text-red-800" v-if="!valid">{{ errorMessage }}</span>
			</Field>

			<InputError class="mt-2" :message="form.errors.gender" />
		</div>

		<!-- birthdate -->
		<div class="mt-4">
			<Field name="birthdate" v-model="form.birthdate" v-slot="{ field, errorMessage, valid }">
				<InputLabel for="birthdate" value="Birthdate" />

				<input ref="dateInput" type="text" v-model="form.birthdate" id="birthdate" :class="inputClasses" v-bind="field">

				<span class="text-red-800" v-if="!valid">{{ errorMessage }}</span>
			</Field>

			<InputError class="mt-2" :message="form.errors.birthdate" />
		</div>

		<!-- address -->
		<div class="mt-4">
			<InputLabel for="address" value="Address" />
			<Field id="address" name="address" :validateOnInput="true" v-model="form.address" type="text" placeholder="Ej: cll 2 #84-2" :class="inputClasses" />

			<ErrorMessage name="address" class="text-red-800" />
			<InputError class="mt-2" :message="form.errors.address" />
		</div>

		<!-- phone -->
		<div class="mt-4">
			<InputLabel for="phone" value="Phone" />
			<Field id="phone" name="phone" :validateOnInput="true" v-model="form.phone" type="text" placeholder="Ej: 3186460802" :class="inputClasses" />

			<ErrorMessage name="phone" class="text-red-800" />
			<InputError class="mt-2" :message="form.errors.phone" />
		</div>

		<!-- email -->
		<div class="mt-4 col-span-2 md:col-span-1">
			<InputLabel for="email" value="Email" />
			<Field id="email" name="email" :validateOnInput="true" v-model="form.email" type="text" placeholder="Ej: example@gmail.com" :class="inputClasses" />

			<ErrorMessage name="email" class="text-red-800" />
			<InputError class="mt-2" :message="form.errors.email" />
		</div>

		<!-- specialty -->
		<div class="mt-4 col-span-2 md:col-span-1">
			<Field name="specialty" v-model="form.specialty_id" v-slot="{ field, errorMessage, valid }">
				<InputLabel for="specialty" value="specialty" />

				<v-select v-bind="field" id="specialty_id" :options="$page.props.specialties" v-model="form.specialty_id" :reduce="specialty => specialty.id" label="name" :clearable="false" />

				<span class="text-red-800" v-if="!valid">{{ errorMessage }}</span>
			</Field>

			<InputError class="mt-2" :message="form.errors.gender" />
		</div>

		<!-- password -->
		<div class="mt-4" v-if="!isCreate">
			<InputLabel for="password" value="Password" />

			<Field id="password" name="password" :validateOnInput="true" v-model="form.password" type="password" :class="inputClasses" />

			<ErrorMessage name="password" class="text-red-800" />
			<InputError class="mt-2" :message="form.errors.password" />
		</div>

		<!-- password_confirmation -->
		<div class="mt-4" v-if="!isCreate">
			<InputLabel for="password_confirmation" value="Confirm Password" />

			<Field id="password_confirmation" name="password_confirmation" :validateOnInput="true" v-model="form.password_confirmation" type="password" :class="inputClasses" />

			<ErrorMessage name="password_confirmation" class="text-red-800" />
			<InputError class="mt-2" :message="form.errors.password_confirmation" />
		</div>

		<!-- is_active -->
		<div class="mt-4" v-if="!isCreate">
			<Field name="is_active" v-model="form.is_active" v-slot="{ field, errorMessage, valid }">
				<InputLabel for="is_active" value="Status" />

				<v-select v-bind="field" id="is_active" :options="optionsUserStatus" :reduce="status => status.status" label="name" v-model="form.is_active" :clearable="false" />
				<span class="text-red-800" v-if="!valid">{{ errorMessage }}</span>
			</Field>

			<InputError class="mt-2" :message="form.errors.is_active" />
		</div>

		<!-- roles (completar) -->
		<!-- <div class="mt-4" v-if="!isCreate">
			<InputLabel for="role" value="Role" />
			<v-select id="role" :options="$page.props.roles" v-model="form.role" :clearable="false" />
			<InputError class="mt-2" :message="form.errors.role" />
		</div> -->

		<div class="col-span-2 flex items-center justify-end mt-4">
			<PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Submit
			</PrimaryButton>
		</div>
	</form>
</template>
<style >
@import "vue-select/dist/vue-select.css";
@import "flatpickr/dist/flatpickr.min.css";
.flatpickr-wrapper {
	width: 100%;
}
</style>

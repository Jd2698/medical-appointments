<script setup>
	import { Link, useForm, usePage } from "@inertiajs/vue3";
	import { computed, onMounted, ref, watch } from "vue";

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

	const props = defineProps({ user: Object });
	// console.log(props.user);
	const emit = defineEmits(["close"]);

	const isCreate = props.user ? false : true;
	const dateInput = ref();
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

	const form = useForm({
		name: props.user ? props.user.name : "",
		documento_identidad: props.user ? props.user.documento_identidad : "",
		gender: props.user ? props.user.gender : "select",
		birthdate: props.user ? props.user.birthdate : "",
		address: props.user ? props.user.address : "",
		phone: props.user ? props.user.phone : "",
		email: props.user ? props.user.email : "",
		role:
			props.user && props.user.roles[0]
				? props.user.roles.map((r) => r.name)
				: ["admin"],
		password: "",
		password_confirmation: "",
		is_active: props.user ? props.user.is_active : "0",
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
		getMaxDate();
		addflatPickr();
	});

	const submit = () => {
		// console.log(form.role);
		// return;

		if (!props.user) {
			form.password = form.documento_identidad;
			form.password_confirmation = form.documento_identidad;

			form.post(route("users.store"), {
				onSuccess: () => {
					form.reset();
					emit("close", {
						status: true,
						type: "success",
						message: "User has been created",
					});
				},
			});
		} else {
			form.put(route("users.update", props.user.id), {
				onSuccess: () => {
					form.reset();
					emit("close", {
						status: true,
						type: "success",
						message: "User has been updated",
					});
				},
			});
		}
	};

	const schema = computed(() => {
		// Validar la contraseña si existe un usuario
		let passwordYup = Yup.string();
		isCreate ? passwordYup.required().min(8) : passwordYup.notRequired();

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

			password: passwordYup,

			password_confirmation: Yup.string().oneOf(
				[Yup.ref("password"), null],
				"Passwords must match"
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
		<div class="mt-4 col-span-2">
			<InputLabel for="email" value="Email" />
			<Field id="email" name="email" :validateOnInput="true" v-model="form.email" type="text" placeholder="Ej: example@gmail.com" :class="inputClasses" />

			<ErrorMessage name="email" class="text-red-800" />
			<InputError class="mt-2" :message="form.errors.email" />
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

		<!-- roles -->
		<div class="mt-4" v-if="isCreate || user && user.roles[0].name == 'doctor'">
			<InputLabel for="role" value="Role" />

			<!-- solo para mostrar -->
			<v-select v-if="isCreate" id="role" :options="['admin']" v-model="form.role" :clearable="false" disabled />

			<!-- un doctor puede tener un rol más -->
			<v-select v-else-if="user && user.roles[0].name == 'doctor'" id="role" :options="['doctor', 'patient']" v-model="form.role" :clearable="false" multiple />

			<InputError class="mt-2" :message="form.errors.role" />
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

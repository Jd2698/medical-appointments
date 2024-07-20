<script setup>
	import { Link, useForm, usePage } from "@inertiajs/vue3";
	import Checkbox from "@/Components/Checkbox.vue";
	import InputError from "@/Components/InputError.vue";
	import InputLabel from "@/Components/InputLabel.vue";
	import PrimaryButton from "@/Components/PrimaryButton.vue";
	import TextInput from "@/Components/TextInput.vue";
	import ActionMessage from "@/Components/ActionMessage.vue";

	const props = defineProps({ doctor: Object });
	const emit = defineEmits(["close"]);
	const isCreate = props.doctor ? false : true;

	// console.log(usePage().props);

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
		specialty: props.doctor ? props.doctor.specialty : "",
		role:
			props.doctor && props.doctor.user.roles[0]
				? props.doctor.user.roles[0].name
				: "doctor",
		password: "",
		password_confirmation: "",
	});

	const passwordReset = () => {
		(form.password = ""), (form.password_confirmation = "");
	};

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
						message: "User has been created",
					});
				},
			});
		} else {
			form.put(route("doctors.update", props.doctor), {
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
</script>

<template>

	<form @submit.prevent="submit" class="grid grid-cols-2 gap-2">
		<!-- {{$page.props.genders}} -->

		<!-- name -->
		<div class="">
			<InputLabel for="name" value="Name" />
			<TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" autofocus autocomplete="name" />
			<InputError class="mt-2" :message="form.errors.name" />
		</div>

		<!-- documento -->
		<div class="">
			<InputLabel for="documento_identidad" value="Documento" />
			<TextInput id="documento_identidad" v-model="form.documento_identidad" type="text" class="mt-1 block w-full" autofocus autocomplete="documento_identidad" />
			<InputError class="mt-2" :message="form.errors.documento_identidad" />
		</div>

		<!-- gender (completar) -->
		<div class="mt-4">
			<InputLabel for="gender" value="Gender" />
			<select v-model="form.gender" name="gender" id="gender" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
				<template v-for="gender in $page.props.genders" :key="gender">
					<option :value="gender">{{ gender[0] + gender.slice(1, gender.length).toLowerCase() }}</option>
				</template>
				<option value="select" selected disabled>SELECT</option>
			</select>
			<InputError class="mt-2" :message="form.errors.gender" />
		</div>

		<!-- birthdate (completar) -->
		<div class="mt-4">
			<InputLabel for="birthdate" value="Birthdate" />
			<input type="date" v-model="form.birthdate" id="birthdate" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
			<InputError class="mt-2" :message="form.errors.birthdate" />
		</div>

		<!-- address -->
		<div class="mt-4">
			<InputLabel for="address" value="Address" />
			<TextInput id="address" v-model="form.address" type="text" class="mt-1 block w-full" />
			<InputError class="mt-2" :message="form.errors.address" />
		</div>

		<!-- phone -->
		<div class="mt-4">
			<InputLabel for="phone" value="Phone" />
			<TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" />
			<InputError class="mt-2" :message="form.errors.phone" />
		</div>

		<!-- email -->
		<div class="mt-4 col-span-2 md:col-span-1">
			<InputLabel for="email" value="Email" />
			<TextInput id="email" v-model="form.email" type="text" class="mt-1 block w-full" />
			<InputError class="mt-2" :message="form.errors.email" />
		</div>

		<!-- specialty -->
		<div class="mt-4 col-span-2 md:col-span-1">
			<InputLabel for="specialty" value="specialty" />
			<TextInput id="specialty" v-model="form.specialty" type="text" class="mt-1 block w-full" autofocus autocomplete="specialty" />
			<InputError class="mt-2" :message="form.errors.specialty" />
		</div>

		<!-- password -->
		<div class="mt-4" v-if="!isCreate">
			<InputLabel for="password" value="Password" />
			<TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
			<InputError class="mt-2" :message="form.errors.password" />
		</div>

		<!-- password_confirmation -->
		<div class="mt-4" v-if="!isCreate">
			<InputLabel for="password_confirmation" value="Confirm Password" />
			<TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
			<InputError class="mt-2" :message="form.errors.password_confirmation" />
		</div>

		<!-- roles (completar) -->
		<div class="mt-4" v-if="!isCreate">
			<InputLabel for="role" value="Role" />
			<select v-model="form.role" name="role" id="role" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
				<option value="admin">Admin</option>
				<option value="client">Client</option>
				<option value="doctor">Doctor</option>
			</select>
		</div>

		<div class="col-span-2 flex items-center justify-end mt-4">
			<PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Submit
			</PrimaryButton>
		</div>
	</form>
</template>

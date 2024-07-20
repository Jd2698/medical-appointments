<script setup>
	import { Link, useForm, usePage } from "@inertiajs/vue3";
	import Checkbox from "@/Components/Checkbox.vue";
	import InputError from "@/Components/InputError.vue";
	import InputLabel from "@/Components/InputLabel.vue";
	import PrimaryButton from "@/Components/PrimaryButton.vue";
	import TextInput from "@/Components/TextInput.vue";
	import ActionMessage from "@/Components/ActionMessage.vue";

	const props = defineProps({ user: Object });
	const emit = defineEmits(["close"]);
	const isCreate = props.user ? false : true;

	const form = useForm({
		name: props.user ? props.user.name : "",
		documento_identidad: props.user ? props.user.documento_identidad : "",
		gender: props.user ? props.user.gender : "select",
		birthdate: props.user ? props.user.birthdate : "",
		address: props.user ? props.user.address : "",
		phone: props.user ? props.user.phone : "",
		email: props.user ? props.user.email : "",
		role:
			props.user && props.user.roles[0] ? props.user.roles[0].name : "client",
		password: "",
		password_confirmation: "",
	});

	const passwordReset = () => {
		(form.password = ""), (form.password_confirmation = "");
	};

	const submit = () => {
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
			form.put(route("users.update", props.user), {
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
		<div class="mt-4 col-span-2">
			<InputLabel for="email" value="Email" />
			<TextInput id="email" v-model="form.email" type="text" class="mt-1 block w-full" />
			<InputError class="mt-2" :message="form.errors.email" />
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
		<div class="mt-4">
			<InputLabel for="role" value="Role" />
			<select v-model="form.role" name="role" id="role" class="mt-1 block w-full border-gray-300 focus:border-main-700 focus:ring-main-700 rounded-md shadow-sm">
				<option value="admin">Admin</option>
				<option value="client">Client</option>
			</select>
		</div>

		<div class="col-span-2 flex items-center justify-end mt-4">
			<PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Submit
			</PrimaryButton>
		</div>
	</form>
</template>

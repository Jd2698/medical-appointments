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

	const props = defineProps({ specialty: Object });
	// console.log(props.specialty);
	const emit = defineEmits(["close"]);

	const isCreate = props.specialty ? false : true;

	const optionsspecialtyStatus = [
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
		name: props.specialty ? props.specialty.name : "",
		is_active: props.specialty ? props.specialty.is_active : "1",
	});

	const submit = () => {
		if (!props.specialty) {
			form.post(route("specialties.store"), {
				onSuccess: () => {
					form.reset();
					emit("close", {
						status: true,
						type: "success",
						message: "specialty has been created",
					});
				},
			});
		} else {
			form.put(route("specialties.update", props.specialty.id), {
				onSuccess: () => {
					form.reset();
					emit("close", {
						status: true,
						type: "success",
						message: "specialty has been updated",
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

	<Form @submit="submit" :validation-schema="schema" class="">
		<!-- {{$page.props.genders}} -->

		<!-- name -->
		<div class="">
			<InputLabel for="name" value="name" />
			<Field id="name" name="name" :validateOnInput="true" v-model="form.name" type="text" placeholder="Ej: pediatra" :class="inputClasses" />

			<ErrorMessage name="name" class="text-red-800" />
			<InputError class="mt-2" :message="form.errors.name" />
		</div>

		<!-- is_active -->
		<div class="mt-4" v-if="!isCreate">
			<Field name="is_active" v-model="form.is_active" v-slot="{ field, errorMessage, valid }">
				<InputLabel for="is_active" value="Status" />

				<v-select v-bind="field" id="is_active" :options="optionsspecialtyStatus" :reduce="status => status.status" label="name" v-model="form.is_active" :clearable="false" />
				<span class="text-red-800" v-if="!valid">{{ errorMessage }}</span>
			</Field>

			<InputError class="mt-2" :message="form.errors.is_active" />
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

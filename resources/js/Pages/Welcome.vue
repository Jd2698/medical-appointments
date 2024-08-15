<script setup>
	import WelcomeLayout from "@/Layouts/WelcomeLayout.vue";
</script>

<template>
	<WelcomeLayout>
		<div class="grid grid-cols-2 gap-4 bg-main-900 p-6 rounded-lg">
			<section class="text-white col-span-2 md:col-span-1 space-y-2">
				<h1 class="text-3xl md:text-5xl font-bold leading-snug ">Organiza tus Citas Médicas de Forma Eficiente.</h1>
				<p>Gestiona tus citas médicas con facilidad desde un solo lugar. Con nuestra plataforma intuitiva, puedes programar y cancelar tus consultas de manera rápida y sencilla.</p>
			</section>
			<div class="col-span-2 md:col-span-1 flex justify-center">
				<img src="/imagen.png" alt="" class="max-h-80 drop-shadow-lg">
			</div>
		</div>
	</WelcomeLayout>
</template>

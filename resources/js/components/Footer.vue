<template>
	<footer class="sm:px-24 px-4">
		<section class="flex flex-row justify-between x">
			<div>
				<h2 class="text-xl font-bold mb-2">Fanny Séraudie</h2>
				<p class="mb-1">Illustratrice &amp; Graphiste</p>
			</div>
			<div class="sm:px-24 px-4 mx-auto text-center mb-4">
				<h2 class="text-xl font-bold mb-2">Suivez-moi</h2>
				<a href="https://www.instagram.com/fannyquidessine_/" target="_blank"
					class="fill-brand-burgundy hover:fill-blue-400 transition-colors duration-200">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7 inline mx-2">
						<!-- Icon from https://github.com/Templarian/MaterialDesign/blob/master/LICENSE -->
						<path
							d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4zm9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8A1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5a5 5 0 0 1-5 5a5 5 0 0 1-5-5a5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3" />
					</svg>
				</a>
				<a href="https://www.linkedin.com/in/fanny-s%C3%A9raudie-749368234/" target="_blank"
					class="fill-brand-burgundy hover:fill-blue-400 transition-colors duration-200">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-8 h-8 inline mx-2">
						<!-- Icon from Material Design Icons by Pictogrammers - https://github.com/Templarian/MaterialDesign/blob/master/LICENSE -->
						<path
							d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93zM6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37z" />
					</svg>

				</a>
			</div>
			<div>
				<h2 class="text-xl font-bold mb-2">Contactez-moi</h2>
				<p>Email: <a href="mailto:fannyseraudie@gmail.com"
						class="text-blue-400 hover:underline">fannyseraudie@gmail.com</a></p>
			</div>
		</section>
		<section>
			<div class="sm:px-24 px-4 mx-auto text-center">
				<p>&copy;{{ currentYear }} Fanny Séraudie Portfolio. Tous droits réservés.</p>
			</div>
			<div class="container mx-auto text-center text-sm mt-2">
				Site web réalisé par <a href="https://www.pauljaguin.com" target="_blank"
					class="text-blue-400 hover:underline">Paul Jaguin</a>
			</div>
		</section>
	</footer>

</template>

<script setup>
const currentYear = new Date().getFullYear()


</script>

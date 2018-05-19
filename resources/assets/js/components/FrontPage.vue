<template>
	<div class="columns is-gapless">
		<div class="column is-one-quarter">
			<front-sidebar></front-sidebar>
		</div>
		<div class="column">
			<front-photo-list></front-photo-list>
		</div>
	</div>
</template>

<script>
	export default {

		data() {
            return {
				photo: {}
            }
        },

        mounted() {
            // this.loadPhotos();
        },

		methods: {
			// loadPhotos($state) {
			// 	this.loadingPhotos = true;
			// 	axios.get('/fotos', { params: this.params})
			// 		.then(response => {
			// 			this.params.page = response.data.current_page;
			// 			this.params.total = response.data.total;
			// 			this.params.per_page = response.data.per_page;
			//
			// 			for (let photo of response.data.data) {
			// 				// photo.date = photo.date ? moment(photo.date).format('DD[/]MM[/]YYYY') : '';
			// 				this.photos.push(photo);
			// 			}
			// 			if ($state) $state.loaded();
			// 			this.params.page++;
			// 		}).catch(error => {
			// 			if ($state) $state.loaded();
			// 			console.error(error);
			// 		});
			// }
		}
    }
</script>

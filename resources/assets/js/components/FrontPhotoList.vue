<template>
    <div class="has-margin-100">
        <div class="columns is-multiline">
			<div v-for="photo of photos" class="column is-one-third">
				<figure class="image is-3by2 is-marginless">
					<router-link :to="'/foto/' + photo.id">
						<img :src="'/fotos/' + photo.id + '/arquivo?tamanho=pequeno&recortar=true'"></img>
					</router-link>
				</figure>
			</div>
		</div>
		<infinite-loading v-if="this.photos.length < params.total" @infinite="loadPhotos"></infinite-loading>
    </div>
</template>

<script>
	import InfiniteLoading from 'vue-infinite-loading';

	export default {

		props: ['filters'],

		data() {
            return {
				cancel: null,
				loadingPhotos: false,
				photos: [],
				params: {
					busca: null,
					page: 1,
					per_page: 21,
					sortBy: 'name',
					sortOrder: 'asc'
				}
            }
        },

        mounted() {
			this.resetComponent();
        },

		methods: {
			loadPhotos($state) {
				this.loadingPhotos = true;
				let cancel = null;

				axios.get('/fotos', {
						params: this.params,
					 	cancelToken: new axios.CancelToken(function executor(c) {
							cancel = c;
						})
					}).then(response => {
						this.params.page = response.data.current_page;
						this.params.total = response.data.total;
						this.params.per_page = response.data.per_page;

						for (let photo of response.data.data) {
							photo.date = photo.date ? new Date(photo.date).toLocaleDateString() : '';
							this.photos.push(photo);
						}
						
						if ($state) $state.loaded();
						this.loadingPhotos = false;
						this.params.page++;
					}).catch(error => {
						if ($state) $state.loaded();
						this.loadingPhotos = false;
						console.error(error);
					});

				this.cancel = cancel;
			},

			resetComponent() {
				if(this.loadingPhotos) {
					this.cancel();
				}
				this.photos = [];
				this.params = {
					busca: this.filters.search,
					cidade: this.filters.cities,
					tag: this.filters.tags,
					tipo: this.filters.types,
					page: 1,
					per_page: 21,
					sortBy: 'name',
					sortOrder: 'asc'
				};
				this.loadPhotos();
			}
		},

		watch: {
			'filters': {
				handler: function (val, oldVal) {
					this.resetComponent();
				},
				deep: true
			}
		}
    }
</script>

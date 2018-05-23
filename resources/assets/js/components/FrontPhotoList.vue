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
				photos: [],
				params: {
					busca: null,
					cidade: null,
					page: 1,
					total: 0,
					per_page: 21,
					sortBy: 'name',
					sortOrder: 'asc'
				}
            }
        },

        mounted() {
			this.loadPhotos();
        },

		methods: {
			loadPhotos($state) {
				this.loadingPhotos = true;
				axios.get('/fotos', { params: this.params })
					.then(response => {
						this.params.page = response.data.current_page;
						this.params.total = response.data.total;
						this.params.per_page = response.data.per_page;

						for (let photo of response.data.data) {
							photo.date = photo.date ? new Date(photo.date).toLocaleDateString() : '';
							this.photos.push(photo);
						}
						if ($state) $state.loaded();
						this.params.page++;
					}).catch(error => {
						if ($state) $state.loaded();
						console.error(error);
					});
			},

			resetComponent() {
				this.photos = [];
				this.params = {
					busca: null,
					cidade: this.filters.cities,
					tags: this.filters.tags,
					tipos: this.filters.types,
					page: 1,
					total: 0,
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

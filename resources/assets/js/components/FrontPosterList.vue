<template>
    <div class="has-margin-100">
        <div class="columns is-multiline">
			<div v-for="poster of posters" class="column is-one-third">
				<div class="poster-card content is-marginless">
					<div class="poster-card-content">
						<p>{{ poster.text }}</p>
						<div v-if="poster.photos[0]" class="poster-info has-text-grey is-size-7">
							<div>{{ poster.photos[0].photographer }}</div>
							<div>{{ poster.photos[0].city }}</div>
							<div>{{ poster.type }}</div>
							<div>{{ poster.tags }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<infinite-loading v-if="this.posters.length < params.total" @infinite="loadPhotos"></infinite-loading>
    </div>
</template>

<script>
	import InfiniteLoading from 'vue-infinite-loading';

	export default {

		props: ['filters'],

		data() {
            return {
				cancel: null,
				loadingPosters: false,
				posters: [],
				params: {
					busca: null,
					mostrarFotos: true,
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

				if(this.loadingPosters) {
					this.cancel();
				}
				let cancel = null;

				this.loadingPosters = true;
				axios.get('/cartazes', {
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
							this.posters.push(photo);
						}

						if ($state) $state.loaded();
						this.loadingPosters = false;
						this.params.page++;
					}).catch(error => {
						if ($state) $state.loaded();
						this.loadingPosters = false;
						console.error(error);
					});

				this.cancel = cancel;
			},

			resetComponent() {
				this.posters = [];
				this.params = {
					busca: this.filters.search,
					cidade: this.filters.cities,
					fotografo: this.filters.photographer,
					genero: this.filters.gender,
					mostrarFotos: true,
					tag: this.filters.tags,
					tipo: this.filters.types,
					page: 1,
					per_page: 21,
					sortBy: 'text',
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

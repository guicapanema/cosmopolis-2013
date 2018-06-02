<template>
    <div class="has-margin-100">
        <div class="columns is-multiline">
			<div v-for="photo of photos" class="column is-one-third">
				<figure class="image is-3by2 is-marginless" @mouseover="photo.active = true" @mouseleave="photo.active = false">
					<router-link :to="'/foto/' + photo.id">
						<img :src="'/fotos/' + photo.id + '/arquivo?tamanho=pequeno&recortar=true'"></img>
						<slider v-if="photo.active && photo.posters.length" animation="fade" :speed="100" :control-btn="false" height="100%" width="100%">
							<slider-item v-for="(poster, index) of photo.posters" :key="index">
								<div style="padding: 1em 0.5em; width: 100%; height: 100%; backgroundColor: rgba(0,0,0,0.4)">
									<p class="has-text-white">{{ poster.text }}</p>
									<div class="poster-info has-text-grey-light is-size-7">
										<div>{{ photo.photographer }}</div>
										<div>{{ photo.city }}</div>
										<div>{{ poster.type }}</div>
										<div>{{ poster.tags }}</div>
									</div>
								</div>
							</slider-item>
						</slider>
					</router-link>
				</figure>
			</div>
		</div>
		<infinite-loading v-if="this.photos.length < params.total" @infinite="loadPhotos"></infinite-loading>
    </div>
</template>

<script>
	import InfiniteLoading from 'vue-infinite-loading';
	import { Slider, SliderItem } from 'vue-easy-slider'

	export default {

		props: ['filters'],

		components: {
    Slider,
    SliderItem
},

		data() {
            return {
				cancel: null,
				loadingPhotos: false,
				photos: [],
				params: {
					busca: null,
					mostrarCartazes: true,
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

				if(this.loadingPhotos) {
					this.cancel();
				}
				let cancel = null;

				this.loadingPhotos = true;
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
							photo.active = false;
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
				this.photos = [];
				this.params = {
					busca: this.filters.search,
					cidade: this.filters.cities,
					fotografo: this.filters.photographer,
					genero: this.filters.gender,
					mostrarCartazes: true,
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

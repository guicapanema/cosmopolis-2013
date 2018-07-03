<template>
    <div class="has-margin-100">
        <div class="columns is-multiline">
			<div v-for="photo of photos" class="column is-one-third">
				<figure class="image is-3by2 is-marginless is-cursor-pointer" @mouseover="photo.active = true" @mouseleave="photo.active = false">
					<img :src="'/fotos/' + photo.id + '/arquivo?tamanho=pequeno&recortar=true'" @click="onPhotoSelect(photo)"></img>
					<slider v-if="photo.active && photo.posters.length" animation="fade" :speed="100" :control-btn="false" height="100%" width="100%">
						<slider-item v-for="(poster, index) of photo.posters" :key="index">
							<div class="slider-overlay"  @click="onPhotoSelect(photo)">
								<p class="has-text-white is-size-5">{{ poster.text }}</p>
								<div class="poster-info has-text-grey-light is-size-6">
									<div v-if="photo.date">{{ photo.date.format('DD/MM/YYYY') }}</div>
									<div v-if="photo.city">{{ photo.city }}</div>
									<div v-if="photo.photographer">Foto: {{ photo.photographer }}</div>
								</div>
							</div>
						</slider-item>
					</slider>
				</figure>
			</div>
			<!-- <div v-if="(photos.length === 0) && !loadingPhotos" class="column">
				<div class="content has-text-centered has-margin-top-200">
				</div>
			</div> -->
		</div>
		<infinite-loading ref="infiniteLoading" @infinite="loadPhotos" spinner="circles">
			<span slot="no-more">
				Fim dos resultados
			</span>
			<span slot="no-results">
				Não há fotos que atendam aos filtros selecionados
			</span>
		</infinite-loading>
    </div>
</template>

<script>
	import InfiniteLoading from 'vue-infinite-loading';
	import { Slider, SliderItem } from 'vue-easy-slider';
	import dayjs from 'dayjs';
	import 'dayjs/locale/pt-br';
	dayjs.locale('pt-br');

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
					esconderVazias: true,
					page: 1,
					per_page: 21,
					sortBy: 'date',
					sortOrder: 'asc',
					total: 0
				}
            }
        },

		computed: {
			sortBy() {
				return this.filters.search || this.filters.cities.length || this.filters.dates.length || this.filters.photographer || this.filters.gender || this.filters.tags.length || this.filters.types.length ? 'date' : null;
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
						this.$emit('total', this.params.total);
						this.params.per_page = response.data.per_page;

						for (let photo of response.data.data) {
							photo.date = photo.date ? dayjs(photo.date) : null;
							photo.active = false;
							this.photos.push(photo);
						}

						if ($state) {
							if(response.data.data.length === 0) {
								$state.complete();
							} else {
								$state.loaded();
							}
						}

						this.loadingPhotos = false;
						this.params.page++;
					}).catch(error => {
						if ($state) $state.loaded();
						this.loadingPhotos = false;
						console.error(error);
					});

				this.cancel = cancel;
			},

			onPhotoSelect(photo) {
				let index = this.photos.findIndex(innerPhoto => innerPhoto.id === photo.id);
				let page = Math.ceil((index + 1) / this.params.per_page);

				this.$router.push({
					path: '/foto/' + photo.id,
					query: {
						busca: this.filters.search,
						cidade: this.filters.cities,
						data: this.filters.dates,
						fotografo: this.filters.photographer,
						genero: this.filters.gender,
						esconderVazias: true,
						tag: this.filters.tags,
						tipo: this.filters.types,
						page: page,
						per_page: this.params.per_page,
						sortBy: this.params.sortBy,
						sortOrder: this.params.sortOrder,
						total: 0
					}
				});
			},

			resetComponent(loadPhotos) {
				this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
				this.photos = [];
				this.params = {
					busca: this.filters.search,
					cidade: this.filters.cities,
					data: this.filters.dates,
					fotografo: this.filters.photographer,
					genero: this.filters.gender,
					mostrarCartazes: true,
					esconderVazias: true,
					tag: this.filters.tags,
					tipo: this.filters.types,
					page: 1,
					per_page: 21,
					sortBy: this.sortBy,
					sortOrder: 'asc'
				};

				if(loadPhotos) {
					this.loadPhotos();
				}
			}
		},

		watch: {
			'filters': {
				handler: function (val, oldVal) {
					this.resetComponent(true);
				},
				deep: true
			}
		}
    }
</script>

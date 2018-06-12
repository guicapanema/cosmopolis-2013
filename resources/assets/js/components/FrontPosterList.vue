<template>
    <div class="has-margin-100">
        <div class="columns is-multiline">
			<div v-for="poster of posters" class="column is-one-third">
				<div class="poster-card content is-marginless" @mouseover="poster.active = true" @mouseleave="poster.active = false">
					<div v-if="!poster.active" class="poster-card-content">
						<p class="is-size-5 is-marginless">{{ poster.text }}</p>
						<div v-if="poster.photos[0]" class="poster-info has-text-grey is-size-6">
							<div>{{ poster.photos[0].photographer }}</div>
							<div>{{ poster.photos[0].city }}</div>
							<div>{{ poster.type }}</div>
							<div>
								<span v-for="(tag, index) of poster.tags">
									{{ tag.text }}<span v-if="index < poster.tags.length - 1">,</span>
								</span>
							</div>
						</div>
					</div>
					<slider v-if="poster.active && poster.photos.length" animation="fade" :speed="100" :control-btn="false" height="100%" width="100%">
						<slider-item v-for="(photo, index) of poster.photos" :key="index">
							<img v-if="poster.active" :src="'/fotos/' + photo.id + '/arquivo?tamanho=pequeno&recortar=true'" @click="$router.push('/foto/' + photo.id)" class="is-cursor-pointer"></img>
						</slider-item>
					</slider>
				</div>
			</div>

			<div v-if="(posters.length === 0) && !loadingPosters" class="column">
				<div class="content has-text-centered has-margin-top-200">
					Não há cartazes que atendam aos filtros selecionados
				</div>
			</div>
		</div>
		<infinite-loading v-if="this.posters.length < params.total" @infinite="loadPosters"></infinite-loading>
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
				loadingPosters: false,
				posters: [],
				params: {
					busca: null,
					esconderVazios: true,
					mostrarFotos: true,
					mostrarTags: true,
					page: 1,
					per_page: 21,
					sortBy: 'text',
					sortOrder: 'asc'
				}
            }
        },

        mounted() {
			this.resetComponent();
        },

		methods: {
			loadPosters($state) {

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

						for (let poster of response.data.data) {
							poster.active = false;
							this.posters.push(poster);
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
					data: this.filters.dates,
					esconderVazios: true,
					fotografo: this.filters.photographer,
					genero: this.filters.gender,
					mostrarFotos: true,
					mostrarTags: true,
					tag: this.filters.tags,
					tipo: this.filters.types,
					page: 1,
					per_page: 21,
					sortBy: 'text',
					sortOrder: 'asc'
				};
				this.loadPosters();
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

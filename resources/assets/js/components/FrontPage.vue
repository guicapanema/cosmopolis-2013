<template>
	<div class="front-container">
		<div class="columns is-gapless">
			<div class="column is-one-quarter front-sidebar">
				<front-sidebar
					:view="view"
					:filters="filters"
					:total="total">
				</front-sidebar>
			</div>
			<div class="column front-body">
				<front-poster-list v-if="view === 'posters'" :filters="filters" @total="total = $event"></front-poster-list>
				<front-photo-list v-else :filters="filters" @total="total = $event"></front-photo-list>
			</div>
		</div>
		<front-photo-single
			v-if="photo_id"
			class="single-photo-overlay">
		</front-photo-single>

		<div v-if="showModal" class="modal is-active">
			<div class="modal-background"></div>
			<div class="modal-content">
				<div class="box">
					<div class="content">
						<div class="apoie-header">
							<h5>Apoie esse projeto</h5>
							<a class="delete" @click="showModal = false"></a>
						</div>
						<p>Você pode ajudar na construção dessa memória coletiva. Seu apoio vai contribuir para melhorias no sistema e catalogação de milhares de cartazes.</p>
						<a href="https://www.catarse.me/grafiasdejunho" class="button is-rounded is-success" target="_blank">APOIAR</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {

		props: ['visited'],

		data() {
            return {
				filters: {
					search: null,
					cities: [],
					dates:[],
					tags: [],
					types: []
				},
				photo_id: null,
				showModal: false,
				total: 0,
				view: 'photos'
            }
        },

        mounted() {
			let hasVisited = this.$cookies.isKey('visited');

			if(!hasVisited) {
				this.$cookies.set('visited', 'true', '1m');
				this.showModal = true;
			}

			this.parsePath();
        },

		methods: {
			parsePath() {
				if (this.$route.params.foto) {
					this.photo_id = this.$route.params.foto;
				} else {
					this.photo_id = null;
				}

				if(this.$route.path === '/cartazes') {
					this.view = 'posters';
				} else {
					this.view = 'photos';
				}

				this.filters = {
					search: null,
					cities: [],
					dates:[],
					tags: [],
					types: []
				};

				if(this.$route.query['cidade']) {
					if(typeof this.$route.query['cidade'] === 'string') {
						Vue.set(this.filters, 'cities', [this.$route.query['cidade']]);
					} else {
						Vue.set(this.filters, 'cities', this.$route.query['cidade']);
					}
				}

				if(this.$route.query['data']) {
					if(typeof this.$route.query['data'] === 'string') {
						Vue.set(this.filters, 'dates', [this.$route.query['data']]);
					} else {
						Vue.set(this.filters, 'dates', this.$route.query['data']);
					}
				}

				if(this.$route.query['tag']) {
					if(typeof this.$route.query['tag'] === 'string') {
						Vue.set(this.filters, 'tags', [this.$route.query['tag']]);
					} else {
						Vue.set(this.filters, 'tags', this.$route.query['tag']);
					}
				}

				if(this.$route.query['tipo']) {
					if(typeof this.$route.query['tipo'] === 'string') {
						Vue.set(this.filters, 'types', [this.$route.query['tipo']]);
					} else {
						Vue.set(this.filters, 'types', this.$route.query['tipo']);
					}
				}

				if(this.$route.query['genero']) {
					Vue.set(this.filters, 'gender', this.$route.query['genero']);
				}

				if(this.$route.query['fotografo']) {
					Vue.set(this.filters, 'photographer', this.$route.query['fotografo']);
				}

				if(this.$route.query['busca']) {
					Vue.set(this.filters, 'search', this.$route.query['busca']);
				}
			}
		},

		watch: {
			'$route' (to, from) {
				this.parsePath();
			}
		}
    }
</script>

<style scoped>
	.single-photo-overlay {
		position:fixed;
		top:0;
		left:0;
		z-index:100;
		width:100%;
		height:100%;
		background: white;
		overflow: auto;
	}
</style>

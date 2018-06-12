<template>
	<div class="front-container">
		<div class="columns is-gapless">
			<div class="column is-one-quarter front-sidebar">
				<front-sidebar
					:view="view"
					:filters="filters">
				</front-sidebar>
			</div>
			<div class="column front-body">
				<front-poster-list v-if="view === 'posters'" :filters="filters"></front-poster-list>
				<front-photo-list v-else :filters="filters"></front-photo-list>
			</div>
		</div>
		<front-photo-single
			v-if="photo_id"
			class="single-photo-overlay">
		</front-photo-single>
	</div>
</template>

<script>
	export default {

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
				view: 'photos'
            }
        },

        mounted() {
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

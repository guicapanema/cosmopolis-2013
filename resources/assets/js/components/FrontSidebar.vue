<template>
    <div>
		<figure class="image logo">
			<router-link to="/">
				<img src="/images/logo.png" width="100%"></img>
			</router-link>
		</figure>
		<div class="content has-text-centered is-size-7">
			<a href="/principal" class="has-text-grey-dark">PRINCIPAL</a>&nbsp | &nbsp
			<a href="/sobre" class="has-text-grey-dark">SOBRE</a>&nbsp | &nbsp
			<a href="/creditos" class="has-text-grey-dark">CRÉDITOS</a>
		</div>
		<div class="field has-margin-100 has-addons">
			<p class="control has-icons-left is-expanded">
				<input
					v-model="search"
					@keyup.enter="onSetSearch(search)"
					class="input" type="text" placeholder="Pesquisar">
				<span class="icon is-small is-left">
					<i class="fas fa-search"></i>
				</span>
			</p>
			<p class="control">
				<router-link to="/" :class="{
					'button': true,
					'is-light': view === 'photos'}">
					<span class="icon">
						<i class="fas fa-camera-retro"></i>
					</span>
				</router-link>
			</p>
			<p class="control">
				<router-link to="/cartazes" :class="{
					'button': true,
					'is-light': view === 'posters'}">
					<span class="icon">
						<i class="fas fa-font"></i>
					</span>
				</router-link>
			</p>
			<!-- <p v-if="search.length" class="control">
				<a @click="onSetSearch('')" class="button is-light">
					Limpar
				</a>
			</p> -->
		</div>
		<aside class="menu has-margin-100 is-hidden-mobile">
			<p class="menu-label">
				Cidade
			</p>
			<ul class="menu-list">
				<li v-for="city in cities" v-if="city['city']">
					<input type="checkbox"
						:checked="hasCity(city['city'])"
						@click="onSetCity(city['city'])">
					{{ city['city'] }}
				</li>
			</ul>

			<p class="menu-label">
				Tema
			</p>
			<ul class="menu-list">
				<li v-for="theme in themes">
					<input type="checkbox"
						:checked="hasTag(theme.tags)"
						@click="onSetTheme(theme.tags)">
					<span class="is-capitalized">{{ theme.name }}</span>
				</li>
			</ul>

			<p class="menu-label">
				Tipo
			</p>
			<ul class="menu-list">
				<li v-for="type in types" v-if="type['type']">
					<input type="checkbox"
						:checked="hasType(type['type'])"
						@click="onSetType(type['type'])">
					<span class="is-capitalized">{{ type['type'] }}</span>
				</li>
			</ul>
		</aside>
		<div class="has-text-centered">
			<a href="https://www.facebook.com/grafiasdejunho/" target="_blank" class="has-text-grey">
				<span class="icon">
					<i class="fab fa-facebook"></i>
				</span>
			</a>
		</div>
    </div>
</template>

<script>
	export default {

		props: ['filters', 'view'],

		data() {
            return {
				cities: [],
				search: '',
				themes: [{
					name: 'democracia',
					tags: ['democracia', 'antipartido', 'antifacismo', 'liberdade cívica', 'reforma política', 'antipolítica', 'partidarismo', 'antisistema', 'eleição', 'ditadura', 'impeachment']
				}, {
					name: 'cidadania',
					tags: ['cidadania', 'redes sociais', 'engajamento', 'convocatória']
				}, {
					name: 'violência',
					tags: ['violência', 'repressão policial', 'segurança pública', 'vinagre']
				}, {
					name: 'mobilidade',
					tags: ['mobilidade', 'aumento da tarifa', 'tarifa zero', 'metrô']
				}, {
					name: 'saúde',
					tags: ['saúde', 'hospitais', 'ato médico', 'sus']
				}, {
					name: 'educação',
					tags: ['educacao']
				}, {
					name: 'copa',
					tags: ['copa', 'fifa', 'estádio', 'território']
				}, {
					name: 'corrupção',
					tags: ['corrupção', 'pec 37', 'políticos corruptos', 'punitivismo']
				}, {
					name: 'nação',
					tags: ['nação', 'patriotismo', 'antipatriotismo', 'hino nacional']
				}, {
					name: 'mídia',
					tags: ['mídia', 'globo', 'SBT', 'veja']
				}, {
					name: 'direitos humanos',
					tags: ['direitos humanos', 'feminismo', 'lgbtiq', 'cura gay', 'estado laico', 'aborto', 'desmilitarização', 'indígena', 'racismo', 'trabalho escravo']
				}, {
					name: 'outras pautas',
					tags: ['cultura', 'pop de rua', 'moradia', 'juventude', 'meio ambiente', 'inflação', 'reforma tributária', 'previdência', 'salário mínimo', 'crise econômica', 'vandalismo', 'terceirização', 'turquia', 'legalização']
				}],

				feelings: [{
					name: 'otimismo',
					tags: ['otimismo']
				}, {
					name: 'indignação',
					tags: ['indignação']
				}, {
					name: 'ódio',
					tags: ['ódio']
				}],

				references: [{
					name: 'música',
					tags: ['música', 'legião urbana', 'cazuza', 'chico buarque', 'bob dylan', 'o rappa', 'racionais', 'engenheiros do hawaii']
				}, {
					name: 'outras línguas',
					tags: ['inglês', 'francês', 'espanhol']
				}, {
					name: 'personalidades',
					tags: ['dilma', 'alckmin', 'jabor', 'feliciano', 'renan', 'márcio lacerda', 'anastasia', 'aécio', 'malafaia', 'malcom x', 'haddad', 'neymar']
				}],

				types: []
            }
        },

        mounted() {
            axios.get('/fotos', { params: { groupBy: 'city' } })
				.then(response => {
					this.cities = response.data.data;
				}).catch(error => {
					console.error(error);
				});

			// axios.get('/tags')
			// 	.then(response => {
			// 		this.tags = response.data;
			// 	}).catch(error => {
			// 		console.error(error);
			// 	});

			axios.get('/cartazes', { params: { groupBy: 'type' } })
				.then(response => {
					this.types = response.data.data;
				}).catch(error => {
					console.error(error);
				});
        },

		methods: {
			hasCity(city) {
				return this.filters.cities.indexOf(city) >= 0;
			},
			hasTag(tag) {
				if(typeof tag === 'string') {
					return this.filters.tags.indexOf(tag) >= 0;
				} else {
					return tag.every((innerTag) => this.filters.tags.includes(innerTag));
				}
			},
			hasType(type) {
				return this.filters.types.indexOf(type) >= 0;

			},
			onSetCity(city) {
				let queryCities = this.$route.query['cidade'];
				if(!queryCities) {
					queryCities = city;
				} else if(typeof queryCities === 'string') {
					if (queryCities === city) {
						queryCities = null;
					} else {
						queryCities = [queryCities, city];
					}
				} else {
					queryCities = queryCities.slice();
					let index = queryCities.indexOf(city);
					if(index >= 0) {
						queryCities.splice(index, 1);
					} else {
						queryCities.push(city);
					}
				}

				this.$router.push({ path: this.$route.path, query: {...this.$route.query, cidade: queryCities} });

			},

			onSetSearch(search) {
				let querySearch = this.$route.query['busca'];
				querySearch = search;
				this.search = search;
				this.$router.push({ path: this.$route.path, query: {...this.$route.query, busca: querySearch} });
			},

			onSetTag(tag) {
				let queryTags = this.$route.query['tag'];
				if(!queryTags) {
					queryTags = tag;
				} else if(typeof queryTags === 'string') {
					if (queryTags === tag) {
						queryTags = null;
					} else {
						queryTags = [queryTags, tag];
					}
				} else {
					queryTags = queryTags.slice();
					let index = queryTags.indexOf(tag);
					if(index >= 0) {
						queryTags.splice(index, 1);
					} else {
						queryTags.push(tag);
					}
				}

				this.$router.push({ path: this.$route.path, query: {...this.$route.query, tag: queryTags} });
			},

			onSetTheme(tags) {
				let queryTags = this.$route.query['tag'];
				if(!queryTags) {
					queryTags = tags;
				} else if(typeof queryTags === 'string') {
					if (tags.includes(queryTags)) {
						queryTags = null;
					} else {
						tags.push(queryTags);
						queryTags = tags.slice();
					}
				} else {
					queryTags = queryTags.slice();
					if (tags.some(innerTag => queryTags.includes(innerTag))) { // Remove all tags in theme from query
						for (let tag of tags) {
							let index = queryTags.findIndex(innerTag => innerTag === tag);
							if (index >= 0) {
								queryTags.splice(index, 1);
							}
						}
					} else {
						queryTags.push(...tags);
					}
				}

				this.$router.push({ path: this.$route.path, query: {...this.$route.query, tag: queryTags} });
			},

			onSetType(type) {
				let queryTypes = this.$route.query['tipo'];
				if(!queryTypes) {
					queryTypes = type;
				} else if(typeof queryTypes === 'string') {
					if (queryTypes === type) {
						queryTypes = null;
					} else {
						queryTypes = [queryTypes, type];
					}
				} else {
					queryTypes = queryTypes.slice();
					let index = queryTypes.indexOf(type);
					if(index >= 0) {
						queryTypes.splice(index, 1);
					} else {
						queryTypes.push(type);
					}
				}

				this.$router.push({ path: this.$route.path, query: {...this.$route.query, tipo: queryTypes} });

			},
		}
    }
</script>

<style scoped>
	p.menu-label {
		margin-bottom: 0.2em;
	}
	li {
		margin-bottom: 0.2em;
	}
</style>

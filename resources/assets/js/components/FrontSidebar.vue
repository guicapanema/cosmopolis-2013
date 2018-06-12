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
			<a href="/colabore" class="has-text-grey-dark">COLABORE</a>
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
				<router-link
					:to="{ path: '/', query: $route.query }"
					:class="{
					'button': true,
					'is-light': view === 'photos'}">
					<span class="icon">
						<i class="fas fa-camera-retro"></i>
					</span>
				</router-link>
			</p>
			<p class="control">
				<router-link
					:to="{ path: '/cartazes', query: $route.query }"
					:class="{
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
			<template v-if="hasFilters">
				<p class="menu-label">
					Filtros ativos
				</p>
				<div class="tags">
					<template
						v-for="(filter, key) in filters"
						v-if="filter && (filter.length > 0)">
						<span
							v-if="key === 'search'"
							class="tag">
							{{ filter }}
							<a class="delete is-small" @click="onRemoveFilter(filter, key)"></a>
						</span>
						<span
							v-else
							v-for="element in filter"
							class="tag">
							<span v-if="key === 'dates'">
								{{ new Date(element).toLocaleDateString() }}
							</span>
							<span v-else>
								{{ element }}
							</span>
							<a class="delete is-small" @click="onRemoveFilter(element, key)"></a>
						</span>
					</template>
				</div>
			</template>

			<p class="menu-label is-cursor-pointer"
				@click="sideMenus['cities'] = !sideMenus['cities']">
				<span class="icon is-small">
					<i :class="{
						'fas': true,
						'fa-caret-down': sideMenus['cities'],
						'fa-caret-right': !sideMenus['cities']}">
					</i>
				</span>
				Cidade
			</p>
			<ul v-if="sideMenus['cities']" class="menu-list">
				<li v-for="city in cities"
					v-if="city['city']"
					@click="onSetCity(city['city'])"
					:class="{
						'is-cursor-pointer': true,
						'active': hasCity(city['city'])
						}">
					<span class="is-capitalized">{{ city['city'] }}</span>
				</li>
			</ul>

			<p class="menu-label is-cursor-pointer"
				@click="sideMenus['themes'] = !sideMenus['themes']">
				<span class="icon is-small">
					<i :class="{
						'fas': true,
						'fa-caret-down': sideMenus['themes'],
						'fa-caret-right': !sideMenus['themes']}">
					</i>
				</span>
				Tema
			</p>
			<ul v-if="sideMenus['themes']" class="menu-list">
				<li v-for="theme in themes"
					@click="onSetTheme(theme.tags)"
					:class="{
						'is-cursor-pointer': true,
						'active': hasTag(theme.tags)
						}">
					<span class="is-capitalized">{{ theme.name }}</span>
				</li>
			</ul>

			<p class="menu-label is-cursor-pointer"
				@click="sideMenus['types'] = !sideMenus['types']">
				<span class="icon is-small">
					<i :class="{
						'fas': true,
						'fa-caret-down': sideMenus['types'],
						'fa-caret-right': !sideMenus['types']}">
					</i>
				</span>
				Tipo
			</p>
			<ul v-if="sideMenus['types']" class="menu-list">
				<li v-for="type in types"
					v-if="type['type']"
					@click="onSetType(type['type'])"
					:class="{
						'is-cursor-pointer': true,
						'active': hasType(type['type'])
						}">
					<span class="is-capitalized">{{ type['type'] }}</span>
				</li>
			</ul>

			<p class="menu-label is-cursor-pointer"
				@click="sideMenus['dates'] = !sideMenus['dates']">
				<span class="icon is-small">
					<i :class="{
						'fas': true,
						'fa-caret-down': sideMenus['dates'],
						'fa-caret-right': !sideMenus['dates']}">
					</i>
				</span>
				Data
			</p>
			<datepicker v-if="sideMenus['dates']"
				:inline="true"
				:language="ptBR"
				:openDate="openDate"
				:disabledDates="disabledDates"
				:highlighted="highlightedDates"
				maximum-view="day"
				calendar-class="my-datepicker"
				@selected="onSetDate">
			</datepicker>
			<!-- <ul v-if="sideMenus['dates']" class="menu-list">
				<li v-for="date in dates"
					v-if="date['date']"
					@click="onSetDate(date['date'])"
					:class="{
						'is-cursor-pointer': true,
						'active': hasDate(date['date'])
						}">
					{{ new Date(date['date']).toLocaleDateString() }}
				</li>
			</ul> -->
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

	import Datepicker from 'vuejs-datepicker';
	import {ptBR} from 'vuejs-datepicker/dist/locale';

	export default {

		components: {
			Datepicker
		},

		props: ['filters', 'view'],

		data() {
            return {
				cities: [],

				ptBR: ptBR,
				openDate: new Date(2013, 5, 1),
				disabledDates: {
					to: new Date(2013, 5, 1),
					from: new Date(2013, 6, 31)
				},
				highlightedDates: {
					dates: [
					]
				},


				search: '',
				themes: [
				{
					name: 'cidadania',
					tags: ['cidadania', 'redes sociais', 'engajamento', 'convocatória']
				},
				{
					name: 'copa',
					tags: ['copa', 'fifa', 'estádio', 'território']
				},
				{
					name: 'corrupção',
					tags: ['corrupção', 'pec 37', 'políticos corruptos', 'punitivismo']
				},
				{
					name: 'democracia',
					tags: ['democracia', 'antipartido', 'antifacismo', 'liberdade cívica', 'reforma política', 'antipolítica', 'partidarismo', 'antisistema', 'eleição', 'ditadura', 'impeachment']
				},
				{
					name: 'direitos humanos',
					tags: ['direitos humanos', 'feminismo', 'lgbtiq', 'cura gay', 'estado laico', 'aborto', 'desmilitarização', 'indígena', 'racismo', 'trabalho escravo']
				},
				{
					name: 'educação',
					tags: ['educacao']
				},
				{
					name: 'mídia',
					tags: ['mídia', 'globo', 'SBT', 'veja']
				},
				{
					name: 'mobilidade',
					tags: ['mobilidade', 'aumento da tarifa', 'tarifa zero', 'metrô']
				},
				{
					name: 'nação',
					tags: ['nação', 'patriotismo', 'antipatriotismo', 'hino nacional']
				},
				{
					name: 'saúde',
					tags: ['saúde', 'hospitais', 'ato médico', 'sus']
				},
				{
					name: 'violência',
					tags: ['violência', 'repressão policial', 'segurança pública', 'vinagre']
				},
				{
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

				sideMenus: {
					'cities': true,
					'themes': true,
					'types': true,
					'dates': true
				},

				types: []
            }
        },

		computed: {
			hasFilters() {
				if(this.filters) {
					for (let filter in this.filters) {
						if (this.filters[filter] && (this.filters[filter].length > 0)) return true;
					}
				}
				return false;
			}
		},

        mounted() {
			this.search = this.filters.search;

            axios.get('/fotos', { params: { groupBy: 'city', sortBy: 'city' } })
				.then(response => {
					this.cities = response.data.data;
				}).catch(error => {
					console.error(error);
				});

			axios.get('/fotos', { params: { groupBy: 'date', sortBy: 'date', per_page: 60 } })
				.then(response => {
					for(let date of response.data.data) {
						this.highlightedDates.dates.push(new Date(Date.parse(date['date'])));
					}
				}).catch(error => {
					console.error(error);
				});

			axios.get('/cartazes', { params: { groupBy: 'type', sortBy: 'type' } })
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
			hasDate(date) {
				return this.filters.dates.indexOf(date) >= 0;
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

			onRemoveFilter(filter, key) {
				if(key === 'search') {
					this.onSetSearch(null);
				} else if(key === 'cities') {
					this.onSetCity(filter);
				} else if(key === 'dates') {
					this.onSetDate(filter);
				} else if(key === 'tags') {
					this.onSetTag(filter);
				} else if(key === 'types') {
					this.onSetType(filter);
				}
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

			onSetDate(date) {
				let isoDate;
				if (typeof date !== 'string') {
					isoDate = date.toISOString();
				} else {
					isoDate = date;
				}

				let queryDates = this.$route.query['data'];
				if(!queryDates) {
					queryDates = isoDate;
				} else if(typeof queryDates === 'string') {
					if (queryDates === isoDate) {
						queryDates = null;
					} else {
						queryDates = [queryDates, isoDate];
					}
				} else {
					queryDates = queryDates.slice();
					let index = queryDates.indexOf(isoDate);
					if(index >= 0) {
						queryDates.splice(index, 1);
					} else {
						queryDates.push(isoDate);
					}
				}

				this.$router.push({ path: this.$route.path, query: {...this.$route.query, data: queryDates} });

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

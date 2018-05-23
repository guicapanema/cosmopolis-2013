<template>
    <div>
		<figure class="image">
			<img src="/images/logo.png" width="100%"></img>
		</figure>
		<div class="content has-text-centered">
			PRINCIPAL | SOBRE | CRÉDITOS
		</div>
		<div class="buttons has-addons is-centered">
			<span class="button">Imagem</span>
			<span class="button">Texto</span>
		</div>
		<div class="field has-margin-100">
			<p class="control has-icons-left">
				<input class="input" type="text" placeholder="Pesquisar">
				<span class="icon is-small is-left">
					<i class="fas fa-search"></i>
				</span>
			</p>
		</div>
		<aside class="menu has-margin-100">
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
				<li v-for="tag in tags">
					<input type="checkbox"
						:checked="hasTag(tag.text)"
						@click="onSetTag(tag.text)">
					{{ tag.text }}
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
					{{ type['type'] }}
				</li>
			</ul>
		</aside>
    </div>
</template>

<script>
	export default {

		props: ['filters'],

		data() {
            return {
				cities: [],
				tags: [],
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

			axios.get('/tags')
				.then(response => {
					this.tags = response.data;
				}).catch(error => {
					console.error(error);
				});

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
				return this.filters.tags.indexOf(tag) >= 0;

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

				this.$router.push({ path: '/', query: {...this.$route.query, cidade: queryCities} });

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

				this.$router.push({ path: '/', query: {...this.$route.query, tag: queryTags} });

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

				this.$router.push({ path: '/', query: {...this.$route.query, tipo: queryTypes} });

			},
		}
    }
</script>

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
				<li v-for="city in cities" v-if="city['city']"><a>{{ city['city'] }}</a></li>
			</ul>

			<p class="menu-label">
				Tema
			</p>
			<ul class="menu-list">
				<li v-for="tag in tags"><a>{{ tag.text }}</a></li>
			</ul>

			<p class="menu-label">
				Tipo
			</p>
			<ul class="menu-list">
				<li v-for="type in types" v-if="type['type']"><a>{{ type['type'] }}</a></li>
			</ul>
		</aside>
    </div>
</template>

<script>
	export default {

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
			// loadPhotos($state) {
			// 	this.loadingPhotos = true;
			// 	axios.get('/fotos', { params: this.params})
			// 		.then(response => {
			// 			this.params.page = response.data.current_page;
			// 			this.params.total = response.data.total;
			// 			this.params.per_page = response.data.per_page;
			//
			// 			for (let photo of response.data.data) {
			// 				// photo.date = photo.date ? moment(photo.date).format('DD[/]MM[/]YYYY') : '';
			// 				this.cities.push(photo);
			// 			}
			// 			if ($state) $state.loaded();
			// 			this.params.page++;
			// 		}).catch(error => {
			// 			if ($state) $state.loaded();
			// 			console.error(error);
			// 		});
			// }
		}
    }
</script>

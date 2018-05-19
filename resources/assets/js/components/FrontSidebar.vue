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
			<!-- <p class="menu-label">
				Geral
			</p>
			<ul class="menu-list">
				<li><a href="/admin" class="{{ Request::is('admin') ? "is-active" : "" }}">Painel</a></li>
			</ul>
			<p class="menu-label">
				Imagens
			</p>
			<ul class="menu-list">
				<li><a href="{{ route('photo_create') }}" class="{{ Request::is('fotos/criar') ? "is-active" : "" }}">Enviar imagens</a></li>
				<li><a href="{{ route('photo_index') }}" class="{{ Request::is('fotos/indice') ? "is-active" : "" }}">Listar imagens</a></li>
			</ul>
			<p class="menu-label">
				Cartazes
			</p>
			<ul class="menu-list">
				<li><a href="{{ route('poster_index') }}" class="{{ Request::is('cartazes/indice') ? "is-active" : "" }}">Listar cartazes</a></li>
			</ul>
			<p class="menu-label">
				Tags
			</p>
			<ul class="menu-list">
				<li><a href="{{ route('tag_index') }}" class="{{ Request::is('tags/indice') ? "is-active" : "" }}">Listar tags</a></li>
			</ul> -->
		</aside>
    </div>
</template>

<script>
	export default {

		data() {
            return {
				photo: {}
            }
        },

        mounted() {
            // this.loadPhotos();
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
			// 				this.photos.push(photo);
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

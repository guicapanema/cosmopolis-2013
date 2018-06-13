<template>
    <div id="photo-single">
		<button class="modal-close is-large" aria-label="close" @click="onPhotoClose()"></button>

		<figure class="image single-photo">
	        <img v-if="photo.id" :src="'/fotos/' + photo.id + '/arquivo?tamanho=grande'" width="100%" @load="loadingPhoto = false"></img>
			<a href="#" class="arrow-next" @click="onGoNext()">
				<img src="/images/next.png"></img>
			</a>
			<a href="#" class="arrow-prev" @click="onGoPrevious()">
				<img src="/images/next.png"></img>
			</a>
		</figure>
		<div class="section single-photo-data has-text-grey-lighter">
			<div class="container">
				<div class="columns">
					<div class="column is-two-thirds">
						<h2 class="title is-marginless has-text-white is-uppercase">Cartazes</h2>
						<hr class="title-underline posters-underline"></hr>
						<div v-for="poster of posters" class="content has-margin-top-200">
							<h3 class="title is-marginless has-text-white is-uppercase has-text-weight-normal">{{ poster.text }}</h3>
							<div>
								<span v-if="poster.pivot.gender">
									<span class="has-text-black has-text-weight-light">Gênero</span>
									<router-link :to="'/?genero=' + poster.pivot.gender" class="has-text-grey-lighter has-text-weight-light is-capitalized">
										{{ poster.pivot.gender === 'male' ? 'masculino' : 'feminino' }}
									</router-link>
								</span>
								<span v-if="poster.type">
									<span class="has-text-black has-text-weight-light">Tipo</span>
									<router-link :to="'/?tipo=' + poster.type" class="has-text-grey-lighter has-text-weight-light is-capitalized">
										{{ poster.type }}
									</router-link>
								</span>
							</div>
							<div v-if="poster.tags.length">
								<span class="has-text-black has-text-weight-light">Tags</span>
								<span v-for="(tag, index) of poster.tags" class="has-text-grey-lighter has-text-weight-light">
									<router-link :to="'/?tag=' + tag.text" class="has-text-grey-lighter has-text-weight-light is-capitalized">
										{{ tag.text }}
									</router-link><span v-if="index < (poster.tags.length - 1)">, </span>
								</span>
							</div>
						</div>
					</div>
					<div class="column is-one-third photo-description">
						<div v-if="photo.date" class="content">
							<h2 class="title is-marginless has-text-white is-uppercase">Data</h2>
							<hr class="title-underline"></hr>
							<router-link :to="'/?data=' + photo.date" class="has-text-grey-lighter has-text-weight-light">
								{{ new Date(Date.parse(photo.date)).toLocaleDateString() }}
							</router-link>
						</div>
						<div v-if="photo.city" class="content">
							<h2 class="title is-marginless has-text-white is-uppercase">Cidade</h2>
							<hr class="title-underline"></hr>
							<div>
								<router-link :to="'/?cidade=' + photo.city" class="has-text-grey-lighter has-text-weight-light">
									{{ photo.city }}
								</router-link>
							</div>
						</div>
						<div v-if="photo.photographer" class="content">
							<h2 class="title is-marginless has-text-white is-uppercase">Fotografia</h2>
							<hr class="title-underline"></hr>
							<div>
								<router-link :to="'/?fotografo=' + photo.photographer" class="has-text-grey-lighter has-text-weight-light">
									{{ photo.photographer }}
								</router-link>
							</div>
						</div>
						<div v-if="photo.license" class="content">
							<h2 class="title is-marginless has-text-white is-uppercase">Licença de Uso</h2>
							<hr class="title-underline"></hr>
							<div class="has-text-weight-light">{{ photo.license }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="overlay" v-if="loadingPhoto">
			<span class="icon is-large has-text-white">
				<i class="fas fa-3x fa-spinner fa-spin"></i>
			</span>
		</div>
    </div>
</template>

<script>
	export default {

		data() {
            return {
				photo: {},
				photos: [],
				posters: [],
				loadingPhoto: false,
				loadingPhotos: false
            }
        },

        mounted() {
			if(this.$route.query) {
				this.params = this.$route.query;
			}
            this.loadPhoto();
			this.loadPosters();
			this.loadPhotos();
			window.addEventListener('keyup', this.onKeyUp);
        },

		methods: {
			loadPhoto() {
				this.loadingPhoto = true;
				axios.get('/fotos/' + this.$route.params.foto)
					.then(response => {
						this.photo = response.data
						// this.loadingPhoto = false;
					}).catch(error => {
						console.error(error);
						this.loadingPhoto = false;
					});
			},

			loadPhotos(prepend) {
				this.loadingPhotos = true;
				axios.get('/fotos', { params: this.params })
					.then(response => {
						this.params.page = response.data.current_page;
						this.params.total = response.data.total;
						this.params.per_page = response.data.per_page;
						this.params.last_page = response.data.last_page;
						if(prepend) {
							this.photos.unshift(...response.data.data);
						} else {
							this.photos.push(...response.data.data);
						}
						this.loadingPhotos = false;
					}).catch(error => {
						this.loadingPhotos = false;
						console.error(error);
					});
			},

			loadPosters() {
				axios.get('/fotos/' + this.$route.params.foto + '/cartazes?showTags=true')
					.then(response => {
						this.posters = response.data;
					}).catch(error => {
						console.error(error);
					});
			},

			onGoNext() {
				let index = this.photos.findIndex(photo => photo.id === this.photo.id);
				if (index >= 0 && index < (this.photos.length - 1)) {
					let nextPhoto = this.photos[index + 1];

					if ((index + 3) >= this.photos.length) {
						if (this.params.page < this.params.last_page) {
							this.params.page += 1;
							this.loadPhotos();
						}
					}
					let photoSingle = document.getElementById('photo-single');
					photoSingle.scrollTop = 0;
					this.$router.push({ path: '/foto/' + nextPhoto.id, query: this.params });
				}

			},

			onGoPrevious() {
				let index = this.photos.findIndex(photo => photo.id === this.photo.id);

				if (index > 0) {
					let previousPhoto = this.photos[index - 1];

					if ((index - 2) <= 0) {
						if (this.params.page > 1) {
							this.params.page -= 1;
							this.loadPhotos(true);
						}
					}
					let photoSingle = document.getElementById('photo-single');
					photoSingle.scrollTop = 0;
					this.$router.push({ path: '/foto/' + previousPhoto.id, query: this.params });
				}
			},

			onKeyUp(event) {
				if (event.keyCode === 37) {
					this.onGoPrevious();
				} else if (event.keyCode === 39) {
					this.onGoNext();
				}
			},

			onPhotoClose() {
				window.removeEventListener('keyup', this.onKeyUp);
				this.$router.push({ path: '/', query: this.params });
			}
		},

		watch: {
			'$route' (to, from) {
				this.loadPhoto();
				this.loadPosters();
			}
		}
    }
</script>

<style scoped>
	.modal-close {
		z-index: 21;
	}
</style>

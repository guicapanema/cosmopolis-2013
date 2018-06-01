<template>
    <div>
		<button class="modal-close is-large" aria-label="close" @click="onPhotoClose()"></button>
		<figure class="image">
	        <img v-if="photo.id" :src="'/fotos/' + photo.id + '/arquivo?tamanho=grande'" width="100%"></img>
		</figure>
		<div class="section single-photo-data has-text-grey-lighter">
			<div class="container">
				<div class="columns">
					<div class="column is-two-thirds">
						<button class="button is-outlined is-inverted">CARTAZES</button>
						<div v-for="poster of posters" class="content has-margin-top-200">
							<h2 class="title is-marginless has-text-white is-uppercase">{{ poster.text }}</h2>
							<div>
								<span v-if="poster.pivot.gender">
									<span class="has-text-grey">Gênero</span>
									<router-link :to="'/?genero=' + poster.pivot.gender" class="has-text-grey-lighter">
										{{ poster.pivot.gender === 'male' ? 'masculino' : 'feminino' }}
									</router-link>
								</span>
								<span v-if="poster.type">
									<span class="has-text-grey">Tipo</span>
									<router-link :to="'/?tipo=' + poster.type" class="has-text-grey-lighter">
										{{ poster.type }}
									</router-link>
								</span>
							</div>
							<div v-if="poster.tags.length">
								<span class="has-text-grey">Tags</span>
								<span v-for="(tag, index) of poster.tags" class="has-text-grey-lighter">
									<router-link :to="'/?tag=' + tag.text" class="has-text-grey-lighter">
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
							<div>{{ photo.date }}</div>
						</div>
						<div v-if="photo.city" class="content">
							<h2 class="title is-marginless has-text-white is-uppercase">Cidade</h2>
							<hr class="title-underline"></hr>
							<div>
								<router-link :to="'/?cidade=' + photo.city" class="has-text-grey-lighter">
									{{ photo.city }}
								</router-link>
							</div>
						</div>
						<div v-if="photo.photographer" class="content">
							<h2 class="title is-marginless has-text-white is-uppercase">Fotógrafo</h2>
							<hr class="title-underline"></hr>
							<div>
								<router-link :to="'/?fotografo=' + photo.photographer" class="has-text-grey-lighter">
									{{ photo.photographer }}
								</router-link>
							</div>
						</div>
						<div v-if="photo.license" class="content">
							<h2 class="title is-marginless has-text-white is-uppercase">Licença de Uso</h2>
							<hr class="title-underline"></hr>
							<div>{{ photo.license }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</template>

<script>
	export default {

		data() {
            return {
				photo: {},
				posters: [],
				loadingPhoto: false
            }
        },

        mounted() {
            this.loadPhoto();

			axios.get('/fotos/' + this.$route.params.foto + '/cartazes?showTags=true')
				.then(response => {
					this.posters = response.data;
				});
        },

		methods: {
			loadPhoto() {
				this.loadingPhoto = true;
				axios.get('/fotos/' + this.$route.params.foto)
					.then(response => {
						this.photo = response.data
						this.photo.date = this.photo.date ? new Date(this.photo.date).toLocaleDateString() : '';
						this.loadingPhoto = false;
					}).catch(error => {
						console.error(error);
						this.loadingPhoto = false;
					});
			},
			onPhotoClose() {
				this.$router.go(-1);
			}
		}
    }
</script>

<style scoped>
	.modal-close {
		z-index: 21;
	}
</style>

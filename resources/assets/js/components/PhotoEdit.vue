<template>
	<div>
		<div class="columns is-vcentered">
			<div class="column is-8">
				<figure class="image">
					<img
						v-if="this.photo && this.photo.path"
						:src="'/fotos/' + this.photo.id + '/arquivo?tamanho=grande'">
					</img>
				</figure>
			</div>
			<div class="column is-4">
				<div class="field">
					<label class="label">Nome</label>
					<div class="control">
						<input v-model="photo.name" type="text" class="input" name="name" required autofocus>
					</div>
				</div>

				<div class="field">
					<label class="label">Data</label>
					<div class="control">
						<input v-model="photo.date" type="text" class="input" name="date" placeholder="DD/MM/YYYY" required>
					</div>
				</div>

				<div class="field">
					<label class="label">Cidade</label>
					<div class="control">
						<input v-model="photo.city" type="text" class="input" name="city" required autofocus>
					</div>
				</div>

				<div class="field">
					<label class="label">Fotógrafo</label>
					<div class="control">
						<input v-model="photo.photographer" type="text" class="input" name="photographer" required autofocus>
					</div>
				</div>

				<div class="field">
					<label class="label">Licença de Uso</label>
					<div class="control">
						<input v-model="photo.license" type="text" class="input" name="license" required autofocus>
					</div>
				</div>

				<div class="field">
					<p class="control">
						<button v-if="!is_bulk_edit" @click="onPhotoDelete()" class="button is-outlined is-danger">
							Apagar
						</button>
						<button @click="onPhotoSave()" class="button is-success is-pulled-right">
							Salvar
						</button>
					</p>
				</div>
			</div>
		</div>

		<div>
			<div class="content">
				<h4>Cartazes</h4>
			</div>
			<photo-poster-card
				v-for="poster of posters"
				:photo_id="photo_id"
				:poster="poster"
				:key="poster.id"
				@remove="onPosterRemove(poster)">
			</photo-poster-card>
			<div>
				<button class="button is-primary is-outlined" @click="onPosterAdd()">
					+ cartaz
				</button>
			</div>
		</div>
	</div>
</template>

<script>
    export default {
		props: ['is_bulk_edit', 'photo_id'],

		data() {
			return {
				photo: {},
				posters: []
			};
		},

        mounted() {
            axios.get('/fotos/' + this.photo_id)
				.then(response => {
					this.photo = response.data;
					if(this.photo.date) this.photo.date = moment(this.photo.date).format('DD[/]MM[/]YYYY');
				});

			axios.get('/fotos/' + this.photo_id + '/cartazes?showTags=true')
				.then(response => {
					this.posters = response.data;
				});
        },

		methods: {
			deletePhoto() {
				axios.delete('/fotos/' + this.photo_id)
					.then(response => {
						window.location.replace('/fotos/indice');
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao apagar imagem', type: 'is-danger', position: 'is-bottom'});
						throw error;
					})
			},

			onPhotoDelete() {
                this.$dialog.confirm({
                    title: 'Apagar foto',
                    message: 'Você tem certeza que deseja <b>apagar</b> essa foto?',
                    confirmText: 'Apagar foto',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => this.deletePhoto()
	            });
			},

			onPhotoSave() {
				axios.put('/fotos/' + this.photo_id, this.photo)
					.then(response => {
						this.$toast.open({ message: 'Imagem salva com sucesso!', type: 'is-success', position: 'is-bottom'});
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao salvar imagem', type: 'is-danger', position: 'is-bottom'});
						throw error;
					})
			},

			onPosterAdd() {
				this.posters.push({text: '', gender: '', type: '', tags: []});
			},

			onPosterRemove(poster) {
				let index = this.posters.findIndex(innerPoster => innerPoster.id === poster.id);
				if (index >= 0) {
					this.posters.splice(index, 1);
				}
			}

		}
    }
</script>

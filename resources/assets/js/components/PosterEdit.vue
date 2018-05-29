<template>
	<div>
		<div class="columns">
			<div class="column is-8">
				<div class="field">
					<label class="label">Texto</label>
					<div class="control">
						<input v-model="poster.text" type="text" class="input" name="text" required autofocus>
					</div>
				</div>
			</div>
			<div class="column is-narrow content">
				<b-field label="Tipo">
					<b-select
						v-model="poster.type"
						placeholder="Selecione um tipo"
						required>
						<option value="bandeira">
							Bandeira
						</option>
						<option value="camiseta">
							Camiseta
						</option>
						<option value="cartaz">
							Cartaz
						</option>
						<option value="faixa">
							Faixa
						</option>
						<option value="impresso">
							Impresso
						</option>
						<option value="pichação">
							Pichação
						</option>
						<option value="outros">
							Outros
						</option>
					</b-select>
		        </b-field>
				<div class="field is-pulled-right">
					<p class="control">
						<button type="submit" class="button is-success" @click="posterUpdate()">
							Salvar
						</button>
					</p>
				</div>
			</div>
		</div>

		<div class="columns">
			<div class="column is-8 content">
				<b-field label="Tags">
					<b-taginput
						v-model="poster.tags"
						:data="filteredTags"
						autocomplete
						:allowNew="true"
						field="text"
						icon="tag"
						placeholder="Adicionar tags"
						size="is-small"
						@typing="getFilteredTags"
						@add="onTagAdd"
						@remove="posterUpdate">
					</b-taginput>
				</b-field>
			</div>

		</div>
		<div class="content">
			<h5>Aparece em:</h5>
		</div>
		<div class="columns is-multiline">
			<div v-for="photo of poster.photos" class="column is-one-quarter">
				<figure class="image">
					<a :href="'/fotos/' + photo.id + '/editar'">
						<img :src="'/fotos/' + photo.id + '/arquivo?tamanho=medio'"></img>
					</a>
				</figure>
			</div>
		</div>
	</div>
</template>

<script>
	const debounce = require('lodash/debounce');
	const clone = require('lodash/clone');

    export default {
		props: ['poster_id'],

		data() {
			return {
				poster: {},
				filteredTags: [],
				tags: []
			};
		},

        mounted() {
			this.getTags();
			axios.get('/cartazes/' + this.poster_id)
				.then(response => {
					this.poster = response.data;
				});
        },

		methods: {
			getAsyncData: debounce(function () {
                this.filteredPosters = []
                this.isFetchingPoster = true
                axios.get('/cartazes', { params : { busca: this.posterText, mostrarFotos: true } })
                    .then(response => {
                        response.data.forEach((item) => this.filteredPosters.push(item))
                        this.isFetchingPoster = false
                    })
                    .catch((error) => {
                        this.isFetchingPoster = false
                        throw error
                    })
            }, 500),

			getFilteredTags(text) {
                this.filteredTags = this.tags.filter((option) => {
                    return option.text
                        .toString()
                        .toLowerCase()
                        .indexOf(text.toLowerCase()) >= 0
                })
            },

			getTags() {
				axios.get('/tags')
					.then(response => {
						this.tags = response.data;
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao obter tags', type: 'is-danger', position: 'is-bottom'});
						throw error;
					})
			},

			onTagAdd(tag) {
				if(typeof tag === 'string') {
					this.onTagCreate(tag);
				} else {
					this.posterUpdate();
				}
			},

			onTagCreate(tag) {
				axios.post('/tags', {text: tag})
					.then(response => {
						let index = this.poster.tags.indexOf(tag);
						if(index >= 0) {
							this.poster.tags.splice(index, 1);
							this.poster.tags.push(response.data)
							this.posterUpdate();
						}
					});
			},

			posterUpdate() {
				axios.put('/cartazes/' + this.poster.id, this.poster)
					.then(response => {
						this.$toast.open({ message: 'Cartaz atualizado com sucesso!', type: 'is-success', position: 'is-bottom'});
					});
			}
		}
    }
</script>

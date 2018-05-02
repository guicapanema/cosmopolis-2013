<template>
	<div class="box">
		<div class="columns">
			<div class="column content is-two-thirds">
				<h5>Texto</h5>
				<b-field>
					<b-autocomplete
					v-model="posterText"
					:data="filteredPosters"
					placeholder="Digite o texto para começar a busca"
					field="text"
					:loading="isFetchingPoster"
					@input="getAsyncData"
					@select="onPosterSelect"
					:disabled="selectedPoster"
					required>
					</b-autocomplete>
				</b-field>
			</div>
			<div v-if="posterText.length && !selectedPoster">
				<button @click="onPosterCreate()" class="button is-success">
					Criar cartaz
				</button>
			</div>
			<div v-if="selectedPoster" class="column content">
				<h5>Gênero</h5>
	            <b-radio
					v-model="selectedPoster.pivot.gender"
					@input="onPivotUpdate"
	                native-value="male">
	                Masculino
	            </b-radio>
	            <b-radio v-model="selectedPoster.pivot.gender"
					@input="onPivotUpdate"
	                native-value="female">
	                Feminino
	            </b-radio>
			</div>
		</div>
		<div v-if="selectedPoster" class="columns">
			<div class="column is-two-thirds content">
				<h5>Tags</h5>
				<b-field>
					<b-taginput
						v-model="selectedPoster.tags"
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
			<div v-if="selectedPoster" class="column content">
				<h5>Tipo</h5>
				<b-field>
					<b-select
						v-model="selectedPoster.pivot.type"
						@input="onPivotUpdate"
						placeholder="Selecione um tipo"
						required>
						<option value="faixa">
							Faixa
						</option>
						<option value="cartaz">
							Cartaz
						</option>
						<option value="pichação">
							Pichação
						</option>
						<option value="camiseta">
							Camiseta
						</option>
						<option value="outros">
							Outros
						</option>
					</b-select>
		        </b-field>
			</div>
        </div>
		<div class="content has-text-right">
			<a @click="onPosterRemove()">Remover</a>
		</div>
	</div>
</template>

<script>
	const debounce = require('lodash/debounce');
	const clone = require('lodash/clone');


    export default {
		props: ['photo_id', 'poster'],

		data() {
            return {
				filteredPosters: [],
                filteredTags: [],
				isFetchingPoster: false,
				posterText: '',
				selectedPoster: null,
				tags: []
            }
        },

        mounted() {
			if(this.poster.text) {
				this.posterText = this.poster.text;
				this.selectedPoster = clone(this.poster);
			}
			this.getTags();
        },
		methods: {
            getAsyncData: debounce(function () {
                this.filteredPosters = []
                this.isFetchingPoster = true
                axios.get('/posteres/busca?query=' + this.posterText)
                    .then(response => {
                        response.data.forEach((item) => this.filteredPosters.push(item))
                        this.isFetchingPoster = false
                    })
                    .catch((error) => {
                        this.isFetchingPoster = false
                        throw error
                    })
            }, 500),

			getTags() {
				axios.get('/tags')
					.then(response => {
						this.tags = response.data;
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao obter tags', type: 'is-danger', position: 'is-bottom'});
						throw error;
					})
			},

			getFilteredTags(text) {
                this.filteredTags = this.tags.filter((option) => {
                    return option.text
                        .toString()
                        .toLowerCase()
                        .indexOf(text.toLowerCase()) >= 0
                })
            },

			onPosterCreate() {
				if (!this.selectedPoster && this.posterText.length) {
					axios.post('/posteres', { text: this.posterText })
						.then(response => {
							this.$toast.open({ message: 'Cartaz criado com sucesso!', position: 'is-bottom'});
							this.onPosterSelect(response.data);
						}).catch(error => {
							this.$toast.open({ message: 'Erro ao criar cartaz', type: 'is-danger', position: 'is-bottom'});
							throw error;
						});
				}
			},

			onPosterRemove() {
				axios.delete('/fotos/' + this.photo_id + '/posteres/' + this.selectedPoster.id)
					.then(response => {
						this.$toast.open({ message: 'Cartaz removido com sucesso', type: 'is-success', position: 'is-bottom'});
						this.$emit('remove');
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao remover cartaz', type: 'is-danger', position: 'is-bottom'});
						throw error;
					});
			},

			onPosterSelect(poster) {
				axios.post('/fotos/' + this.photo_id + '/posteres' , poster)
					.then(response => {
						this.selectedPoster = response.data;
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao adicionar cartaz', type: 'is-danger', position: 'is-bottom'});
						throw error;
					});
			},

			onPivotUpdate() {
				let pivotData = { gender: this.selectedPoster.pivot.gender, type: this.selectedPoster.pivot.type };
				axios.put('/fotos/' + this.photo_id + '/posteres/' + this.selectedPoster.id,
				pivotData)
					.then(response => {
						this.$toast.open({ message: 'Cartaz atualizado com sucesso!', type: 'is-success', position: 'is-bottom'});
					});
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
						let index = this.selectedPoster.tags.indexOf(tag);
						if(index >= 0) {
							this.selectedPoster.tags.splice(index, 1);
							this.selectedPoster.tags.push(response.data)
							this.posterUpdate();
						}
					});
			},

			posterUpdate() {
				axios.put('/posteres/' + this.selectedPoster.id, this.selectedPoster)
					.then(response => {
						this.$toast.open({ message: 'Cartaz atualizado com sucesso!', type: 'is-success', position: 'is-bottom'});
					});
			}
        }
    }
</script>

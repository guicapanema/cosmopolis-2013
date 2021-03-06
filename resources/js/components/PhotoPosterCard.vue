<template>
	<div class="box">
		<div class="columns">
			<div class="column content is-two-thirds">
				<b-field label="Texto" expanded>
					<b-field expanded>
						<b-autocomplete
							v-model="posterText"
							:data="filteredPosters"
							placeholder="Digite o texto para começar a busca"
							field="text"
							:loading="isFetchingPoster"
							@input="getAsyncData"
							@select="onPosterSelect"
							:disabled="selectedPoster" expanded>
							<template slot-scope="props">
								<div class="media">
									<div class="media-left" @click.stop="onPosterPhotoClick(props.option.photos)">
										<img width="64" :src="(props.option.photos && props.option.photos.length) ? '/fotos/' + props.option.photos[0].id + '/arquivo?tamanho=pequeno' : ''">
									</div>
									<div class="media-content">
										{{ props.option.text }}
										<br>
										<small>
											{{ props.option.type }} | aparece em {{ props.option.photos_count }} fotos
										</small>
									</div>
								</div>
							</template>
						</b-autocomplete>
						<p v-if="!selectedPoster" class="control">
							<button @click="onPosterCreate()" class="button is-success" :disabled="!posterText.length">
								Criar cartaz
							</button>
						</p>
					</b-field>
				</b-field>
			</div>
			<!-- <div v-if="posterText.length && !selectedPoster">
				<button @click="onPosterCreate()" class="button is-success">
					Criar cartaz
				</button>
			</div> -->
			<div v-if="selectedPoster" class="column content">
				<b-field label="Gênero">
					<div>
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
				</b-field>
			</div>
		</div>
		<div v-if="selectedPoster" class="columns">
			<div class="column is-two-thirds content">
				<b-field label="Tags">
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
				<b-field label="Tipo">
					<b-select
						v-model="selectedPoster.type"
						@input="posterUpdate"
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
						<option value="bandeira">
							Bandeira
						</option>
						<option value="outros">
							Outros
						</option>
					</b-select>
		        </b-field>
			</div>
        </div>
		<div v-if="selectedPoster" class="content">
			<a :href="'/cartazes/' + selectedPoster.id + '/editar'">Ver cartaz</a>
			<button class="button is-danger is-outlined is-small is-pulled-right" @click="onPosterRemove()">
				Remover
			</button>
		</div>
		<b-modal :active.sync="isPhotoModalActive">
				<img :src="'/fotos/' + previewPhoto.id + '/arquivo?tamanho=grande'">
        </b-modal>
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
				isPhotoModalActive: false,
				posterText: '',
				previewPhoto: {},
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
                axios.get('/cartazes', { params : { busca: this.posterText, mostrarFotos: true, limite: 10 } })
                    .then(response => {
                        response.data.data.forEach((item) => this.filteredPosters.push(item))
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
					axios.post('/cartazes', { text: this.posterText })
						.then(response => {
							this.$toast.open({ message: 'Cartaz criado com sucesso!', position: 'is-bottom'});
							this.onPosterSelect(response.data);
						}).catch(error => {
							this.$toast.open({ message: 'Erro ao criar cartaz', type: 'is-danger', position: 'is-bottom'});
							throw error;
						});
				}
			},

			onPosterPhotoClick(photos) {
				if(photos && photos.length) {
					this.previewPhoto = photos[0];
					this.isPhotoModalActive = true;
				}
			},

			onPosterRemove() {
				axios.delete('/fotos/' + this.photo_id + '/cartazes/' + this.selectedPoster.id)
					.then(response => {
						this.$toast.open({ message: 'Cartaz removido com sucesso', type: 'is-success', position: 'is-bottom'});
						this.$emit('remove');
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao remover cartaz', type: 'is-danger', position: 'is-bottom'});
						throw error;
					});
			},

			onPosterSelect(poster) {
				axios.post('/fotos/' + this.photo_id + '/cartazes' , poster)
					.then(response => {
						this.selectedPoster = response.data;
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao adicionar cartaz', type: 'is-danger', position: 'is-bottom'});
						throw error;
					});
			},

			onPivotUpdate() {
				let pivotData = { gender: this.selectedPoster.pivot.gender };
				axios.put('/fotos/' + this.photo_id + '/cartazes/' + this.selectedPoster.id,
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
				axios.put('/cartazes/' + this.selectedPoster.id, this.selectedPoster)
					.then(response => {
						this.$toast.open({ message: 'Cartaz atualizado com sucesso!', type: 'is-success', position: 'is-bottom'});
					});
			}
        }
    }
</script>

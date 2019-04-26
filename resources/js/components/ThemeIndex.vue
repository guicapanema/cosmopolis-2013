<template>
	<div>
		<div v-for="theme of themes" class="box">
			<div class="field">
				<label class="label">Texto</label>
				<div class="control">
					<input v-model="theme.name"
						type="text"
						class="input"
						name="text"
						required
						autofocus
						@blur="themeUpdate(theme)">
					</input>
				</div>
			</div>

			<b-field label="Tags">
				<b-taginput
					v-model="theme.tags"
					:data="filtered_tags"
					autocomplete
					:allowNew="false"
					field="text"
					icon="tag"
					placeholder="Adicionar tags"
					@typing="getFilteredTags"
					@add="themeUpdate(theme)"
					@remove="themeUpdate(theme)">
				</b-taginput>
			</b-field>

			<div class="is-clearfix">
				<button class="button is-danger is-outlined is-small is-pulled-right" @click="onThemeRemove(theme)">
					Remover
				</button>
			</div>
		</div>

		<div>
			<button class="button is-primary is-outlined" @click="onThemeAdd()">
				+ tema
			</button>
		</div>
	</div>
</template>

<script>
	const debounce = require('lodash/debounce');
	const clone = require('lodash/clone');

    export default {
		data() {
			return {
				themes: [],
				filtered_tags: [],
				tags: []
			};
		},

        mounted() {
			this.getTags();
			this.getThemes();
        },

		methods: {
			getFilteredTags(text)
			{
                this.filtered_tags = this.tags.filter((option) => {
                    return option.text
                        .toString()
                        .toLowerCase()
                        .indexOf(text.toLowerCase()) >= 0
                })
            },

			getTags()
			{
				axios.get('/tags')
					.then(response => {
						this.tags = response.data;
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao obter tags', type: 'is-danger', position: 'is-bottom'});
						throw error;
					});
			},

			getThemes()
			{
				axios.get('/temas')
					.then(response => {
						this.themes = response.data;
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao obter temas', type: 'is-danger', position: 'is-bottom'});
						throw error;
					});
			},

			themeUpdate(theme)
			{
				if (theme.id) {
					axios.put('/temas/' + theme.id, theme)
						.then(response => {
							this.$toast.open({ message: 'Tema atualizado com sucesso!', type: 'is-success', position: 'is-bottom'});
						});
				} else {
					axios.post('/temas', theme)
						.then(response => {
							Vue.set(theme, 'id', response.data.id);
							this.$toast.open({ message: 'Tema criado com sucesso!', type: 'is-success', position: 'is-bottom'});
						});
				}
			},

			onThemeAdd()
			{
				this.themes.push({
					name: '',
					tags: [],
				});
			},

			onThemeRemove(theme)
			{
				if (theme.id) {
					axios.delete('/temas/' + theme.id)
					.then(response => {
						let index = this.themes.findIndex(inner_theme => inner_theme.id === theme.id);
						if (index >= 0) {
							this.themes.splice(index, 1);
						}

						this.$toast.open({ message: 'Tema removido com sucesso', type: 'is-success', position: 'is-bottom'});
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao remover tema', type: 'is-danger', position: 'is-bottom'});
						throw error;
					});
				}
				else {
					let index = this.themes.findIndex(inner_theme => inner_theme.id === theme.id);
					if (index >= 0) {
						this.themes.splice(index, 1);
					}
				}
			}
		}
    }
</script>

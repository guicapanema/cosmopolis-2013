<template>
    <div>
		<div>
			<b-field grouped>
				<b-field>
		            <b-input v-model="searchText"
						placeholder="buscar..."
		                type="search"
		                icon="search"
						@keyup.native.enter="loadTags()">
		            </b-input>
		            <p class="control" @click="loadTags()">
		                <button class="button is-primary">Buscar</button>
		            </p>
		        </b-field>
				<b-field expanded>
				</b-field>
				<p class="control">
					<button class="button field is-danger" @click="onDeletePosters()"
					:disabled="!checkedRows.length">
					<b-icon icon="trash-alt"></b-icon>
						<span>Apagar</span>
					</button>
				</p>
			</b-field>
		</div>

        <b-table
			:loading="loadingTags"
            :data="tags"
            :checked-rows.sync="checkedRows"
			default-sort="name"
			:default-sort-direction="'asc'"
			:paginated="true"
            checkable>
			<template slot-scope="props">
                <b-table-column field="name" label="Texto" sortable>
                    <a :href="'/tags/' + props.row.id + '/editar'">
						{{ props.row.text }}
					</a>
                </b-table-column>

                <b-table-column field="posters_count" label="Nº Cartazes" sortable numeric>
					{{ props.row.posters_count }}
                </b-table-column>
            </template>
        </b-table>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                checkedRows: [],
				loadingTags: true,
				moment: moment,
				tags: [],
				searchText: null
            }
        },

		mounted() {
			this.loadTags();
		},

		methods: {
			deleteTags() {
				let requests = [];
				for (let row of this.checkedRows) {
					requests.push(axios.delete('/tags/' + row.id));
				}
				axios.all(requests)
				.then(response => {
					this.checkedRows = [];
					this.loadTags();
					this.$toast.open({ message: 'Cartazes apagados com sucesso!', type: 'is-success', position: 'is-bottom'});
				}).catch(error => {
					this.$toast.open({ message: 'Erro ao apagar tags', type: 'is-danger', position: 'is-bottom'});
					throw error;
				})
			},
			loadTags() {
				this.loadingTags = true;
				axios.get('/tags', { params: { busca: this.searchText }})
					.then(response => {
						this.tags = response.data;
						this.loadingTags = false;
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao carregar tags', type: 'is-danger', position: 'is-bottom'});
						this.loadingTags = false;
					});
			},
			onDeletePosters() {
                this.$dialog.confirm({
                    title: 'Apagar fotos',
                    message: 'Você tem certeza que deseja <b>apagar</b> esses tags?',
                    confirmText: 'Apagar tags',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => this.deleteTags()
                })
            }
		}
    }
</script>

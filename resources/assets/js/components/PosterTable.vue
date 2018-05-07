<template>
    <section class="section">
		<div>
			<b-field grouped>
				<b-field>
		            <b-input v-model="searchText"
						placeholder="buscar..."
		                type="search"
		                icon="search">
		            </b-input>
		            <p class="control" @click="loadPosters()">
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
            :data="posters"
            :checked-rows.sync="checkedRows"
			default-sort="name"
			:default-sort-direction="'asc'"
			:paginated="true"
            checkable>
			<template slot-scope="props">
                <b-table-column field="name" label="Texto" sortable>
                    <a :href="'/cartazes/' + props.row.id + '/editar'">
						{{ props.row.text }}
					</a>
                </b-table-column>

                <b-table-column field="photos_count" label="Nº Fotos" sortable numeric>
					{{ props.row.photos_count }}
                </b-table-column>
            </template>
        </b-table>

		<b-loading :active.sync="loadingPosters"></b-loading>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                checkedRows: [],
				loadingPosters: true,
				moment: moment,
				posters: [],
				searchText: null
            }
        },

		mounted() {
			this.loadPosters();
		},

		methods: {
			deletePosters() {
				let requests = [];
				for (let row of this.checkedRows) {
					requests.push(axios.delete('/cartazes/' + row.id));
				}
				axios.all(requests)
				.then(response => {
					this.checkedRows = [];
					this.loadPosters();
					this.$toast.open({ message: 'Cartazes apagados com sucesso!', type: 'is-success', position: 'is-bottom'});
				}).catch(error => {
					this.$toast.open({ message: 'Erro ao apagar cartazes', type: 'is-danger', position: 'is-bottom'});
					throw error;
				})
			},
			loadPosters() {
				this.loadingPosters = true;
				axios.get('/cartazes', { params: { busca: this.searchText }})
					.then(response => {
						this.posters = response.data;
						this.loadingPosters = false;
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao carregar cartazes', type: 'is-danger', position: 'is-bottom'});
						this.loadingPosters = false;
					});
			},
			onDeletePosters() {
                this.$dialog.confirm({
                    title: 'Apagar fotos',
                    message: 'Você tem certeza que deseja <b>apagar</b> esses cartazes?',
                    confirmText: 'Apagar cartazes',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => this.deletePosters()
                })
            }
		}
    }
</script>

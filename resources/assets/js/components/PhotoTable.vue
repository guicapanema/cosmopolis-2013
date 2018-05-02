<template>
    <section class="section">
		<div>
			<b-field grouped>
				<b-field>
		            <b-input v-model="searchText"
						placeholder="nome/cidade/fotógrafo"
		                type="search"
		                icon="search">
		            </b-input>
		            <p class="control" @click="loadPhotos()">
		                <button class="button is-primary">Buscar</button>
		            </p>
		        </b-field>
				<b-field expanded>
				</b-field>
				<p class="control">
					<button class="button field is-danger" @click="onDeletePhotos()"
					:disabled="!checkedRows.length">
					<b-icon icon="trash-alt"></b-icon>
						<span>Apagar</span>
					</button>
				</p>
			</b-field>
		</div>

        <b-table
            :data="photos"
            :checked-rows.sync="checkedRows"
			default-sort="name"
			:default-sort-direction="'asc'"
			:paginated="true"
            checkable>
			<template slot-scope="props">
                <b-table-column field="name" label="Nome" sortable>
                    <a :href="'/fotos/' + props.row.id + '/editar'">
						{{ props.row.name }}
					</a>
                </b-table-column>

                <b-table-column field="date" label="Data" sortable>
					{{ moment(props.row.date).format('DD[/]MM[/]YYYY') }}
                </b-table-column>

                <b-table-column field="city" label="Cidade" sortable>
					{{ props.row.city }}
                </b-table-column>

                <b-table-column field="photographer" label="Fotógrafo" sortable>
					{{ props.row.photographer }}
                </b-table-column>

                <b-table-column field="posters_count" label="Nº Cartazes" sortable numeric>
					{{ props.row.posters_count }}
                </b-table-column>
            </template>
        </b-table>

		<b-loading :active.sync="loadingPhotos"></b-loading>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                checkedRows: [],
                columns: [
                    {
                        field: 'name',
                        label: 'Nome',
                    },
                    {
                        field: 'date',
                        label: 'Data',
                    },
                    {
                        field: 'city',
                        label: 'Cidade',
                    },
                    {
                        field: 'photographer',
                        label: 'Fotógrafo',
                    },
                    {
                        field: 'posters',
                        label: 'Nº Cartazes',
                    }
                ],
				loadingPhotos: true,
				moment: moment,
				photos: [],
				searchText: null
            }
        },

		mounted() {
			this.loadPhotos();
		},

		methods: {
			deletePhotos() {
				let requests = [];
				for (let row of this.checkedRows) {
					requests.push(axios.delete('/fotos/' + row.id));
				}
				axios.all(requests)
				.then(response => {
					this.checkedRows = [];
					this.loadPhotos();
					this.$toast.open({ message: 'Imagens apagadas com sucesso!', type: 'is-success', position: 'is-bottom'});
				}).catch(error => {
					this.$toast.open({ message: 'Erro ao apagar imagens', type: 'is-danger', position: 'is-bottom'});
					throw error;
				})
			},
			loadPhotos() {
				this.loadingPhotos = true;
				axios.get('/fotos', { params: { busca: this.searchText }})
					.then(response => {
						this.photos = response.data;
						this.loadingPhotos = false;
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao carregar imagens', type: 'is-danger', position: 'is-bottom'});
						this.loadingPhotos = false;
					});
			},
			onDeletePhotos() {
                this.$dialog.confirm({
                    title: 'Apagar fotos',
                    message: 'Você tem certeza que deseja <b>apagar</b> essas fotos?',
                    confirmText: 'Apagar fotos',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => this.deletePhotos()
                })
            }
		}
    }
</script>

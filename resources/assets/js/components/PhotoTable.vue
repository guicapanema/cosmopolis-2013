<template>
    <section class="section">
        <button class="button field is-danger" @click="onDeletePhotos()"
            :disabled="!checkedRows.length">
            <b-icon icon="trash-alt"></b-icon>
            <span>Apagar</span>
        </button>

        <b-table
            :data="photos"
            :checked-rows.sync="checkedRows"
            checkable>
			<template slot-scope="props">
                <b-table-column field="name" label="Nome">
                    <a :href="'/fotos/' + props.row.id + '/editar'">
						{{ props.row.name }}
					</a>
                </b-table-column>

                <b-table-column field="date" label="Data">
					{{ props.row.date }}
                </b-table-column>

                <b-table-column field="city" label="City">
					{{ props.row.city }}
                </b-table-column>

                <b-table-column field="photographer" label="Fotógrafo">
					{{ props.row.photographer }}
                </b-table-column>

                <b-table-column field="posters" label="Nº Cartazes">
					0
                </b-table-column>
            </template>
        </b-table>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                photos: [],
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
                ]
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
				axios.get('/fotos')
					.then(response => {
						this.photos = response.data;
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao carregar imagens', type: 'is-danger', position: 'is-bottom'});
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

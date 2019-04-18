<template>
    <div>
		<div>
			<b-field grouped>
				<b-field>
		            <b-input v-model="params.busca"
						placeholder="buscar..."
		                type="search"
		                icon="search"
						@keyup.native.enter="loadPosters()">
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
			:loading="loadingPosters"

			paginated
			backend-pagination
			:total="params.total"
			:per-page="params.per_page"
			@page-change="onPageChange"

			backend-sorting
            default-sort-direction="asc"
            :default-sort="[params.sortBy, params.sortOrder]"
			@sort="onSort"

			:checked-rows.sync="checkedRows"
            checkable>
			<template slot-scope="props">
                <b-table-column field="text" label="Texto" sortable>
                    <a :href="'/cartazes/' + props.row.id + '/editar'">
						{{ props.row.text }}
					</a>
                </b-table-column>

				<b-table-column field="photos_count" label="Tipo" sortable>
					{{ props.row.type }}
                </b-table-column>

                <b-table-column field="photos_count" label="Nº Fotos" sortable numeric>
					{{ props.row.photos_count }}
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
				loadingPosters: true,
				moment: moment,
				posters: [],
				params: {
					busca: null,
					page: 1,
					total: 0,
					per_page: 20,
					sortBy: 'text',
					sortOrder: 'asc'
				}
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
				axios.get('/cartazes', { params: this.params})
					.then(response => {
						this.posters = response.data.data;
						this.params.page = response.data.current_page;
						this.params.total = response.data.total;
						this.params.per_page = response.data.per_page;
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
            },

			onPageChange(page) {
				this.params.page = page;
				this.loadPosters();
			},

			onSort(field, order) {
                this.params.sortBy = field;
                this.params.sortOrder = order;
                this.loadPosters();
            },
		}
    }
</script>

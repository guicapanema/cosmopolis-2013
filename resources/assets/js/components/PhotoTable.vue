<template>
    <div>
		<div>
			<b-field grouped>
				<b-field>
		            <b-input v-model="params.busca"
						placeholder="nome/cidade/fotógrafo"
		                type="search"
		                icon="search"
						@keyup.native.enter="loadPhotos()">
		            </b-input>
		            <p class="control" @click="loadPhotos()">
		                <button class="button is-primary">Buscar</button>
		            </p>
		        </b-field>
				<b-field expanded>
				</b-field>
				<p class="control">
					<button class="button field is-danger" @click="onPhotoDelete()"
					:disabled="!checkedRows.length">
					<b-icon icon="trash-alt"></b-icon>
						<span>Apagar</span>
					</button>
				</p>
			</b-field>
		</div>

        <b-table
			:data="photos"
			:loading="loadingPhotos"

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
                <b-table-column field="name" label="Nome" sortable>
                    <a :href="'/fotos/' + props.row.id + '/editar'">
						{{ props.row.name }}
					</a>
                </b-table-column>

                <b-table-column field="date" label="Data" sortable>
					{{ props.row.date }}
                </b-table-column>

                <b-table-column field="city" label="Cidade" sortable>
					{{ props.row.city }}
                </b-table-column>

                <b-table-column field="photographer" label="Fotógrafo" sortable>
					{{ props.row.photographer }}
                </b-table-column>

				<b-table-column field="is_verified" label="Verificada?" centered sortable>
					<b-switch :value="props.row.is_verified" @click.native="onPhotoVerify(props.row)">
		            </b-switch>
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
				loadingPhotos: true,
				moment: moment,
				photos: [],
				params: {
					busca: null,
					page: 1,
					total: 0,
					per_page: 20,
					sortBy: 'name',
					sortOrder: 'asc'
				}
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
				axios.get('/fotos', { params: this.params})
					.then(response => {
						this.photos = response.data.data;
						this.params.page = response.data.current_page;
						this.params.total = response.data.total;
						this.params.per_page = response.data.per_page;

						for (let photo of this.photos) {
							photo.date = photo.date ? moment(photo.date).format('DD[/]MM[/]YYYY') : '';
						}
						this.loadingPhotos = false;
					}).catch(error => {
						this.$toast.open({ message: 'Erro ao carregar imagens', type: 'is-danger', position: 'is-bottom'});
						this.loadingPhotos = false;
					});
			},

			onPageChange(page) {
				this.params.page = page;
				this.loadPhotos();
			},

			onPhotoDelete() {
                this.$dialog.confirm({
                    title: 'Apagar fotos',
                    message: 'Você tem certeza que deseja <b>apagar</b> essas fotos?',
                    confirmText: 'Apagar fotos',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => this.deletePhotos()
                })
            },

			onPhotoVerify(photo) {
				photo.is_verified = !photo.is_verified;
				axios.put('/fotos/' + photo.id, photo)
					.then(response => {
						this.$toast.open({ message: 'Imagem atualizada!', type: 'is-success', position: 'is-bottom'});
					}).catch(error => {
						photo.is_verified = !photo.is_verified;
						this.$toast.open({ message: 'Erro ao atualizar imagem', type: 'is-danger', position: 'is-bottom'});
					});
			},

			onSort(field, order) {
                this.params.sortBy = field;
                this.params.sortOrder = order;
                this.loadPhotos();
            },
		}
    }
</script>

<template>
	<div class="front-container">
		<div class="columns is-gapless">
			<div class="column is-one-quarter">
				<front-sidebar></front-sidebar>
			</div>
			<div class="column">
				<front-photo-list :filters="filters"></front-photo-list>
			</div>
		</div>
		<front-photo-single
			v-if="photo_id"
			class="single-photo-overlay">
		</front-photo-single>
	</div>
</template>

<script>
	export default {

		data() {
            return {
				filters: {},
				photo_id: null
            }
        },

        mounted() {
			this.parsePath();
        },

		methods: {
			parsePath() {
				if (this.$route.params.foto) {
					this.photo_id = this.$route.params.foto;
				} else {
					this.photo_id = null;
				}
			}
		},

		watch: {
			'$route' (to, from) {
				this.parsePath();
			}
		}
    }
</script>

<style scoped>
	.single-photo-overlay {
		position:fixed;
		top:0;
		left:0;
		z-index:100;
		width:100%;
		height:100%;
		background: white;
		overflow: auto;
	}
</style>

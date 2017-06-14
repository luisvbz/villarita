<template>
	<div id="demo">
		<div class="row">
			<div class="col-lg-6" v-for="n in noticias">
				<div class="box box-success">
					<div class="box-header with-border">
						<h4>Ultima noticia</h4>
					</div>
					<div class="panel-body">
					<h4>{{ n.title }}</h4>
						<p v-html="n.body.substring(0,250)">
						</p>.....	
					</div>
					<div class="panel-footer">
						<router-link to="/noticias" class="btn btn-xs btn-success">Ir a noticias</router-link>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="box box-warning">
					<div class="box-header with-border">
						<h4>Eventos</h4>
					</div>
					<div class="panel-body">
						<p>Lorem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						 orem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						 orem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						 orem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						 orem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						 orem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="box box-danger">
					<div class="box-header with-border">
						<h4>Otro</h4>
					</div>
					<div class="panel-body">
						<p>Lorem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						 orem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						 orem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						 orem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						 orem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						 orem ipsum at sit ame lorem ipsum at sit ame Lorem ipsum at sit ame
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
  export default {
    data() {
            return {
                noticias: []
            }
        },
        mounted(){  
                this.getNoticias()
        },
        methods: {
            getNoticias: function(){

              this.$http.get('/api/noticias?page='+1).then(response => {
                  this.noticias.push(response.body.data[0])  
              }, response => {

              })
            }
      }
  }
</script>
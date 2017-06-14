<template>
	<div class="row">
		<div class="col col-lg-12">
			<div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Noticias</h3>
              <button class="btn btn-md btn-primary pull-right">Nueva <i class="fa fa-plus"></i></button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
              	<thead>
              	 <tr>
                  <th style="width: 10%">Fecha</th>
                  <th style="width: 75%">Titulo</th>
                  <th style="width: 15%">Autor</th>
                  <th style="width: 10%">Acciones</th>
                </tr>	
              	</thead>
                <tbody>
                <tr v-for="n in noticias">
                	<td>{{ n.created_at | formatDate('DD/MM/YYYY') }}</td>
                	<td>{{ n.title }}</td>
                	<td>{{n.user.name }}</td>
                	<td>
                		<button class="btn btn-xs btn-success"><i class="fa fa-edit"></i></button>
                		<button class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></button>
                	</td>
                </tr>
              </tbody>
              </table>
            </div>
          </div>
		</div>
	</div>
</template>
<script>
  import auth from '../../../services/auth';
    import router from '../../../routes';
  export default {
    data() {
            return {
                auth: auth,
                noticias: []
            }
        },
        mounted(){
		      if(!auth.user.authenticated || auth.user.profile.rol == 3)
		      {
		        return router.push({path: '/'})
		      }
                this.getNoticias()
        },
        methods: {
            getNoticias: function(){

            	this.$http.get('/api/social').then(response => {
            		for (var i = 0; i < response.body.length; i++) {
            			this.noticias.push(response.body[i])	
            		};
            	}, response => {

            	})
            }
      }
  }
</script>
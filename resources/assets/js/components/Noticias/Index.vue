<template>
<div>
 <div class="row" v-for="n in noticias">
   <div class="col-md-12">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                  <div class="pull-left image icon">
                    <div class="img-circle">
                           {{ n.user.name.substring(0,1) }}
                    </div>
                  </div>
                <span class="username"><a href="#">{{ n.user.name }}</a></span>
                <span class="description">Publicado - {{ n.created_at | formatDate('DD/MM/YY HH:MM')}}</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Attachment -->
              <div class="attachment-block clearfix">
                <img class="attachment-img" src="/dist/img/photo1.png" alt="Attachment Image">

                <div class="attachment-pushed">
                  <h4 class="attachment-heading"><router-link :to="{name: 'noticia.show', params: {slug: n.slug }}">{{ n.title }}</router-link></h4>

                  <div class="attachment-text">
                    <div v-html="n.body.substring(0,200)"></div>
                  </div>
                </div>
              </div>
              <span class="pull-right text-muted">2 comentarios</span>
            </div>
          </div>
          <!-- /.box -->
        </div>
 </div>
 </div>
</template>

<script>
  import auth from '../../services/auth';
  import router from '../../routes';
  export default {
    data() {
            return {
                auth: auth,
                noticias: []
            }
        },
        mounted(){  
                this.getNoticias()
        },
        methods: {
            getNoticias: function(){

              this.$http.get('/api/noticias?page='+1).then(response => {
                for (var i = 0; i < response.body.data.length; i++) {
                  this.noticias.push(response.body.data[i])  
                };
              }, response => {

              })
            }
      }
  }
</script>
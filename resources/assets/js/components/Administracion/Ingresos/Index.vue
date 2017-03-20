<template>
  <div>
   <div class="row">
     <div class="col-md-12 form-horizontal">
      
      <form class="form-horizontal" v-on:submit.prevent="generarCobro">
          <div class="col-xs-3">
              <select class="form-control"  v-model="cobro.tipo_ingreso" id="tipocobro">
                <option v-for="t in tipoingresos" :value="t.id">{{ t.descripcion }}</option>
              </select>
          </div>
          <div class="col-xs-2">
              <select class="form-control" v-model="anio">
                <option v-for="a in anios" :value="a.aniofiscal">{{ a.aniofiscal }}</option>
              </select>
          </div>
          <div class="col-xs-2">
              <select class="form-control" v-model="cobro.codperi" id="periodo">
                <option v-for="p in periodos" :value="p.cod">{{ p.descripcion }}</option>
              </select>
          </div>
          <div class="col-xs-2">
              <input type="number" class="form-control" placeholder="Monto: Ej: 5000.00" v-model="cobro.monto" required>
          </div>
          <div class="col-xs-2">
            <button class="btn btn-primary" type="submit"><i class="fa fa-dollar"></i> Generar cobro</button>
          </div>
      </form>
      </div>
   </div>
   <br>
   <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Cobros</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-reponsive table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="text-align: center"><input type="checkbox" id="all"  v-model="allSelected"></th>
                    <th>Casa</th>
                    <th>Propietario o Inquilino</th>
                    <th style="text-align: right">Saldo</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(casa, index) in casas">
                    <td style="text-align: center">
                    <input type="checkbox" :id="casa.casa_id" v-bind:value="casa.casa_id" v-model="cobro.casas">
                    </td>
                    <td>{{ casa.numero}}</td>
                    <td>{{ casa.propietario }}</td>
                    <td style="text-align: right">Bs. {{ casa.saldo}}</td>
                    <td style="text-align: center"><router-link :to="{name: 'admin.estcuenta', params: { casa: casa.numero}}" class="btn btn-success"><i class="fa fa-list"></i> Estado de Cuenta</button></td>
                  </tr>
                </tbody>
              </table>
          </div>
          <div class="overlay" v-if="loading">
              <i class="fa fa-refresh fa-spin"></i>
           </div>
       <!-- /.box -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
 import router from '../../../routes';
 import auth from '../../../services/auth';
  export default {
    data () {
      return {
        casas: [],
        anios: [],
        periodos: [],
        tipoingresos: [],
        anio: '2017',
        loading: false,
        viewForm: false,
        auth:auth,
        cobro: {tipo_ingreso: '1', anio: '2017',codperi: '001', monto:'', casas: []},
        allSelected: false
      }
    }, 
    mounted(){
      
      if(!auth.user.authenticated)
      {
        return router.push({path: '/'})
      }
      
      Pace.restart();

     this.getCasas();
     this.getAnios(this.anio);
     this.getTiposIngresos();
     this.getPeriodos(this.anio);
    },
    watch:{
      anio: function(){
        this.getPeriodos(this.anio);
        this.cobro.anio = this.anio;
        },
      allSelected: function(){
        if(!this.allSelected){

          this.cobro.casas = [];

        }else{

          for (var i = 0; i < this.casas.length; i++) {
               this.cobro.casas.push(this.casas[i].casa_id);
          };
          
        }
      }
    },
    methods: {
      getCasas: function(){
         this.loading = true;
         this.casas = [];
       this.$http.get('/api/ingresos').then(response => {
            for (var i = 0; i < response.body.length; i++) {
              var data = response.body[i];
              this.casas.push(data);
            }
            this.loading = false;
           // console.log(auth.user);

        }, response => {
            //console.log(error);
        })
      },
      getAnios: function(){

        this.$http.get('/api/aniofiscal').then(response => {
          for (var i = 0; i < response.body.length; i++) {
            this.anios.push(response.body[i]) 
          }
        }, response => {

        })
      },
      getPeriodos: function(anio){
        //this.loading = true;
        this.$http.get('/api/periodos/'+anio).then(response => {
          this.periodos = [];
          for (var i = 0; i < response.body.length; i++) {
            this.periodos.push(response.body[i])  
          }
          //this.loading = false;
        }, response => {

        })
      },
      getTiposIngresos: function(){

        this.$http.get('/api/tipoingresos').then(response => {
          for (var i = 0; i < response.body.length; i++) {
            this.tipoingresos.push(response.body[i]) 
          }
        }, response => {

        })
      },
      toggleCobro: function(){

        if(!this.viewForm){
          this.viewForm = true;
        }else{
          this.viewForm = false;
        }
      },
      generarCobro: function(){

          var nomperiodo = $("#periodo :selected").text();
          var nomtipocobro = $("#tipocobro :selected").text();

          if(this.cobro.casas.length == 0){

              return alert('Debes seleccionar al menos una casa!');

          }

          var r = confirm('Deseas generar: ' + nomtipocobro +', para '+ nomperiodo +', del año fiscal '+ this.anio + '?');

          if(r == true){
            this.loading = true; 
            this.$http.post('/api/ingresos/generarCobro', {cobro: this.cobro}).then(response => {

                  if(response.body.save && response.body.casas.length == 0){

                      alert(response.body.msj);

                      return this.getCasas();

                  }else if(response.body.save && response.body.casas.length > 0){

                      alert('Los cobros se generaron, excepto para las casas '+ response.body.casas + ', Ya tenian esta cuota registrada');

                      return this.getCasas();

                  }else if(!response.body.save && response.body.casas.length > 0){

                      alert('Ya este cobro se realizo anteriormente para todas las casas');

                      return this.getCasas();
                  }else{

                      alert('Ocurrio un error al generar los cobros');
                  }
            
            this.loading = false; 

            });
          }
          
      }
    }
  }
</script>
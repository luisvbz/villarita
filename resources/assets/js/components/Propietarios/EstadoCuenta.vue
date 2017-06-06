<template>
	<div>
  <div class="row">
     <div class="col-md-10 form-horizontal" v-if="auth.user.profile.rol == 1 || auth.user.profile.rol == 2">
          <div class="col-xs-2">
            <button class="btn btn-success" @click="showModal = true"><i class="fa fa-dollar"></i> Aplicar pago</button>
          </div>
          <div class="col-xs-2">
              <button class="btn btn-warning" v-if="!descargando" @click="getPdf()">Descargar  <i class="fa fa-download"></i></button>
              <button class="btn btn-warning" disabled="" v-else>Descargando  <i class="fa fa-spinner fa-spin"></i></button>
          </div>
          <div class="col-xs-2">
            <select class="form-control" v-model="anio">
              <option v-for="anio in anios" :value="anio.aniofiscal">{{ anio.aniofiscal }}</option>
              <option value="0">Todo</option>
            </select>
          </div>
          </div>
          <div class="col-md-10 form-horizontal" v-else>
          <div class="col-xs-2">
            <select class="form-control" v-model="anio">
              <option v-for="anio in anios" :value="anio.aniofiscal">{{ anio.aniofiscal }}</option>
              <option value="0">Todo</option>
            </select>
          </div>
            <div class="col-xs-2">
              <button class="btn btn-warning" v-if="!descargando" @click="getPdf()">Descargar  <i class="fa fa-download"></i></button>
              <button class="btn btn-warning" disabled="" v-else>Descargando  <i class="fa fa-spinner fa-spin"></i></button>
          </div>
          </div>
      </div>
   <br>
	<section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <img src="/img/logo_small.png" width="190" height="74">
           <span class="pull-right">Estado de cuenta, Casa <strong># {{ casa.numero }}</strong>
           </span> 
          </h2>
        </div>
        <!-- /.col -->
      </div>

      <!-- /.row -->

      <!-- Table row -->
  <div class="row" v-if="cargando">
      <center>
        <i class="fa fa-spinner fa-spin" style="color: #00a65a; font-size:40px;"></i>
        <br>Cargando estado de cuenta ...
      </center>
  </div>
    <div class="row" v-else>
        <div class="col-xs-10 col-xs-offset-1">
        <table class="table table-striped" style="width: 100%;">
          <thead>
            <tr>
                <th>Cedula</th>
                <th>Propietario</th>
            </tr>
            </thead>
              <tbody>
                <tr>
                  <td>{{ propietario.cedula | currency('', 0)}}</td>
                  <td>{{ propietario.apellidos }}, {{ propietario.nombres }}</td>
                </tr>
              </tbody>
        </table>
        <table class="table table-striped" style="width: 100%;">
          <thead>
                <th style="text-align: center">DETALLES DE LA CUENTA</th>
              </thead>
        </table>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Nª</th>
              <th>FECHA</th>
              <th>DETALLE DE MOVIMIENTO</th>
              <th style="text-align: right;">DEBITOS</th>
              <th style="text-align: right;">CREDITOS</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="(c, index) in cuentas">
                <td>{{ index + 1 }}</td>
              <td>{{ c.created_at | formatDate('DD/MM/YYYY') }}</td>
               <td  v-if="c.tipo == 'D'">{{ c.tipo_ingreso.descripcion }} / {{ c.periodo.descripcion }} {{ c.anio }}</td>
               <td  v-if="c.tipo == 'C'">
                <template v-if="c.forma_pago_id == 1">
                  Transferencia: {{ c.referencia}}/ 
                </template>
                <template v-else-if="c.forma_pago_id == 2">
                  Deposito: {{ c.referencia}}/ 
                </template>
                <template v-else>
                  Pago recibido en efectivo
                </template>
           </td>
              <td style="text-align: right;"><span v-if="c.tipo == 'D'" class="red">{{ c.monto | currency('')}}</span></td>
              <td style="text-align: right;"><span v-if="c.tipo == 'C'" style="color: #00a65a;">{{ c.monto | currency('')}}</span></td>
              <td></td>
              </tr>
          </tbody>
          <tfoot>
            <tr style="border-bottom: 1px dotted #ccc;">
              <td colspan="4" style="text-align: right;"><b>Total Debitos</b></td>
              <td style="text-align: right;"><span class="red"><b>{{ totalDebitos  | currency('')}}</b></span></td>
            </tr>
            <tr>
              <td colspan="4" style="text-align: right;"><b>Total Creditos</b></td>
              <td style="text-align: right;"><span style="color: #00a65a;"><b>{{ totalCreditos | currency('') }}</b></span></td>
            </tr>
            <tr>
              <td colspan="4" style="text-align: right;"><b>Saldo total</b></td>
              <td style="text-align: right;"><span :style="totalMonto < 0 ? 'color: #f56954;' : 'color: #00a65a;'"><b>{{ totalMonto  | currency('')}}</b></span></td>
            </tr>
          </tfoot>
        </table>
	</div>

  </div>
      
    </section>
    <!-- Modal para aplicar pagos -->
 <transition name="modal" v-if="showModal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">

            <div class="modal-header">
                <h3>Registrar pago a la casa # {{ casa.numero }}</h3>
              <button class="btn btn-xs pull-right btn-danger" style="margin-top: -60px; margin-right:-30px;" @click="showModal = false">
                  x
              </button>
            </div>

            <div class="modal-body">
              <form v-on:submit.prevent="pagar">
         <div class="form-group">
                  <label>Seleccione la forma de pago</label> 
                  <select class="form-control" v-model="nuevoPago.tipoPago">
                    <option value="1">Transferencia</option>
                    <option value="2">Deposito</option>
                    <option value="3">Efectivo</option>
                  </select>
            </div>
            <div class="form-group" v-if="nuevoPago.tipoPago != 3">
                <select class="form-control" v-model="nuevoPago.cuenta">
                <option disabled value="">-- Seleccion la cuenta destino ---</option>
                  <option v-for="cuenta in bancos" :value="cuenta.id">{{cuenta.banco.descripcion}} - 0{{ cuenta.numero }}</option>  
                </select>
            </div>
            <div class="form-group" v-if="nuevoPago.tipoPago != 3">
              <label v-if="nuevoPago.tipoPago == 1">Indique la referencia de la transferencia</label>
              <label v-if="nuevoPago.tipoPago == 2">Indique el numero del deposito</label>
              <input type="text" class="form-control" v-model="nuevoPago.ref">
            </div>
            <div class="form-group">
                <label>Monto pagado</label>
                <input type="text" required class="form-control" v-model="nuevoPago.monto">
            </div>
            <button class="btn btn-success pull-right" v-if="!registrando" type="submit">Registrar pago  <i class="fa fa-check"></i></button>
            <button class="btn btn-success pull-right" v-else disabled>Registrando  <i class="fa fa-spinner fa-spin"></i></button>
            </form>
            </div>
          </div>
        </div>
      </div>
    </transition>
	</div>
</template>
<script>
 import router from '../../routes';
 import auth from '../../services/auth';
 import Ruta from '../Ruta.vue';
 import moment from 'moment';
 import modal from './modal.vue';
  export default {
    data () {
      return {
        auth: auth,
        existe: true,
        casa: {},
        propietario: {cedula: '', apellidos: '', nombres: '', fecnac: '',  inquilino: false},
        conyuge: {},
        hijos: [],
        vehiculos: [],
        cuentas: [],
        totalDebitos: 0.00,
        totalCreditos: 0.00,
        totalMonto: 0.00,
        auth:auth,
        loading: false,
        bancos: [],
        selected_cuota: null,
        anios: [],
        anio: null,
        nuevoPago: {tipoPago: 3, cuenta: '', ref: null, monto: null},
        descargando: false,
        cargando: false,
        miCasa: null,
        showModal: false,
        registrando: false
      }
    }, 
    mounted(){
      
      if(!auth.user.authenticated)
      {
        return router.push({path: '/'})
      }

        if(this.auth.user.profile.rol == 3){
            this.miCasa = this.auth.user.profile.casa;
        }else{
            this.miCasa = this.$route.params.casa;
        }

      this.getCasa();
      this.getAnios();
      //this.getEstCuenta();
      this.getCuentas();

    },
    components:{
      modal
    },
    computed: {
      totalDeuda: function(){

        var total = (this.totalDebitos - this.totalCreditos);

        return total;
      },

      totalApagar: function(){

        var cuotas = this.nuevoPago.cuotas;

        var total = 0.00

        for (var i = 0; i < this.nuevoPago.cuotas.length; i++) {

          total = parseFloat(total) + parseFloat(cuotas[i].monto);
              
        }

        return total;

      }
    },
    watch: {
      '$route' (to, from){
        if(from.params.casa !== to.params.casa){
          return this.getCasa();
        }
      },
      anio: function(){
        this.getEstCuenta();
      }
    },
    methods: {
      getCasa: function(){
        this.loading = true;
        this.hijos = [];
        this.vehiculos = [];

        this.$http.get('/api/casas/'+this.miCasa).then(response => {
            var data = response.body[0];
            //console.log(data);
            if(response.body.length == 0){
              this.existe = false;
               this.loading = false;
            }else{
              this.casa = data.casa;
              this.conyuge = data.conyuge;

              for (var i = 0; i < data.hijos.length; i++) {
                this.hijos.push(data.hijos[i]);
              };

              for (var i = 0; i < data.vehiculos.length; i++) {
                this.vehiculos.push(data.vehiculos[i]);
              };
            
              //data propietario
              this.propietario.cedula = data.cedula;
              this.propietario.apellidos = data.apellidos;
              this.propietario.nombres = data.nombres;
              this.propietario.fecnac = data.fecnac;
              this.existe = true;
              this.loading = false;
            }

        }, response => {
            //console.log(error);
        })
      },
      getEstCuenta: function(){

        this.cargando = true;
        this.cuentas = [];

        this.totalMonto= 0.00;
        this.totalDebitos= 0.00;
        this.totalCreditos= 0.00;

      	 this.$http.get('/api/estcuenta/'+this.miCasa+'/'+this.anio).then(response => {

      	 		for (var i = 0; i < response.body.length; i++) {
      	 			
      	 			this.cuentas.push(response.body[i]);

              this.totalMonto = parseFloat(this.totalMonto) + parseFloat(this.cuentas[i].monto);

              if(this.cuentas[i].tipo == 'D'){
                  this.totalDebitos = parseFloat(this.totalDebitos) + parseFloat(this.cuentas[i].monto);
              }

              if(this.cuentas[i].tipo == 'C'){
                 this.totalCreditos = parseFloat(this.totalCreditos) + parseFloat(this.cuentas[i].monto); 
              }

      	 		};
          this.cargando = false;

      	 }, response => {
            this.$swal("Ocurrio un error al cargar el estado de cuenta!");
            this.cargando = false;
         })
      },
      getCuentas: function(){

        this.$http.get('/api/cuentas').then(response => {

          for (var i = 0; i < response.body.length; i++) {
            this.bancos.push(response.body[i]) 
          }
        }, response => {

        })
      },
      getAnios: function(){

          this.$http.get('/api/aniofiscal').then(response => {
            for (var i = 0; i < response.body.length; i++) {
                this.anios.push(response.body[i]) 
              } 

              this.anio = this.anios[0].aniofiscal;
          })
      },
      pagar: function(){

        if(this.anio == 0){
          var anio = this.anios[0].aniofiscal;
        }else{
          var anio = this.anio;
        }

        if(this.nuevoPago.tipoPago == 3){
            this.nuevoPago.cuenta = 1;
        }

        var r = confirm("Estas seguro de registrar este pago?");


        if(r){
          this.registrando = true
          this.$http.post('/api/pagar', {pagos: this.nuevoPago, casa_id: this.casa.id, anio: anio }).then(response => {

             if(response.body.save){

                   this.nuevoPago = {tipoPago: 3, cuenta: null, ref: null, cuotas: [], monto: ''}

                   //this.getPendientes();
                   this.showModal = false
                   this.registrando = false
                   this.$swal({
                    title: "Exito!",
                    text: 'El pago se registró con exito!',
                    type: 'success'
                   })
                   this.getEstCuenta();

             }
          })
          }
      },
      getPdf: function(){

        this.descargando = true;
          this.$http.post('/api/pdf', {casa_id: this.casa.id, anio: this.anio, propietario: this.propietario, numero: this.casa.numero}, {responseType: 'arraybuffer'}).then(function(response){
              var headers = response.headers;
              var blob = new Blob([response.data], {type: headers['content-type']});
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              console.log(link);
              link.download = "EstadoDeCuenta"+this.casa.numero+"_"+this.anio+".pdf";
              link.click();
              this.descargando = false;
          });
        }
    },
    components: {
      Ruta
    }
  }
</script>
<style>
.tg  {border-collapse:collapse;border-spacing:0; width:100%;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-wr85{font-weight:bold;background-color:#efefef;text-align:center}
.tg .tg-yeb6{font-weight:bold;background-color:#efefef;text-align:right}
.tg .tg-ipa1{font-weight:bold;background-color:#c0c0c0;text-align:center}
.red {color: #f56954;},
.green {color: #00a65a;}
 .modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 500px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

.cerrar{
    position: absolute;
  }
</style>
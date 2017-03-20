<template>
	<div>
  <div class="row">
     <div class="col-md-12 form-horizontal">
          <div class="col-xs-3">
            <button class="btn btn-success" data-toggle="modal" data-target="#nuevoPago"><i class="fa fa-dollar"></i> Aplicar pago</button>
            <button class="btn btn-warning"> Imprimir <i class="fa fa-print"></i></button>
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
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1">
	       <table class="tg">
			  <tr>
			    <th class="tg-ipa1" colspan="6">ESTADO DE CUENTA</th>
			  </tr>
			  <tr>
			    <td class="tg-yeb6">Cedula</td>
			    <td class="tg-031e">{{ propietario.cedula | currency('', 0)}}</td>
			    <td class="tg-yeb6">Propietario</td>
			    <td class="tg-031e">{{ propietario.apellidos }}, {{ propietario.nombres }}</td>
			    <td class="tg-yeb6">Casa</td>
			    <td class="tg-031e">{{ casa.numero }}</td>
			  </tr>
			<!--  <tr>
			    <td class="tg-031e"></td>
			    <td class="tg-031e"></td>
			    <td class="tg-031e"></td>
			    <td class="tg-031e"></td>
			    <td class="tg-031e"></td>
			    <td class="tg-031e"></td>
			  </tr>-->
			  <tr>
			    <td class="tg-ipa1" colspan="6">DETALLES DE CUENTA</td>
			  </tr>
			  <tr>
			    <td class="tg-wr85">Nº</td>
			    <td class="tg-wr85">FECHA</td>
			    <td class="tg-wr85" colspan="2">DETALLES DEL MOVIMIENTO</td>
			    <td class="tg-wr85">DEBITOS</td>
			    <td class="tg-wr85">CREDITOS</td>
			  </tr>
			  <tr v-for="(c, index) in cuentas">
			    <td class="tg-031e">{{ index + 1 }}</td>
			    <td class="tg-031e">{{ c.created_at | formatDate('DD/MM/YYYY') }}</td>
			    <td class="tg-031e" colspan="2">{{ c.tipo_ingreso.descripcion }} / {{ c.periodo.descripcion }} {{ c.anio }}</td>
			    <td class="tg-031e">{{ c.deuda | currency('Bs. ')}}</td>
          <td class="tg-031e" v-if="c.confirmado">
            <span style="color: #00a65a;">{{ c.pago | currency('Bs. ')}} <i class="fa fa-check-circle"></i></span>
          </td>
			    <td class="tg-031e" v-else><span :style="c.pago > 0 ? 'color: #f39c12;' : ''">{{ c.pago | currency('Bs. ')}} <i v-if="c.pago > 0" class="fa fa-warning"></i></span></td>
			  </tr>
        <tr>
          <td class="tg-031e" style="text-align:right;" colspan="4"><b>Subtotal</b></td>
          <td class="tg-031e">{{ totalDebitos  | currency('Bs. ')}}</td>
          <td class="tg-031e">{{ totalCreditos | currency('Bs. ')}}</td>
        </tr>
        <tr>
          <td class="tg-031e" style="text-align:right;" colspan="4"><b>Total deuda</b></td>
          <td class="tg-031e" colspan="2"><span :style="totalDeuda > 0 ? 'color: #f56954;' : ''">{{ totalDeuda  | currency('Bs. ')}}</span></td>
        </tr>
		</table>
	</div>

  </div>
      <!-- this row will not appear when printing -->
    </section>
    <!-- Modal para aplicar pagos -->
    <div class="modal fade" id="nuevoPago" tabindex="-1" role="dialog" aria-labelledby="Nuevo" aria-hidden="true">
  <form v-on:submit.prevent="pagar">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Aplicar pago a la casa # {{ casa.numero }}</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                  <label>Seleccione la forma de pago</label> 
                  <select class="form-control" v-model="nuevoPago.tipoPago">
                    <option value="1">Transferencia</option>
                    <option value="2">Deposito</option>
                    <option value="3">Efectivo</option>
                  </select>
            </div>
            <div class="form-group" v-if="nuevoPago.tipoPago != 3">
                <label>Selecciona la cuenta destino</label>
                <select class="form-control" v-model="nuevoPago.cuenta">
                  <option v-for="cuenta in bancos" :value="cuenta.id">{{cuenta.banco.descripcion}} - 0{{ cuenta.numero }}</option>  
                </select>
            </div>
            <div class="form-group" v-if="nuevoPago.tipoPago != 3">
              <label v-if="nuevoPago.tipoPago == 1">Indique la referencia de la transferencia</label>
              <label v-if="nuevoPago.tipoPago == 2">Indique el numero del deposito</label>
              <input type="text" class="form-control" v-model="nuevoPago.ref">
            </div>
            <div class="form-group">
              <label>Selecciona la cuota o cuotas a pagat</label>
              <select class="form-control" v-model="selected_cuota">
                <option :value="index" v-for="(p, index) in pendientes">
                  {{ p.tipo_ingreso.descripcion }} / {{ p.periodo.descripcion }} {{ p.anio }} / {{ p.deuda | currency('Bs.') }}
                </option>
              </select>
              <br>
              <button class="btn btn-primary btn-xs" @click="addCuota">Añdir</button>
            </div>
            <div class="from-group" v-if="nuevoPago.cuotas.length > 0">
                <table class="tg">
                    <tr>
                      <th>#</th>
                      <th>Concepto</th>
                      <th>Monto</th>
                      <th></th>
                    </tr>
                    <tr v-for="(c, index) in nuevoPago.cuotas">
                      <td>{{ index + 1}}</td>
                      <td>{{ c.tipo_ingreso.descripcion }} / {{ c.periodo.descripcion }} {{ c.anio }}</td>
                      <td>{{ c.deuda | currency('Bs. ') }}</td>
                      <td><button @click="removeCuota(index)" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i></button></td>
                    </tr>
                    <tr>
                      <td colspan="2">Total a pagar</td>
                      <td>{{ totalApagar | currency('Bs. ')}}</td>
                    </tr>
                </table>
            </div>
            <div class="form-group">
                <label>Monto pagado</label>
                <input type="text" required class="form-control" v-model="nuevoPago.monto">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Aplicar <i class="fa fa-save"></i></button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>
	</div>
</template>
<script>
 import router from '../../routes';
 import auth from '../../services/auth';
 import Ruta from '../Ruta.vue';
 import moment from 'moment';
  export default {
    data () {
      return {
        existe: true,
        casa: {},
        propietario: {cedula: '', apellidos: '', nombres: '', fecnac: '', inquilino: false},
        conyuge: {},
        hijos: [],
        vehiculos: [],
        cuentas: [],
        totalDebitos: 0.00,
        totalCreditos: 0.00,
        auth:auth,
        loading: false,
        pendientes: [],
        bancos: [],
        selected_cuota: null,
        nuevoPago: {tipoPago: 3, cuenta: null, ref: null, cuotas: [], monto: 0.00}
      }
    }, 
    mounted(){
      
      if(!auth.user.authenticated)
      {
        return router.push({path: '/'})
      }

      this.getCasa();
      this.getEstCuenta();
      this.getPendientes();
      this.getCuentas();

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

          total = parseFloat(total) + parseFloat(cuotas[i].deuda);
              
        }

        return total;

      }
    },
    watch: {
      '$route' (to, from){
        if(from.params.casa !== to.params.casa){
          return this.getCasa();
        }
      }
    },
    methods: {
      getCasa: function(){
        this.loading = true;
        this.hijos = [];
        this.vehiculos = [];
        this.$http.get('/api/casas/'+this.$route.params.casa).then(response => {
            var data = response.body[0];
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
              this.propietario.inquilino = data.inquilino;
              this.existe = true;
              this.loading = false;
            }

        }, response => {
            //console.log(error);
        })
      },
      getEstCuenta: function(){

        this.cuentas = [];

        this.totalDebitos = 0.00;

        this.totalCreditos = 0.00;

      	 this.$http.get('/api/estcuenta/'+this.$route.params.casa).then(response => {

      	 		for (var i = 0; i < response.body.length; i++) {
      	 			
      	 			this.cuentas.push(response.body[i]);

              this.totalDebitos = this.totalDebitos + response.body[i].deuda;

              if(response.body[i].confirmado){
              
              this.totalCreditos = this.totalCreditos + response.body[i].pago;

              }
      	 		};

      	 })
      },
      getPendientes: function(){

        this.$http.get('/api/ingresos/pagosPendientes/' + this.$route.params.casa).then(response => {
            
            for (var i = 0; i < response.body.length; i++) {

              this.pendientes.push(response.body[i]);

            }
        });
      },
      getCuentas: function(){

        this.$http.get('/api/cuentas').then(response => {

          for (var i = 0; i < response.body.length; i++) {
            this.bancos.push(response.body[i]) 
          }
        }, response => {

        })
      },
      addCuota: function(){

            this.nuevoPago.cuotas.push(this.pendientes[this.selected_cuota]);
            this.pendientes.splice(this.selected_cuota, 1);
      },
      removeCuota: function(index){

          this.pendientes.push(this.nuevoPago.cuotas[index]);
          this.nuevoPago.cuotas.splice(index, 1);
      },
      pagar: function(){

          this.$http.post('/api/pagar', {pagos: this.nuevoPago, casa_id: this.casa_id }).then(response => {

             if(response.body.save){

                   this.nuevoPago = {tipoPago: 3, cuenta: null, ref: null, cuotas: [], monto: 0.00}

                   this.getPendientes();

                   this.getEstCuenta();

             }

          })
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
</style>
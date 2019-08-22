<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol>
    <div class="container-fluid">
      <div class="row">
        <!-- Ejemplo de tabla Listado -->
        <div class="card col-md-8">
          <div class="card-header">
            <i class="fa fa-align-justify"></i>
            Ventas
            <template v-if="idroles==2">
              <button
                type="button"
                disabled
                @click="abrirModal('venta','registrar')"
                class="btn btn-secondary"
              >
                <i class="icon-plus"></i>&nbsp;Nuevo
              </button>
            </template>
            <template v-else>
              <button
                type="button"
                @click="abrirModal('venta','registrar')"
                class="btn btn-secondary"
              >
                <i class="icon-plus"></i>&nbsp;Nuevo
              </button>
            </template>

            <i class="float-right">
              <td>Periodo Actual : </td>
              <td><strong>{{ periodoActual}}</strong></td>
              <td>
                - Total Tokens :
                <strong>{{ totalAcum = formatPrice(AcumuladoTokens) }}</strong>
              </td>
             
            </i>
          </div>
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-3" v-model="criterio">
                    <option value="nombre">Nombre</option>
                    <option value="nombre_plataforma">Plataforma</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listarVenta(1,buscar,criterio)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <button
                    type="submit"
                    @click="listarVenta(1,buscar,criterio)"
                    class="btn btn-primary"
                  >
                    <i class="fa fa-search"></i> Buscar
                  </button>
                </div>
              </div>
            </div>
            <table class="table table-bordered table-striped table-responsive-xs">
              <thead>
                <tr>
                  <th>Opciones</th>
                  <th>Fecha</th>
                  <th>Nombres</th>
                  <th>Plataforma</th>
                  <th>Cant.</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="venta in arrayVenta" :key="venta.id">
                  <td>
                    <button
                      type="button"
                      @click="abrirModal('venta','actualizar',venta)"
                      class="btn btn-warning btn-sm"
                    >
                      <i class="icon-pencil"></i>
                    </button>
                    &nbsp;
                    <template v-if="venta.condicion">
                      <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        @click="desactivarVenta(venta.id)"
                      >
                        <i class="icon-trash"></i>
                      </button>
                    </template>

                    <template v-else>
                      <button
                        type="button"
                        class="btn btn-info btn-sm"
                        @click="activarVenta(venta.id)"
                      >
                        <i class="icon-check"></i>
                      </button>
                    </template>
                  </td>
                  <td v-text="venta.fecha"></td>
                  <td v-text="venta.nombre_completo"></td>
                  <td v-text="venta.nombre_plataforma"></td>
                  <td v-text="formatPrice(venta.cantidad)" align="right"></td>

                  <td>
                    <div v-if="venta.condicion">
                      <span class="badge badge-success">Activo</span>
                    </div>
                    <div v-else>
                      <span class="badge badge-danger">Inactivo</span>
                    </div>
                  </td>
                </tr>
                <!---->
              </tbody>
            </table>
            <nav>
              <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(pagination.current_page -1,buscar,criterio)"
                  >Ant</a>
                </li>
                <li
                  class="page-item"
                  v-for="page in pageNumber"
                  :key="page"
                  :class="[page == isActived ? 'active' : '']"
                >
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(page,buscar,criterio)"
                    v-text="page"
                  ></a>
                </li>
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)"
                  >Sig</a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
        <!-- Ejemplo de Consolidado -->
        <div class="card col-md-4">
          <div class="card-header">
            <i class="fa fa-calculator"></i>
            Acumulado período
          </div>
          <div class="card-body">
            <!--   <div class="form-group row">
            <div class="col-md-12">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nombre">Nombre</option>
                  <option value="cantidad">Cantidad</option>
                </select>
                <input
                  type="text"
                  v-model="buscar" @keyup.enter="acumularVenta(1,buscar,criterio)"
                  class="form-control"
                  placeholder="Texto a buscar"
                />
                <button type="submit" @click="acumularVenta(1,buscar,criterio)" class="btn btn-primary">
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
            </div>-->
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>Nombres</th>
                  <th>Cantidad</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="venta in arrayTokens" :key="venta.nombre">
                  <td v-text="venta.nombre"></td>
                  <td v-text="formatPrice(venta.totalperiodo)" align="right"></td>
                </tr>
                <!---->
                <tr style="background-color:#CEECF5">
                  <td><strong>Total Tokens</strong></td>
                  <td align="right"><strong>{{totalTips = formatPrice(calculatarTotalTip)}}</strong></td>
                </tr>
                <tr style="background-color:#CEECF5">
                  <td><strong> Total USD</strong> </td>
                  <td align="right"><strong>{{totalUSD = formatPrice(calculatarTotalTip * 0.05)}}</strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{'mostrar' :modal}"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <!--            <input type="text" class="form-control datepicker" name="date">
                     <template>
                      <div>
                        <md-datepicker v-model="selectedDate">
                          <label>Select date1</label>
                        </md-datepicker>
                      </div>
            </template>-->

            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                <div class="col-md-3">
                  <input
                    type="date"
                    v-model="fecha"
                    class="form-control"
                    placeholder="Nombres y Apellidos"
                  />

                  <!--   <span class="help-block">(*) Ingrese la meta de ventas</span> -->
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Nombres</label>
                <div class="col-md-9">
                  <select class="form-control" v-model="idpersona">
                    <option value="0" disabled>Seleccionar persona</option>
                    <option
                      v-for="persona in arrayPersona"
                      :key="persona.id"
                      :value="persona.id"
                      v-text="persona.nombre"
                    ></option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Plataforma</label>
                <div class="col-md-9">
                  <select class="form-control" v-model="idplataforma">
                    <option value="0" disabled>Seleccionar plataforma</option>
                    <option
                      v-for="plataforma in arrayPlataforma"
                      :key="plataforma.id"
                      :value="plataforma.id"
                      v-text="plataforma.nombre_plataforma"
                    ></option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Cantidad</label>
                <div class="col-md-9">
                  <input type="number" v-model="cantidad" class="form-control" placeholder />
                </div>
              </div>
              <div v-show="errorVenta" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="tipoAccion==1"
              class="btn btn-primary"
              @click="registrarVenta()"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              class="btn btn-primary"
              @click="actualizarVenta()"
            >Actualizar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
export default {
  create: function() {},

  data() {
    return {
      nombre: "",
      nombre_completo: "",
      meta: 0,
      arrayVenta: [],
      arrayVentaAcumulada: [],
      sumaTokensPeriodo: [],
      modal: 0,
      tituloModal: "",
      totalUSD:0.0,
      totalTips:0,
      tipoAccion: 0,
      errorVenta: 0,
      err: 0,
      idroles: 0,
      errorMostrarMsjVenta: [],
      venta_id: 0,
      idpersona: 0,
      idplataforma: 0,
      nombre_persona: "",
      fecha: "",
      cantidad: 0,
      totalperiodo: 0,
      totalTokens: 0,
      totalAcum: 0,
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,

        to: 0
      },
      offset: 3,
      criterio: "nombre",
      buscar: "",
      arrayPersona: [],
      arrayPlataforma: [],
      arrayTokens: [],
      arrayPeriodos: [],
      arrayRegistrosVentas: [],
      periodoActual: "",
      sw: 0,
      selectedDate: null
    };
  },

  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },

  calculatarTotalTip:function(){
    var resultado =0;
    for(var i = 0;i<this.arrayVenta.length;i++){
      resultado = resultado+(this.arrayVenta[i].cantidad)
    }
    return resultado;
  },

    // Calcula elementos de l paginación
    pageNumber: function() {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },

    formato_numero: function(valor, decimales) {
      valor += ""; // si pasa el valor como texto
      valor = parseFloat(valor.replace(/[^0-9\.]/g, "")); // eliminamos cualquier cosa que no sea numero o punto

      decimales = decimales || 0; // si se pasa decimales vacio

      // si no es numero o es igual a cero, se retorna cero
      if (isNaN(valor) || valor == 0) return parseFloat(0).toFixed(decimales);

      // si es mayor o menor que cero retorno el valor formateado como numero
      valor = "" + valor.toFixed(decimales);

      var valor_parts = valor.split("."),
        regexp = /(\d+)(\d{3})/;

      while (regexp.test(valor_parts[0]))
        valor_parts[0] = valor_parts[0].replace(regexp, "$1" + "," + "$2");
      return valor_parts.join(".");
    },

    /*     AcumuladoTokens: function() {
      var totalAcum=0;
      var resultado = 0;
      for (var i = 0; i < this.sumaTokensPeriodo.length; i++) {
        resultado = resultado + this.sumaTokensPeriodo[i].cantidad;
      }
      return resultado;
    } */

    AcumuladoTokens: function() {
      var resultado = 0;
      console.log(this.arrayVenta.length);
      for (var i = 0; i < this.arrayVenta.length; i++) {
        resultado = resultado + this.arrayVenta[i].cantidad;
      }
      return resultado;
    }
  },

  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(0).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },

    listarVenta(page, buscar, criterio) {
      let me = this;
      var url =
        "/venta?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayVenta = respuesta.ventas.data;
          // me.periodoActual = respuesta.ventas[i].periodo.data;
          me.pagination = respuesta.pagination;
          me.encuentraPeriodoActual();
          this.AcumuladoTokens();
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    getRol() {
      let me = this;
      var urlrol = "/venta/habilitaVenta";
      axios.get(urlrol).then(function(response) {
        var respuesta = response.data;
        me.idroles = respuesta.idrol;

        console.log("rol actual", response.data);
      });
    },

    getVentas() {
      let me = this;
      var urlAcum = "/venta/acumuladoPeriodo";
      axios.get(urlAcum).then(function(response) {
        var respuesta = response.data;
        me.arrayTokens = respuesta.ventas.data;

        // console.log(response.data);
      });
    },

    getVentasPeriodoActual() {
      let me = this;
      var urlAcum = "/venta/tokensAcumPeriodo";
      axios.get(urlAcum).then(function(response) {
        var respuesta = response.data;
        me.sumaTokensPeriodo = respuesta.ventas;

        // console.log("nueva funcuiion", response.data);
      });
    },
    getPeriodos() {
      let me = this;
      var urlPer = "/venta/mostrarPeriodos";
      axios.get(urlPer).then(function(response) {
        var respuesta = response.data;
        me.arrayPeriodos = respuesta.periodos;

        // console.log(response.data);
      });
    },

    selectPersona() {
      let me = this;
      var url = "/persona/selectPersona";
      axios
        .get(url)
        .then(function(response) {
          //  console.log(response);
          var respuesta = response.data;
          me.arrayPersona = respuesta.personas;
          console.log("seleccion de preosnas", response.data);
          //  me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    selectPlataforma() {
      let me = this;
      var url = "/plataforma/selectPlataforma";
      axios
        .get(url)
        .then(function(response) {
          // console.log(response);
          var respuesta = response.data;
          me.arrayPlataforma = respuesta.plataformas;
          //  me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      me.pagination.current_page = page;
      me.listarVenta(page, buscar, criterio);
      me.getVentas();
    },

    toFixed($number, $decimals) {
      return number_format($number, $decimals, ".", "");
    },

    // TODO: VALIDACION DE REGISTROS EXISTENTES EN LA BASE DE DATOS
    ExisteRegistro() {
      let me = this;
      fecha: this.fecha;
      idpersona: this.idpersona;
      idplataforma: this.idplataforma;

      var url =
        "/venta?idpersona=" +
        me.idpersona +
        "&idplataforma=" +
        me.idplataforma +
        "&fecha=" +
        me.fecha;
      console.log(me.idpersona, me.idplataforma, me.fecha);
      axios
        .get(url)
        .then(function(response) {
          //  console.log(response);
          var respuesta = response.data;
          me.arrayRegistrosVentas = respuesta.ventas.data;
          //  me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    encuentra(idper, idpla, fech) {
      var sw = 0;
      // console.log("Datos de parametros: ", idper, idpla, fech);
      for (var i = 0; i < this.arrayVenta.length; i++) {
        //     console.log( "Datos de del arreglo: ", this.arrayVenta[i].idpersona,this.arrayVenta[i].idplataforma, this.arrayVenta[i].fecha);
        if (
          this.arrayVenta[i].idpersona == idper &&
          this.arrayVenta[i].idplataforma == idpla &&
          this.arrayVenta[i].fecha == fech
        ) {
          this.sw = true;
        }
      }
      //    console.log(this.sw);
    },

    //
    encuentraPeriodoActual() {
      let me = this;
      var periodoActual = "";
      for (var i = 0; i < me.arrayVenta.length; i++) {
        //     console.log( "Datos de del arreglo: ", this.arrayVenta[i].idpersona,this.arrayVenta[i].idplataforma, this.arrayVenta[i].fecha);

        me.periodoActual = me.arrayVenta[i].periodo;
        //  console.log(this.arrayVenta[i].periodo);
      }
      //    console.log(this.sw);
    },

    //

    registrarVenta() {
      let me = this;
      me.ExisteRegistro();
      //  for (var i = 0; i < this.arrayVenta.length; i++) {
      //     console.log("total arreglo",i);
      //  }
      if (this.validarVenta()) {
        return;
      }
      me.encuentra(this.idpersona, this.idplataforma, this.fecha);
      // this.sw = this.encuentra(this.idpersona,this.idplataforma,this.fecha);
      // console.log("resultado sw:", sw);
      if (me.sw) {
        Swal.fire({
          type: "error",
          title: "Error",
          text: "Registro para Modelo-Fecha-Plataforma, ya existe!"
        });

        me.sw = false;
      } else {
        let me = this;
        axios
          .post("/venta/registrar", {
            fecha: this.fecha,
            cantidad: this.cantidad,
            idpersona: this.idpersona,
            idplataforma: this.idplataforma
          })
          .then(function(response) {
            me.cerrarModal();
            me.listarVenta(1, "", "nombre");
            me.getVentas();
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    /* 
     let me = this;
     me.err=0;
       console.log("inicializa variable err :", me.err);
      if (this.validarVenta()) {
        console.log("primer if");
        return;
      }
      else{
           console.log("primer else");
           if(this.encuentra(this.idpersona,this.idplataforma,this.fecha))
              me.err=1;
               console.log("asignacion :", me.err);
              console.log("segundo if");
              swal.fire({
                type:'error',
                title:'Error',
                text:'Registro para Modelo-Fecha-Plataforma, ya existe!',
        });
        
         
         console.log("Nuevo valor variable err :", me.err);
          if(me.err==0) {

              let me = this;
              axios
                .post("/venta/registrar", {
                  fecha: this.fecha,
                  cantidad: this.cantidad,
                  idpersona: this.idpersona,
                  idplataforma: this.idplataforma
                })
                .then(function(response) {
                  me.cerrarModal();
                  me.listarVenta(1, "", "nombre");
                  me.getVentas();
                })
                .catch(function(error) {
                  console.log(error);
                });
        
      }

      } */

    actualizarVenta() {
      if (this.validarVenta()) {
        return;
      }
      let me = this;
      axios
        .put("/venta/actualizar", {
          fecha: this.fecha,
          cantidad: this.cantidad,
          idpersona: this.idpersona,
          idplataforma: this.idplataforma,
          id: this.venta_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarVenta(1, "", "nombre");
          me.getVentas();
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    desactivarVenta(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "Esta seguro de desactivar este registro?",
          //  text: "You won't be able to revert this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Aceptar!",
          cancelButtonText: "Cancelar!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;
            axios
              .put("/venta/desactivar", {
                id: id
              })
              .then(function(response) {
                me.listarVenta(1, "", "nombre");
                me.getVentas();
                swalWithBootstrapButtons.fire(
                  "Desactivado!",
                  "El registro ha sido desactivado.",
                  "success"
                );
              })
              .catch(function(error) {
                console.log(error);
              });
          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
          }
        });
    },

    activarVenta(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "Esta seguro de activar este registro?",
          //  text: "You won't be able to revert this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Aceptar!",
          cancelButtonText: "Cancelar!",
          reverseButtons: true
        })
        .then(result => {
          if (result.value) {
            let me = this;
            axios
              .put("/venta/activar", {
                id: id
              })
              .then(function(response) {
                me.listarVenta(1, "", "nombre");
                me.getVentas();
                swalWithBootstrapButtons.fire(
                  "Activado!",
                  "El registro ha sido activado.",
                  "success"
                );
              })
              .catch(function(error) {
                console.log(error);
              });
          } else if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.cancel
          ) {
          }
        });
    },

    validarVenta() {
      this.errorVenta = 0;
      this.errorMostrarMsjVenta = [];

      if (!this.fecha)
        this.errorMostrarMsjVenta.push("La Fecha no puede estar vacia.");
      if (this.idpersona == 0)
        this.errorMostrarMsjVenta.push("Selecciones una persona.");
      if (this.idplataforma == 0)
        this.errorMostrarMsjVenta.push("Selecciones una plataforma.");
      if (!this.cantidad)
        this.errorMostrarMsjVenta.push(
          "La cantidad debe ser número y no puede estar vacio."
        );

      //  validar repetidos
      /*        if(this.encuentra(this.idpersona,this.idplataforma,this.fecha))
                this.errorMostrarMsjVenta.push("Registro para Modelo-Fecha-Plataforma, ya existe!");
                  swal.fire(
                  "Error!",
                  "Registro para Modelo-Fecha-Plataforma, ya existe!",
                  "error"
                ); */
      //  this.errorVenta = 1;
      //

      if (this.errorMostrarMsjVenta.length) this.errorVenta = 1;
      //  console.log("Estado de error :", this.errorVenta);
      return this.errorVenta;
    },

    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      (this.idpersona = 0), (this.idplataforma = 0);
      this.nombre = "";
      this.fecha = "";
      this.cantidad = 0;
      this.errorVenta = 0;
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "venta": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              (this.idpersona = 0), (this.idplataforma = 0), (this.nombre = "");
              this.fecha = "";
              this.cantidad = 0;
              this.tituloModal = "Registrar Ventas";
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar Ventas";
              this.tipoAccion = 2;
              this.venta_id = data["id"];
              this.idpersona = data["idpersona"];
              this.idplataforma = data["idplataforma"];
              this.cantidad = data["cantidad"];
              this.fecha = data["fecha"];

              break;
              // console.log(data);
              //  this.tituloModal = "Actualizar persona";
            }
          }
        }
      }
      this.selectPersona();
      this.selectPlataforma();
    }
  },
  mounted() {
    this.listarVenta(1, this.buscar, this.criterio);
    this.getVentas();
    this.getPeriodos();
    this.ExisteRegistro();
    this.getVentasPeriodoActual();
    this.getRol();
    // this.getVentasAcum();
    // this.listarVentasAcumuladas();
    //  this.acumularVenta();
  }
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
</style>
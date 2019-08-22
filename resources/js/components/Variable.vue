<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Variable
<!--           <button
            type="button"
            @click="abrirModal('variable','registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button> -->
        </div>
        <div class="card-body">
          <!--  <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nombre_plataforma">Plataforma</option>
                  <option value="porcentaje">Porcentaje</option>
                </select>
                <input
                  type="text"
                  v-model="buscar" @keyup.enter="listarPlataforma(1,buscar,criterio)"
                  class="form-control"
                  placeholder="Texto a buscar"
                />
                <button type="submit" @click="listarPlataforma(1,buscar,criterio)" class="btn btn-primary">
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div> xx-->
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Opciones</th>
                <th>Periodo Actual</th>
                <th>Trm</th>
                <th>Tokens Premio</th>
                <th>Nota</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="variable in arrayVariable" :key="variable.id">
                <td>
                  <button
                    type="button"
                    @click="abrirModal('variable','actualizar',variable)"
                    class="btn btn-warning btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button>
                  &nbsp;
                  <!--                  <template v-if="plataforma.condicion">
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      @click="desactivasPlataforma(plataforma.id)"
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>

                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-info btn-sm"
                      @click="activarPlataforma(plataforma.id)"
                    >
                      <i class="icon-check"></i>
                    </button>
                  </template>-->
                </td>
                <td v-text="variable.periodo"></td>
                <td v-text="variable.trm"></td>
                <td v-text="variable.tokenspremio"></td>
                <td v-text="variable.observacion"></td>
         <!--       <td>
 
                  <div v-if="plataforma.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div> -->
             <!--      <div v-else>
                    <span class="badge badge-danger">Inactivo</span>
                  </div>
                </td> -->
              </tr>
              <!---->
            </tbody>
          </table>
          <nav>
        <!--     <ul class="pagination">
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
            </ul> -->
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{'mostrar' :modal}"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
             
          <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Rol (*)</label>
                <div class="col-md-9">
                    <select class="form-control" v-model="periodo">
                        <option value="0" disabled >Selccione un período</option>
                        <option v-for="periodo in arrayPeriodo" :key="periodo.id" :value="periodo.periodo" v-text="periodo.periodo">
                        
                        </option>
                    </select>
                </div>
            </div>
             
             
            <!--   <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Periodo</label>
                <div class="col-md-9">
                  <input type="text" v-model="periodo" class="form-control"  placeholder="Período de liquidación"/>
                </div>
              </div> -->
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Trm</label>
                <div class="col-md-9">
                  <input type="number" v-model="trm" class="form-control" placeholder="tasa de cambio"/>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Tokes Concurso</label>
                <div class="col-md-9">
                  <input type="number" v-model="tokenspremio" class="form-control" placeholder="Cantidad de Tokens"/>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Notas</label>
                <div class="col-md-9">
                  <input type="text" v-model="observacion" class="form-control" placeholder="Notas"/>
                </div>
              </div>

              <div v-show="errorVariable" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjVariable" :key="error" v-text="error"></div>
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
              @click="registrarVariable()"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              class="btn btn-primary"
              @click="actualizarVariable()"
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
  data() {
    return {
      periodo: "",
      trm:0,
      tokenspremio:0,
      observacion:"",
      arrayVariable: [],
      arrayPeriodo: [],
      modal: 0,
      idperiodo:"",
      periodo:"",
      perido_sel:"",
      tituloModal: "",
      tipoAccion: 0,
      errorVariable: 0,
      errorMostrarMsjVariable: [],
      variable_id: 0,
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "nombre_plataforma",
      buscar: "",
     
    };
  },

  computed: {
    isActived: function() {
      return this.pagination.current_page;
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
    }
  },

  methods: {
    listarVariable() {
      let me = this;
      var url ='/variable';
      //   var url='/plataformas/index';
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayVariable = respuesta.variables.data;
         // me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    getPeriodos() {
      let me = this;
      var urlPer = "/variable/selectPeriodo";
      axios.get(urlPer).then(function(response) {
        var respuesta = response.data;
        me.arrayPeriodo = respuesta.periodos;

       // console.log(response.data);
      });
    },



    cambiarPagina(page, buscar, criterio) {
      let me = this;
      me.pagination.current_page = page;
      me.listarPlataforma(page, buscar, criterio);
    },
    registrarVariable() {
      if (this.validarVariable()) {
        return;
      }
      let me = this;
      axios
        .post("/variable/registrar", {
          periodo: this.periodo,
          trm: this.trm,
          tokenspremio: this.tokenspremio,
          observacion: this.observacion
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarvariable();
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    actualizarVariable() {
      if (this.validarVariable()) {
        return;
      }
      let me = this;
      axios
        .put("/variable/actualizar", {
          periodo: this.periodo,
          trm: this.trm,
          tokenspremio: this.tokenspremio,
          observacion: this.observacion,
          id: this.variable_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarVariable();
        })
        .catch(function(error) {
          console.log(error);
        });
    },

 
    validarVariable() {
      this.errorVariable = 0;
      this.errorMostrarMsjVariable = [];
      if (!this.periodo)
        this.errorMostrarMsjVariable.push(
          "El período no puede estar vacio."
        );
      if (this.errorMostrarMsjVariable.length) this.errorVariable = 1;
      return this.errorVariable;
    },

    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.periodo ="";
      this.trm=0;
      this.tokenspremio=0;
      this.observacion=0;
      this.errorVariable = 0;
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "variable": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Variable";
              this.periodo ="";
              this.trm=0;
              this.tokenspremio=0;
              this.observacion=0;
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar variable";
              this.tipoAccion = 2;
              this.variable_id = data["id"];
              this.periodo = data["periodo"];
              this.trm = data["trm"];
              this.tokenspremio = data["tokenspremio"];
              this.observacion = data["observacion"];
              break;
              // console.log(data);
              //  this.tituloModal = "Actualizar variable";
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listarVariable();
    this.getPeriodos();
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
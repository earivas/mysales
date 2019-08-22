<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Plataforma
          <button
            type="button"
            @click="abrirModal('plataforma','registrar')"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
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
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Opciones</th>
                <th>Nombre Plataforma</th>
                <th>Porcentaje</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="plataforma in arrayPlataforma" :key="plataforma.id">
                <td>
                  <button
                    type="button"
                    @click="abrirModal('plataforma','actualizar',plataforma)"
                    class="btn btn-warning btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button>
                  &nbsp;
                  <template v-if="plataforma.condicion">
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
                  </template>
                </td>
                <td v-text="plataforma.nombre_plataforma"></td>
                <td v-text="plataforma.porcentaje"></td>
                <td>
                  <div v-if="plataforma.condicion">
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
                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page -1,buscar,criterio)">Ant</a>
              </li>
              <li class="page-item" v-for="page in pageNumber" :key ="page" :class="[page == isActived ? 'active' : '']">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
              </li>
              <li class="page-item"  v-if="pagination.current_page < pagination.last_page">
                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
              </li>
            </ul>
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
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Plataforma</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre_plataforma"
                    class="form-control"
                    placeholder="Nombre de plataforma"
                  />
                  <!--   <span class="help-block">(*) Ingrese la meta de ventas</span> -->
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Porcentaje</label>
                <div class="col-md-9">
                  <input
                    type="number"
                    v-model="porcentaje"
                    class="form-control"
                    placeholder="Valor del porcentaje"
                  />
                </div>
              </div>
              <div v-show="errorPlataforma" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjPlataforma" :key="error" v-text="error"></div>
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
              @click="registrarPlataforma()"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              class="btn btn-primary"
              @click="actualizarPlataforma()"
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
      nombre_plataforma: "",
      porcentaje: 0,
      arrayPlataforma: [],
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorPlataforma: 0,
      errorMostrarMsjPlataforma: [],
      plataforma_id: 0,
      pagination: {
          total: 0,
          current_page: 0,
          per_page: 0,
          last_page: 0,
          from: 0,
          to: 0
      },
        offset:3,
        criterio:'nombre_plataforma',
        buscar:''

    }
  },
  
  computed:{
      isActived:function(){
        return this.pagination.current_page;
      },

      // Calcula elementos de l paginación
      pageNumber:function(){
        if(!this.pagination.to){
          return [];
        }

        var from = this.pagination.current_page - this.offset;
        if(from <1 ){
          from = 1;
        }

        var to = from + (this.offset * 2 );
        if(to >= this.pagination.last_page){
          to = this.pagination.last_page;
        }

        var pagesArray =[];
        while(from <= to){
          pagesArray.push(from);
          from++;
        }
        return pagesArray;
      }
  },

  methods: {
    listarPlataforma(page, buscar, criterio) {
      let me = this;
      var url='/plataforma?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
   //   var url='/plataformas/index';
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayPlataforma = respuesta.plataformas.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page,buscar, criterio){
      let me = this;
      me.pagination.current_page = page;
      me.listarPlataforma(page,buscar,criterio);
    },
    registrarPlataforma() {
      if (this.validarPlataforma()) {
        return;
      }
      let me = this;
      axios
        .post("/plataforma/registrar", {
          nombre_plataforma: this.nombre_plataforma,
          porcentaje: this.porcentaje
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarPlataforma(1,'','nombre_plataforma');
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    actualizarPlataforma() {
      if (this.validarPlataforma()) {
        return;
      }
      let me = this;
      axios
        .put("/plataforma/actualizar", {
          nombre_plataforma: this.nombre_plataforma,
          porcentaje: this.porcentaje,
          id: this.plataforma_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarPlataforma(1,'','nombre_plataforma');
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    desactivasPlataforma(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "Esta seguro de desactivar esta plataforma?",
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
              .put("/plataforma/desactivar", {
                id: id
              })
              .then(function(response) {
                me.listarPlataforma(1,'','nombre_plataforma');
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

    activarPlataforma(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons
        .fire({
          title: "Esta seguro de activar esta plataforma?",
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
              .put("/plataforma/activar", {
                id: id
              })
              .then(function(response) {
                me.listarPlataforma(1,'','nombre_plataforma');
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

    validarPlataforma() {
      this.errorPlataforma = 0;
      this.errorMostrarMsjPlataforma = [];
      if (!this.nombre_plataforma)
        this.errorMostrarMsjPlataforma.push("El nombre de la plataforma no puede estar vacio.");
      if (this.errorMostrarMsjPlataforma.length) this.errorPlataforma = 1;
      return this.errorPlataforma;
    },

    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nombre_plataforma = "";
      this.porcentaje = "";
      this.errorPlataforma = 0;
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "plataforma": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Plataforma";
              this.nombre_plataforma = "";
              this.porcentaje = "";
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar Plataforma";
              this.tipoAccion = 2;
              this.plataforma_id = data["id"];
              this.nombre_plataforma = data["nombre_plataforma"];
              this.porcentaje = data["porcentaje"];
              break;
              // console.log(data);
              //  this.tituloModal = "Actualizar plataforma";
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listarPlataforma(1,this.buscar,this.criterio);
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
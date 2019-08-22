<template>
  <main class="main">
    <!-- Breadcrumb-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
    </ol>
    <div class="container-fluid">
      <div class="card">
        <div class="header"></div>
        <div class="card-body">
          <div class="div row">
            <div class="col-md-4">
              <div class="col-sm-12 col-lg-12 col-12">
                <div class="card">
                  <div class="card-body p-3 clearfix">
                    <i class="fa fa-bar-chart bg-primary p-3 font-2xl mr-3 float-left"></i>
                    <div class="h5 text-primary mb-0 mt-2">{{ ventaPeriodo = AcumuladoTokensDash }}</div>
                    <div
                      class="text-muted text-uppercase font-weight-bold font-xs"
                    >Ventas quincena actual</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="col-sm-12 col-lg-12 col-12">
                <div class="card">
                  <div class="card-body p-3 clearfix">
                    <i class="fa fa-handshake-o bg-primary p-3 font-2xl mr-3 float-left"></i>
                    <div class="h5 text-primary mb-0 mt-2">{{miMeta = valorMeta}}</div>
                    <div class="text-muted text-uppercase font-weight-bold font-xs">Meta</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="col-sm-12 col-lg-12 col-12">
                <div class="card">
                  <div class="card-body p-3 clearfix">
                    <i class="fa fa-line-chart bg-primary p-3 font-2xl mr-3 float-left"></i>
                    <div class="h5 text-primary mb-0 mt-2">{{cumplimiento = calcCumplimiento }}</div>
                    <div class="text-muted text-uppercase font-weight-bold font-xs">Por cumplir</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="header"></div>
        <div class="card-body">
          <div class="row">
            <!-- Graficas de modelos -->
            <div class="col-md-12">
              <div class="card card-chart">
                <div class="card-header">
                  <h4>Ventas modelos</h4>
                </div>
                <div class="card-content">
                  <div class="ct-chart">
                    <canvas id="ventasxModelo"></canvas>
                  </div>
                </div>
                <div class="card-footer">
                  <p>Ventas por modelo del periodo Actual</p>
                </div>
              </div>
            </div>
            <!-- Graficas de Paginas -->
            <div class="col-md-6">
              <div class="card card-chart">
                <div class="card-header">
                  <h4>Ventas por plataforma</h4>
                </div>
                <div class="card-content">
                  <div class="ct-chart">
                    <canvas id="ventasxplataforma"></canvas>
                  </div>
                </div>
                <div class="card-footer">
                  <p>Ventas históricas por página - {{ new Date().getFullYear() }}</p>
                </div>
              </div>
            </div>

            <!-- Graficas por dias -->
            <div class="col-md-6">
              <div class="card card-chart">
                <div class="card-header">
                  <h4>Ventas por día</h4>
                </div>
                <div class="card-content">
                  <div class="ct-chart">
                    <canvas id="ventasxmes"></canvas>
                  </div>
                </div>
                <div class="card-footer">
                  <p>Ventas históricas por día - {{ new Date().getFullYear() }}</p>
                </div>
              </div>
            </div>
            <!-- 
            <div class="col-md-6">
              <div class="card card-chart">
                <div class="card-header">
                  <h4>Ventas modelos</h4>
                </div>
                <div class="card-content">
                  <div class="ct-chart">
                    <table class="table table-bordered table-striped table-sm">
                      <thead>
                        <tr>
                          <th>Nombres</th>
                          <th>Cantidad</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="venta in ventasModelo" :key="venta.nombre">
                          <td v-text="venta.nombre"></td>
                          <td v-text="formatPrice(venta.ventatotal)" align="right"></td>
                        </tr>
                       
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                  <p>Ventas por modelo del periodo - {{ new Date().getFullYear() }}</p>
                </div>
              </div>
            </div>-->
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import { totalmem } from "os";
import { constants } from "crypto";
export default {
  data() {
    return {
      meta: 0,
      miMeta: 0,
      ventaPeriodo: 0,
      cumplimiento: 0,
      tokensDia: 0,
      totalModelosActivas: [],
      contadorModelosActivas: 0,
      vlrMetaTotal: 0,
      arrayVentasPeriodo: [],

      varVentaDia: null, // valor id del grafico (varIngreso)
      chartVentaDia: null, // crea el grafico (chartIngreso)
      ventasDia: [], // listado datos (Ingresos)
      varTotalVentasDia: [], // total de ingresos por mes (varTotalIngreso)
      varNombreDiaVenta: [], // nombre de los meses (varMesIngreso)

      varVentaModelo: null, // valor id del grafico (varIngreso)
      chartVentaModelo: null, // crea el grafico (chartIngreso)
      ventasModelo: [], // listado datos (Ingresos)
      varTotalVentasModelo: [], // total de ingresos por mes (varTotalIngreso)
      varNombreModeloVenta: [], // nombre de los meses (varMesIngreso)

      varVentaPlataforma: null, // valor id del grafico (varIngreso)
      chartVentaPlataforma: null, // crea el grafico (chartIngreso)
      ventasPlataforma: [], // listado datos (Ingresos)
      varTotalVentasPlataforma: [], // total de ingresos por mes (varTotalIngreso)
      varNombrePlataformaVenta: [] // nombre de los meses (varMesIngreso)
    };
  },
  computed: {
    calcCumplimiento: function() {
      let me = this;
      var resultadotks = 0;
      var resultado = 0;
      var vlrmeta = 16000;
      var resultado = 0;

      for (var i = 0; i < this.arrayVentasPeriodo.length; i++) {
        resultadotks = resultadotks + this.arrayVentasPeriodo[i].cantidad;
      }
      resultadotks = "" + parseInt(resultadotks);
      resultado = parseInt(me.vlrMetaTotal) - resultadotks;
      //  console.log("xxxxx", resultado);
      /*  console.log("tks:",resultadotks);
      var resultadoMeta = 0;
      for (var i = 0; i < me.arrayVentasPeriodo.length; i++) {
        //     console.log( "Datos de del arreglo: ", this.arrayVenta[i].idpersona,this.arrayVenta[i].idplataforma, this.arrayVenta[i].fecha);

        resultadoMeta = this.arrayVentasPeriodo[i].meta;
      }
      //
      resultadoMeta = "" + parseInt(resultadoMeta);
       */
      resultado = "" + resultado;

      //  console.log("meta:", resultadoMeta);

      var resultado_calc = resultado.split("."),
        regexp = /(\d+)(\d{3})/;

      while (regexp.test(resultado_calc[0]))
        resultado_calc[0] = resultado_calc[0].replace(
          regexp,
          "$1" + "," + "$2"
        );
      return resultado_calc.join(".");
    },

    AcumuladoTokensDash: function() {
      var resultado = 0;
      for (var i = 0; i < this.arrayVentasPeriodo.length; i++) {
        resultado = resultado + this.arrayVentasPeriodo[i].cantidad;
      }
      //
      resultado = "" + resultado.toFixed(0);

      var resultado_parts = resultado.split("."),
        regexp = /(\d+)(\d{3})/;

      while (regexp.test(resultado_parts[0]))
        resultado_parts[0] = resultado_parts[0].replace(
          regexp,
          "$1" + "," + "$2"
        );
      return resultado_parts.join(".");
      //
    },

    valorMeta: function() {
      //TODO:  Devolver la meta del usuario logueado, si es Admin, mostrar por defecto la meta de todos los usuarios
      let me = this;
      var resultado = 0;

      for (var i = 0; i < me.arrayVentasPeriodo.length; i++) {
        //     console.log( "Datos de del arreglo: ", this.arrayVenta[i].idpersona,this.arrayVenta[i].idplataforma, this.arrayVenta[i].fecha);

        resultado = this.arrayVentasPeriodo[i].meta * me.contadorModelosActivas;
      }
      //

      resultado = "" + parseInt(resultado);
      me.vlrMetaTotal = resultado;

      var meta_parts = resultado.split("."),
        regexp = /(\d+)(\d{3})/;

      while (regexp.test(meta_parts[0]))
        meta_parts[0] = meta_parts[0].replace(regexp, "$1" + "," + "$2");
      return meta_parts.join(".");

      //
    }
  },

  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(0).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    getModelosActivas() {
      let me = this;
      var urlmodelos = "/dashboard";
      axios.get(urlmodelos).then(function(response) {
        var respuesta = response.data;
        me.totalModelosActivas = respuesta.modelosActivas;
        me.contadorModelosActivas = me.totalModelosActivas.length;
        //   console.log("Total modelos ",respuesta.modelosActivas);
        //  console.log("ceunta modelos :",  me.totalModelosActivas.length);
      });
    },
  

    getVentasModelo() {
      let me = this;
      var urlModel = "/dashboard";
      axios
        .get(urlModel)
        .then(function(response) {
          var respuesta = response.data;
          me.ventasModelo = respuesta.ventasModelos;
          //   console.log("Ventas por modelos", respuesta.ventasModelos);
          me.loadVentasModelo();
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    loadVentasModelo() {
      let me = this;
      me.ventasModelo.map(function(x) {
        me.varNombreModeloVenta.push(x.nombre);
        me.varTotalVentasModelo.push(x.ventatotal);
      });

      me.varVentaModelo = document
        .getElementById("ventasxModelo")
        .getContext("2d");

      me.chartVentaModelo = new Chart(me.varVentaModelo, {
        type: "bar",
        data: {
          labels: me.varNombreModeloVenta,
          datasets: [
            {
              label: "Tokens",
              data: me.varTotalVentasModelo,
              backgroundColor: "rgba(86, 107, 204, 0.5)",
              borderColor: "rgba(5, 9, 132, 0.5)",
              borderWidth: 1
            }
          ]
        },
        options: {
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true
                }
              }
            ]
          }
        }
      });
    },

    getVentasPlataforma() {
      let me = this;
      var url = "/dashboard";
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.ventasPlataforma = respuesta.ventasPlataformas;
          //  console.log("plata", respuesta.ventasPlataformas);
          me.loadVentasPlataforma();
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    loadVentasPlataforma() {
      let me = this;
      me.ventasPlataforma.map(function(x) {
        me.varNombrePlataformaVenta.push(x.nombre_plataforma);
        me.varTotalVentasPlataforma.push(x.ventatotal);
      });

      me.varVentaPlataforma = document
        .getElementById("ventasxplataforma")
        .getContext("2d");

      me.chartVentaPlataforma = new Chart(me.varVentaPlataforma, {
        type: "bar",
        data: {
          labels: me.varNombrePlataformaVenta,
          datasets: [
            {
              label: "Tokens",
              data: me.varTotalVentasPlataforma,
              backgroundColor: "rgba(255, 99, 132, 0.5)",
              borderColor: "rgba(255, 99, 132, 0.5)",
              borderWidth: 1
            }
          ]
        },
        options: {
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true
                }
              }
            ]
          }
        }
      });
    },

    getVentasDia() {
      let me = this;
      var url = "/dashboard";
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.ventasDia = respuesta.ventasDia;
          //  console.log(respuesta.ventasDia);
          me.loadVentasDia();
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    loadVentasDia() {
      let me = this;
      let dianombre = "";
      me.ventasDia.map(function(x) {
        switch (parseInt(x.dia)) {
          case 0:
            dianombre = "Lunes";
            break;
          case 1:
            dianombre = "Martes";
            break;
          case 2:
            dianombre = "Miercoles";
            break;
          case 3:
            dianombre = "Jueves";
            break;
          case 4:
            dianombre = "Viernes";
            break;
          case 5:
            dianombre = "Sábado";
            break;
          case 6:
            dianombre = "Domingo";
            break;
          default:
            dianombre = "Error";
        }
        me.varNombreDiaVenta.push(dianombre);
        me.varTotalVentasDia.push(x.ventatotal);
      });

      me.varVentaDia = document.getElementById("ventasxmes").getContext("2d");

      me.chartVentaDia = new Chart(me.varVentaDia, {
        type: "bar",
        data: {
          labels: me.varNombreDiaVenta,
          datasets: [
            {
              label: "Tokens",
              data: me.varTotalVentasDia,
              backgroundColor: "rgba(54, 162, 235, 0.5)",
              borderColor: "rgba(54, 162, 235, 0.5)",
              borderWidth: 1
            }
          ]
        },
        options: {
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true
                }
              }
            ]
          }
        }
      });
    },
    getVentas() {
      let me = this;
      var url = "/dashboard";
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayVentasPeriodo = respuesta.ventas.data;

          //   me.pagination = respuesta.pagination;
          //   me.encuentraPeriodoActual();
        })
        .catch(function(error) {
          console.log(error);
        });
    }

    /*   encuentraMeta() {
      let me = this;
      var meta = 0;
      for (var i = 0; i < me.arrayVentasPeriodo.length; i++) {
        //     console.log( "Datos de del arreglo: ", this.arrayVenta[i].idpersona,this.arrayVenta[i].idplataforma, this.arrayVenta[i].fecha);

        me.meta = me.arrayVentasPeriodo[i].meta;
      }
      //
      meta = "" + meta.toFixed(0);

      var meta_parts = meta.split("."),
        regexp = /(\d+)(\d{3})/;

      while (regexp.test(meta_parts[0]))
        meta_parts[0] = meta_parts[0].replace(regexp, "$1" + "," + "$2");
      return meta_parts.join(".");

      //
    } */
  },
  mounted() {
    this.getVentas();
    this.getVentasDia();
    this.getVentasPlataforma();
    this.getModelosActivas();
    this.getVentasModelo();
   
    //  this.calcCumplimiento();
  }
};
</script>
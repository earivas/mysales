<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Venta;
use App\Plataforma;
use App\Periodo;
use App\Variable;
use App\User;
use Carbon\Carbon;


class VentaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
      
        $idrol = \Auth::user()->idrol;
        $buscar=$request->buscar;
        $criterio=$request->criterio;
        
        if($criterio == 'nombre_plataforma'){
            $tablaBusqueda = 'plataformas';
        }else{
            $tablaBusqueda = 'personas';
        }
        
        if($buscar == ''){
            $ventas = Venta::join('personas','ventas.idpersona','=','personas.id')
            ->join('plataformas','ventas.idplataforma','=','plataformas.id')
            ->join('periodos','ventas.fecha','=','periodos.fecha')
            ->join('variables','periodos.periodo','=','variables.periodo')
            ->select('ventas.id','ventas.idpersona','ventas.fecha','ventas.idplataforma','plataformas.nombre_plataforma',
            'ventas.cantidad','personas.nombre as nombre_completo','ventas.condicion','periodos.periodo')
            ->orderBy('ventas.created_at','desc')
            ->paginate(2000);
        }
        else{
            if($criterio == 'nombre_plataforma'){
                $ventas = Venta::join('personas','ventas.idpersona','=','personas.id')
                ->join('plataformas','ventas.idplataforma','=','plataformas.id')
                ->join('periodos','ventas.fecha','=','periodos.fecha')
                ->join('variables','periodos.periodo','=','variables.periodo')
                ->select('ventas.id','ventas.idpersona','ventas.fecha','ventas.idplataforma','plataformas.nombre_plataforma',
                'ventas.cantidad','personas.nombre as nombre_completo','ventas.condicion', 'periodos.periodo')
                ->where('plataformas.'.$criterio, 'like', '%' . $buscar . '%')
                ->orderBy('ventas.created_at','desc')
                ->paginate(2000);
            }
            else{
                $ventas = Venta::join('personas','ventas.idpersona','=','personas.id')
                ->join('plataformas','ventas.idplataforma','=','plataformas.id')
                ->join('periodos','ventas.fecha','=','periodos.fecha')
                ->join('variables','periodos.periodo','=','variables.periodo')
                ->select('ventas.id','ventas.idpersona','ventas.fecha','ventas.idplataforma','plataformas.nombre_plataforma',
                'ventas.cantidad','personas.nombre as nombre_completo','ventas.condicion', 'periodos.periodo')
                ->where('personas.'.$criterio, 'like', '%' . $buscar . '%')
                ->orderBy('ventas.created_at','desc')
                ->paginate(2000);

            }
        }

       return [
            'pagination'=>[
                    'total'         =>  $ventas->total(),
                    'current_page'  =>  $ventas->currentPage(),
                    'per_page'      =>  $ventas->perPage(),
                    'last_page'     =>  $ventas->lastPage(),
                    'from'          =>  $ventas->firstItem(),
                    'to'            =>  $ventas->lastItem(),
        ],

        
        'ventas' => $ventas
        
    ];
    }

  public function habilitaVenta(Request $request){
    $idrol = \Auth::user()->idrol;
      return[ 'idrol' => $idrol];
  }

    public function acumuladoPeriodo(Request $request){
       // if(!$request->ajax()) return redirect('/'); 
        
     //   $myFecha = $request->fecha;
     //   $dFecha = $request->fecha->format('d');
     //   $mFecha = $request->fecha->format('m');
     //   $yFecha = $request->fecha->format('y');


         $ventaAcumulada = Venta::join('personas','ventas.idpersona','=','personas.id')
        ->join('periodos','ventas.fecha','=','periodos.fecha')
        ->join('variables','periodos.periodo','=','variables.periodo')
        ->selectRaw('personas.nombre , sum(ventas.cantidad) as totalperiodo')
        ->groupBy('personas.nombre')
     //   ->orderBy('personas.nombre','desc')
          ->orderBy('totalperiodo','desc')
        ->paginate(100);

         return ['ventas'=>$ventaAcumulada];

        }
        public function tokensAcumPeriodo(Request $request){
             if(!$request->ajax()) return redirect('/'); 
           
              $ventaAcumuladaPeriodo = Venta::join('personas','ventas.idpersona','=','personas.id')
             ->join('periodos','ventas.fecha','=','periodos.fecha')
             ->join('variables','periodos.periodo','=','variables.periodo')
             ->selectRaw('variables.periodo , sum(ventas.cantidad) as cantidad')
             ->groupBy('variables.periodo')
             ->get();
     
              return ['ventas'=>$ventaAcumuladaPeriodo];
     
             }
     

        public function RegistroExistente(Request $request){

            $idpersona=$request->idpersona;
            $idplataforma=$request->idplataforma;
            $fecha=$request->fecha;

            $existeRegistroVentas = Ventas::where('venta.idpersona','=', $idpersona)
            ->where('venta.idplataforma','=', $idplataforma)
            ->where('venta.fecha','=', $fecha)
            ->get()
            ->paginate(1000);

            return ['ventas'=>$existeRegistroVentas];
        //    if(count($existeRegistroVentas)>0){
        //        return 1;
        //    }else{
        //        return 0;
        //    }

        }
        public function mostrarPeriodos(){

         //   $fechaAnterior = Carbon::now()->subMonths();
         //   $fechaPosterior = Carbon::now()-addMonths();
            
            $fromYear = date('Y');
            $toInitialMonth = date('m');
            $toEndMonth = date('m');

            $mesAnterior = $toInitialMonth -1;
            $mesSiguiente = $toEndMonth + 1;
  /*
            $periodos = DB::table('periodos') 
            ->select(DB::raw('periodo'),
             DB::raw('YEAR(fecha) as anio'), 
             DB::raw('SUM(id) as per'))
            ->whereYear('fecha',$fromYear)
            ->groupBy(DB::raw('periodo'))
            ->get()
            ->paginate(100);
           ; */
         
          

      /*       $ventaAcumulada = Venta::join('personas','ventas.idpersona','=','personas.id')
            ->join('periodos','ventas.fecha','=','periodos.fecha')
            ->selectRaw('personas.nombre , sum(ventas.cantidad) as totalperiodo')
            ->groupBy('personas.nombre')
            ->orderBy('personas.nombre','desc')
            ->paginate(100);
*/

            $periodos = Periodo::selectRaw('periodos.periodo,SUM(periodos.id) as per')
            //->whereYear('periodos.fecha',$fromYear)
            ->whereBetween('periodos.fecha',array(Carbon::now()->subWeek(3), Carbon::now()->addWeek(3)))
            ->groupBy('periodos.periodo')
            ->get(); 

            return ['periodos'=>$periodos];
        }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $venta = new Venta();
        $venta -> idpersona = $request->idpersona;
        $venta -> idplataforma = $request->idplataforma;
        $venta -> fecha = $request->fecha;
        $venta -> cantidad = $request->cantidad;
        $venta -> condicion = '1';
        $venta->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta ->idpersona = $request->idpersona;
        $venta ->idplataforma = $request->idplataforma;
        $venta ->fecha = $request->fecha;
        $venta ->cantidad = $request->cantidad;
        $venta ->condicion = '1';
        $venta->save();
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta ->condicion = '0';
        $venta->save();
    }
    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta -> condicion = '1';
        $venta->save();
    }
  
}

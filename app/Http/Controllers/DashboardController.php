<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Venta;
use App\Plataforma;
use App\Periodo;
use App\Variable;
use App\Persona;
use App\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function __invoke(Request $request){
     //  return view('user.profile',['user'=> User::findOrFail($id)]);
     $anio = date('Y');
     $user = \Auth::user()->usuario;
     $idrol = \Auth::user()->idrol;
    // 1 : Administrador
    // 2 : Supervisor 
    // 3 : Modelo
    
    if($idrol == 3){

        $ventas = Venta::join('personas','ventas.idpersona','=','personas.id')
        ->join('plataformas','ventas.idplataforma','=','plataformas.id')
        ->join('periodos','ventas.fecha','=','periodos.fecha')
        ->join('variables','periodos.periodo','=','variables.periodo')
        ->join('users','personas.id','=','users.id')
        ->select('ventas.id','ventas.idpersona','ventas.fecha','ventas.idplataforma',
                 'plataformas.nombre_plataforma','ventas.cantidad','personas.nombre as nombre_completo',
                 'ventas.condicion','periodos.periodo','personas.meta','users.id','users.usuario')
        ->where('users.usuario','=',$user)        
         ->paginate(2000);


         
            $ventasDia= Venta::join('personas','ventas.idpersona','=','personas.id')
            ->join('plataformas','ventas.idplataforma','=','plataformas.id')
            ->join('periodos','ventas.fecha','=','periodos.fecha')
        //    ->join('variables','periodos.periodo','=','variables.periodo')
            ->join('users','personas.id','=','users.id')
            ->select(DB::raw('WEEKDAY(ventas.fecha) as dia'),
                    DB::raw('YEAR(ventas.fecha) as anio'),
                    DB::raw('SUM(ventas.cantidad) as ventatotal'))
            ->whereYear('ventas.fecha',$anio)
            ->where('users.usuario','=',$user)        
            ->groupBy(DB::raw('WEEKDAY(ventas.fecha)'), DB::raw('YEAR(ventas.fecha)'))
            ->get();

            $ventasPlataformas= Venta::join('personas','ventas.idpersona','=','personas.id')
            ->join('plataformas','ventas.idplataforma','=','plataformas.id')
            ->join('periodos','ventas.fecha','=','periodos.fecha')
         //   ->join('variables','periodos.periodo','=','variables.periodo')
            ->join('users','personas.id','=','users.id')
            ->select(DB::raw('plataformas.nombre_plataforma'),
                    DB::raw('YEAR(ventas.fecha) as anio'),
                    DB::raw('SUM(ventas.cantidad) as ventatotal'))
            ->whereYear('ventas.fecha',$anio)
            ->where('users.usuario','=',$user)        
            ->groupBy(DB::raw('plataformas.nombre_plataforma'), DB::raw('YEAR(ventas.fecha)'))
            ->get();

/* 
            SELECT personas.nombre, SUM(ventas.cantidad) Cantidad FROM ventas 
            *INNER JOIN periodos ON periodos.fecha = ventas.fecha
            *INNER JOIN personas on personas.id = ventas.idpersona
            INNER JOIN variables ON variables.periodo = periodos.periodo
            INNER JOIN users ON users.id = personas.id
            WHERE users.condicion = '1'
            GROUP by  personas.nombre */
            
       /*  */


           $ventasModelos= Venta::join('personas','ventas.idpersona','=','personas.id')
             ->join('periodos','ventas.fecha','=','periodos.fecha')
             ->join('variables','periodos.periodo','=','variables.periodo') //*** */
             ->join('users','personas.id','=','users.id')
           ->select(DB::raw('personas.nombre'),
                    DB::raw('YEAR(ventas.fecha) as anio'),
                     DB::raw('SUM(ventas.cantidad) as ventatotal'))
          //  ->whereYear('ventas.fecha',$anio)
          // ->where('users.condicion','=','1')     
           ->where('users.usuario','=',$user)     
           ->groupBy(DB::raw('personas.nombre'), DB::raw('YEAR(ventas.fecha)'))
            ->get();


            $modelosActivas = Persona::join('users','users.id','=','personas.id')
            ->select('personas.id','personas.nombre')
         //   ->where('personas.condicion','=','1')
         //   ->where('users.idrol','=','3')
            ->where('users.usuario','=',$user)  
            ->get();


              return ['ventas' => $ventas, 'ventasDia'=> $ventasDia,
                      'ventasPlataformas'=> $ventasPlataformas, 
                      'modelosActivas'=>$modelosActivas,
                      'ventasModelos'=>$ventasModelos,'anio'=>$anio];
   
    }
    else{
        $ventas = Venta::join('personas','ventas.idpersona','=','personas.id')
        ->join('plataformas','ventas.idplataforma','=','plataformas.id')
        ->join('periodos','ventas.fecha','=','periodos.fecha')
        ->join('variables','periodos.periodo','=','variables.periodo')
        ->join('users','personas.id','=','users.id')
        ->select('ventas.id','ventas.idpersona','ventas.fecha','ventas.idplataforma',
                 'plataformas.nombre_plataforma','ventas.cantidad','personas.nombre as nombre_completo',
                 'ventas.condicion','periodos.periodo','personas.meta','users.id','users.usuario')
     //   ->orderBy('ventas.created_at','desc')
        ->paginate(2000);


        $ventasDia= Venta::join('personas','ventas.idpersona','=','personas.id')
        ->join('plataformas','ventas.idplataforma','=','plataformas.id')
        ->join('periodos','ventas.fecha','=','periodos.fecha')
     //   ->join('variables','periodos.periodo','=','variables.periodo')
        ->join('users','personas.id','=','users.id')
        ->select(DB::raw('WEEKDAY(ventas.fecha) as dia'),
                DB::raw('YEAR(ventas.fecha) as anio'),
                DB::raw('SUM(ventas.cantidad) as ventatotal'))
        ->whereYear('ventas.fecha',$anio)
      //  ->where('users.usuario','=',$user)        
        ->groupBy(DB::raw('WEEKDAY(ventas.fecha)'), DB::raw('YEAR(ventas.fecha)'))
       // ->orderBy(DB::raw('WEEKDAY(ventas.fecha)','ASC'))
        ->get();


        $ventasPlataformas= Venta::join('personas','ventas.idpersona','=','personas.id')
        ->join('plataformas','ventas.idplataforma','=','plataformas.id')
        ->join('periodos','ventas.fecha','=','periodos.fecha')
     //   ->join('variables','periodos.periodo','=','variables.periodo')
        ->join('users','personas.id','=','users.id')
        ->select(DB::raw('plataformas.nombre_plataforma'),
                DB::raw('YEAR(ventas.fecha) as anio'),
                DB::raw('SUM(ventas.cantidad) as ventatotal'))
        ->whereYear('ventas.fecha',$anio)
      //  ->where('users.usuario','=',$user)        
        ->groupBy(DB::raw('plataformas.nombre_plataforma'), DB::raw('YEAR(ventas.fecha)'))
        ->get();

         $modelosActivas = Persona::join('users','users.id','=','personas.id')
        ->select('personas.id','personas.nombre')
        ->where('personas.condicion','=','1')
        ->where('users.idrol','=','3')
        ->get();

        $ventasModelos= Venta::join('personas','ventas.idpersona','=','personas.id')
        ->join('periodos','ventas.fecha','=','periodos.fecha')
        ->join('variables','periodos.periodo','=','variables.periodo') //*** */
        ->join('users','personas.id','=','users.id')
        ->select(DB::raw('personas.nombre'),
           DB::raw('YEAR(ventas.fecha) as anio'),
                DB::raw('SUM(ventas.cantidad) as ventatotal'))
     //  ->whereYear('ventas.fecha',$anio)
      ->where('users.condicion','=','1')     
      ->groupBy(DB::raw('personas.nombre'), DB::raw('YEAR(ventas.fecha)'))
       ->get();

        return ['ventas' => $ventas, 'ventasDia'=> $ventasDia, 
                'ventasPlataformas'=> $ventasPlataformas,
                'modelosActivas'=>$modelosActivas,
                'ventasModelos'=>$ventasModelos,'anio'=>$anio];
      //  return ['ventas' => $ventas];
      }

}

}
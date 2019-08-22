<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Variable;
use App\Periodo;
use Carbon\Carbon;

class VariableController extends Controller
{
    public function index(Request $request)
    {
       if(!$request->ajax()) return redirect('/');
  
      
            $variables = Variable::orderBy('id','desc')->paginate(7);
             return [
                    'variables' => $variables
                    ];
    }

    public function selectPeriodo(){

           $fromYear = date('Y');
           $toInitialMonth = date('m');
           $toEndMonth = date('m');

           $mesAnterior = $toInitialMonth -1;
           $mesSiguiente = $toEndMonth + 1;

           $periodos = Periodo::selectRaw('periodos.periodo,SUM(periodos.id) as per')
            ->whereBetween('periodos.fecha',array(Carbon::now()->subWeek(3), Carbon::now()->addWeek(3)))
           ->groupBy('periodos.periodo')
           ->get(); 

           return ['periodos'=>$periodos];
       }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
            $variables = Variable::findOrFail($request->id);
            $variables -> periodo = $request->periodo;
            $variables -> trm = $request->trm;
            $variables -> tokenspremio = $request->tokenspremio;
            $variables -> observacion = $request->observacion;
            $variables->save();
    }

}

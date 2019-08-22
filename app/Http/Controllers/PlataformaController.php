<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plataforma;


class PlataformaController extends Controller
{
    public function index(Request $request)
    {
       if(!$request->ajax()) return redirect('/');
        
        $buscar=$request->buscar;
        $criterio=$request->criterio;

        if($buscar == ''){

            $plataformas = Plataforma::orderBy('id','desc')->paginate(7);
        }
        else{
            $plataformas = Plataforma::where($criterio, 'like', '%'. $buscar . '%')
                               ->orderBy('id','desc')
                               ->paginate(7);
        }
        return [
            'pagination'=>[
                    'total'         =>  $plataformas->total(),
                    'current_page'  =>  $plataformas->currentPage(),
                    'per_page'      =>  $plataformas->perPage(),
                    'last_page'     =>  $plataformas->lastPage(),
                    'from'          =>  $plataformas->firstItem(),
                    'to'            =>  $plataformas->lastItem(),
        ],
        'plataformas' => $plataformas
    ];
    }
    public function selectPlataforma(Request $request){
      
        if(!$request->ajax()) return redirect('/');  
        $plataformas = Plataforma::where('condicion', '=','1')
            ->select('id','nombre_plataforma')
            ->orderBy('nombre_plataforma','asc')
            ->get();

            return['plataformas'=> $plataformas];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $plataformas = new Plataforma();
        $plataformas -> nombre_plataforma = $request->nombre_plataforma;
        $plataformas -> porcentaje = $request->porcentaje;
        $plataformas -> condicion = '1';
        $plataformas->save();
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
        $plataformas = Plataforma::findOrFail($request->id);
        $plataformas -> nombre_plataforma = $request->nombre_plataforma;
        $plataformas -> porcentaje = $request->porcentaje;
        $plataformas -> condicion = '1';
        $plataformas->save();
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $plataformas = Plataforma::findOrFail($request->id);
        $plataformas -> condicion = '0';
        $plataformas->save();
    }
    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $plataformas = Plataforma::findOrFail($request->id);
        $plataformas -> condicion = '1';
        $plataformas->save();
    }
  
}

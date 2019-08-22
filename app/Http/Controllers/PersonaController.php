<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;


class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $buscar=$request->buscar;
        $criterio=$request->criterio;

        if($buscar == ''){
            $personas = Persona::orderBy('id','desc')->paginate(7);
        }
        else{
            $personas = Persona::where($criterio, 'like', '%'. $buscar . '%')
                               ->orderBy('id','desc')
                               ->paginate(15);
        }
        return [
            'pagination'=>[
                    'total'         =>  $personas->total(),
                    'current_page'  =>  $personas->currentPage(),
                    'per_page'      =>  $personas->perPage(),
                    'last_page'     =>  $personas->lastPage(),
                    'from'          =>  $personas->firstItem(),
                    'to'            =>  $personas->lastItem(),
        ],
        'personas' => $personas
    ];
    }
    public function selectPersona(Request $request){
      
        if(!$request->ajax()) return redirect('/');  
    //    $personas = Persona::where('condicion', '=','1')  
    //        ->select('id','nombre')
    //        ->orderBy('nombre','asc')
    //        ->get();

    //        return['personas'=> $personas];



  $personas = Persona::join('users','users.id','=','personas.id')
            ->select('personas.id','personas.nombre')
            ->where('personas.condicion','=','1')
            ->where('users.idrol','=','3')
          //  ->where()
            ->orderBy('personas.nombre','asc')
            ->get();

            return['personas'=> $personas];
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
        $personas = new Persona();
        $personas -> nombre = $request->nombre;
        $personas -> meta = $request->meta;
        $personas -> condicion = '1';
        $personas->save();
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
        $personas = Persona::findOrFail($request->id);
        $personas -> nombre = $request->nombre;
        $personas -> meta = $request->meta;
        $personas -> condicion = '1';
        $personas->save();
    }

    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $personas = Persona::findOrFail($request->id);
        $personas -> condicion = '0';
        $personas->save();
    }
    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $personas = Persona::findOrFail($request->id);
        $personas -> condicion = '1';
        $personas->save();
    }
  
}

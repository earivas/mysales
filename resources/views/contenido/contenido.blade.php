@extends('principal')
@section('contenido')

@if(Auth::check())
            @if (Auth::user()->idrol == 1)
            <template v-if="menu==0">
                    <dashboard></dashboard>
                    </template>
                    <template v-if="menu==1">
                      <plataforma></plataforma>
                    </template>
                    <template v-if="menu==2">
                        <persona></persona>
                    </template>
                    <template v-if="menu==3">
                        <h1>Contenido del Menu3</h1>
                    </template>
                    <template v-if="menu==4">
                    <h1>Contenido del Menu4</h1>
                    </template>
                    <template v-if="menu==5">
                       <variable></variable>
                    </template>
                    <template v-if="menu==6">
                        <venta></venta>
                    </template>
                    <template v-if="menu==7">
                        <User></User>
                    </template>
                    <template v-if="menu==8">
                        <rol></rol>
                    </template>
                    <template v-if="menu==9">
                        <reporte></reporte>
                    </template>
                    <template v-if="menu==10">
                        <h1>Menu10</h1>
                    </template>
                    <template v-if="menu==11">
                        <h1>Ayuda</h1>
                    </template>
                    <template v-if="menu==12">
                        <h1>Acerca de ...</h1>
                    </template>
            @elseif (Auth::user()->idrol == 2)
            <template v-if="menu==0">
                    <dashboard></dashboard>
                    </template>
                    <template v-if="menu==6">
                        <venta></venta>
                    </template>
                    <template v-if="menu==11">
                        <h1>Ayuda</h1>
                    </template>
                    <template v-if="menu==12">
                        <h1>Acerca de ...</h1>
                    </template>
            @elseif (Auth::user()->idrol == 3)
            <template v-if="menu==0">
                    <dashboard></dashboard>
                    </template>
                    <template v-if="menu==11">
                        <h1>Ayuda</h1>
                    </template>
                    <template v-if="menu==12">
                        <h1>Acerca de ...</h1>
                    </template>
            @else
 
            @endif
 
        @endif

@endsection
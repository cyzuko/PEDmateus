@extends('adminlte::page')

@section('title', 'Sistema de Gerenciamento de Faturas')

@section('content_header')
    @yield('content_header')
@stop

@section('content')
    @yield('content')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('AdminLTE aplicado!');
    </script>
@stop
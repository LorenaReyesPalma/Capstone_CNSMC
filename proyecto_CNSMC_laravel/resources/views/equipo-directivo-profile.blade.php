<!-- resources/views/equipo-directivo-profile.blade.php -->
@extends('layouts.app')

@section('title', 'Equipo Directivo')

@section('content')
<div class="container mt-4">
    <h1>Perfil del Equipo Directivo</h1>
    <p>Bienvenido a la sección del perfil del equipo directivo. Desde aquí puedes gestionar las derivaciones, citaciones y consultar las estadísticas.</p>

    <div class="row">
        <!-- Tarjeta de Derivaciones -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Derivaciones</h5>
                    <p class="card-text">Visualiza y gestiona las derivaciones de los estudiantes.</p>
                    <a href="#" class="btn btn-primary">Ir a Derivaciones</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Citaciones Apoderado -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Citaciones Apoderado</h5>
                    <p class="card-text">Consulta las citaciones con los apoderados de los estudiantes.</p>
                    <a href="#" class="btn btn-primary">Ver Citaciones</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Estadísticas -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Estadísticas</h5>
                    <p class="card-text">Consulta las estadísticas de gestión y rendimiento.</p>
                    <a href="#" class="btn btn-primary">Ver Estadísticas</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Aquí puedes agregar más secciones -->
    </div>
</div>
@endsection

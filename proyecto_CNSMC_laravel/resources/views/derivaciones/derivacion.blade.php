@extends('layouts.app')

@section('content')
@if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
<div class="container">
    <h1 class="mb-4 text-center " style="color: #002A45;">FICHA DERIVACIÓN CONVIVENCIA ESCOLAR 2024</h1>

    <form action="{{ route('derivacion.store') }}" method="POST">
        @csrf
        <input type="hidden" name="run" value="{{ $run }}">
        <input type="hidden" name="digito_ver" value="{{ $dv }}">

        <!-- Accordion -->
        <div class="accordion" id="derivacionAccordion">

            <!-- Sección 1: Individualización de estudiante -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        1. Individualización de estudiante
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                    data-bs-parent="#derivacionAccordion">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre_estudiante">Nombre completo estudiante:</label>
                                    <input type="text" name="nombre_estudiante" id="nombre_estudiante"
                                        class="form-control" value="{{ $nombre_completo }}" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edad">Edad:</label>
                                    <input type="number" name="edad" id="edad" class="form-control"
                                        value="{{ $edad ?? '' }}" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="curso">Curso:</label>
                                    <input type="text" name="curso" id="curso" class="form-control"
                                        value="{{ $alumno->desc_grado }} {{ $alumno->letra_curso }}" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_derivacion">Fecha de Derivación:</label>
                                    <input type="date" name="fecha_derivacion" id="fecha_derivacion"
                                        class="form-control" value="{{ $fechaActual }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="adulto_responsable">Adulto Responsable:</label>
                                    <input type="text" name="adulto_responsable" id="adulto_responsable"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Teléfono:</label>
                                    <input type="text" name="telefono" id="telefono"  value="{{ $telefono }}"class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Programa de Integración Escolar</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="programa_integracion"
                                            id="programa_integracion"
                                            {{ $alumno->programa_integracion ? 'checked' : '' }}>
                                        <label class="form-check-label" for="programa_integracion">Sí</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Programa de Pro Retención</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="programa_retencion"
                                            id="programa_retencion" {{ $alumno->programa_retencion ? 'checked' : '' }}>
                                        <label class="form-check-label" for="programa_retencion">Sí</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Sección 2: Indicadores sugeridos para iniciar intervención -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        2. Indicadores sugeridos para iniciar intervención
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                    data-bs-parent="#derivacionAccordion">
                    <div class="accordion-body">
                        <p>Seleccione aquel indicador que considere relevante para la intervención de convivencia
                            escolar:</p>

                        <h5>Ámbito personal:</h5>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="indicador_personal[]"
                                value="Repitencia o pre-deserción escolar">
                            <label class="col-lg-4" class="form-check-label">Repitencia o pre-deserción escolar</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="indicador_personal[]"
                                value="Bajo rendimiento académico">
                            <label class="col-lg-4" class="form-check-label">Bajo rendimiento académico</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="indicador_personal[]"
                                value="Presentación personal e higiene">
                            <label class="col-lg-4" class="form-check-label">Presentación personal e higiene</label>
                        </div>

                        <div class="form-check d-flex align-items-center mb-3">
                            <input type="checkbox" class="form-check-input me-2" name="indicador_personal[]"
                                value="Diagnóstico previo" id="diagnostico_previo_checkbox">
                            <label class="col-lg-4" class="form-check-label me-2"
                                for="diagnostico_previo_checkbox">Diagnóstico previo</label>
                            <input type="text" name="diagnostico_previo" class="form-control flex-grow-1"
                                placeholder="Especificar diagnóstico previo">
                        </div>

                        <div class="form-check d-flex align-items-center mb-3">
                            <input type="checkbox" class="form-check-input me-2" name="indicador_personal[]"
                                value="Tratamiento farmacológico" id="tratamiento_farmacologico_checkbox">
                            <label class="col-lg-4" class="form-check-label me-2"
                                for="tratamiento_farmacologico_checkbox">Tratamiento farmacológico</label>
                            <input type="text" name="tratamiento_farmacologico" class="form-control flex-grow-1"
                                placeholder="Especificar tratamiento">
                        </div>


                        <h5>Ámbito familiar:</h5>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="indicador_familiar[]"
                                value="Adultos con baja escolaridad">
                            <label class="form-check-label">Adultos con baja escolaridad</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="indicador_familiar[]"
                                value="Precariedad del empleo">
                            <label class="form-check-label">Precariedad del empleo</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="indicador_familiar[]"
                                value="Problemas en el establecimiento de límites y normas">
                            <label class="form-check-label">Problemas en el establecimiento de límites y normas por
                                parte del adulto responsable</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="indicador_familiar[]"
                                value="Abandono afectivo">
                            <label class="form-check-label">Abandono afectivo</label>
                        </div>

                        <h5>Ámbito Socio-comunitario:</h5>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="indicador_socio_comunitario[]"
                                value="Sectores con conductas infractoras">
                            <label class="form-check-label">Sectores con conductas infractoras: consumo y tráfico de
                                drogas</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="indicador_socio_comunitario[]"
                                value="Pertenencia a grupos de riesgo">
                            <label class="form-check-label">Pertenencia a grupos con conductas de riesgo</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 3: Descripción - Motivo derivación -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        3. Descripción - Motivo derivación
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                    data-bs-parent="#derivacionAccordion">
                    <div class="accordion-body">
                        <textarea name="motivo_derivacion" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
            </div>

            <!-- Sección 4: Acciones realizadas anteriormente -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        4. Acciones realizadas anteriormente
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                    data-bs-parent="#derivacionAccordion">
                    <div class="accordion-body">
                        <textarea name="acciones_realizadas" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
            </div>

            <!-- Sugerencias -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        5. Sugerencias para la intervención
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                    data-bs-parent="#derivacionAccordion">
                    <div class="accordion-body">
                        <textarea name="sugerencias" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
            </div>

            <!-- Sección 6: Autorización -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        6. Autorización
                    </button>
                </h2>
                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                    data-bs-parent="#derivacionAccordion">
                    <div class="accordion-body">
                        <p>Los datos contenidos en esta ficha serán utilizados exclusivamente para fines de intervención
                            en convivencia escolar.</p>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="autorizacion" id="autorizacion"
                                required>
                            <label class="form-check-label" for="autorizacion">Autorizo el uso de los datos
                                proporcionados en esta ficha.</label>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <button type="submit" class="btn btn-primary mt-4">Enviar</button>
    </form>
</div>
@endsection
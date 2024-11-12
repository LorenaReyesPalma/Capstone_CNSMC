@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Columna del calendario -->
        <div class="col-md-8">
            <div>
                <h4>Citaciones Mes:</h4>
            </div>
            <div id="calendar"></div>
        </div>

        <!-- Columna de citaciones de la semana -->
        <div class="col-md-4">
            <div>
                <h4>Citaciones Semana:</h4>
            </div>
            <div id="calendar2"></div>
        </div>


    </div>

    <!--  -->

<!-- Modal -->
<div class="modal fade" id="searchStudentModal" tabindex="-1" aria-labelledby="searchStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="searchStudentModalLabel">Buscar Alumno</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="searchForm">
          <div class="mb-3">
            <label for="searchInput" class="form-label">Buscar por nombre o RUT</label>
            <input type="text" class="form-control" id="searchInput" placeholder="Nombre o RUT">
          </div>
          <div class="mb-3">
          <select name="cod_tipo_ensenanza" class="form-control">
                        <option value="">-- Selecciona un curso --</option>
                        @foreach($cursos as $curso)
                        <option value="{{ $curso->cod_tipo_ensenanza }}_{{ $curso->cod_grado }}_{{ $curso->letra_curso }}">
                            {{ $curso->desc_grado }} {{ $curso->letra_curso }}
                        </option>
                        @endforeach
            </select>
          </div>


          <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
        <div class="mt-3">
          <label for="alumnoSelect" class="form-label">Selecciona un alumno</label>
          <select class="form-select" id="alumnoSelect">
            <option value="">-- Selecciona un alumno --</option>
            <!-- Aquí se agregarán los alumnos desde la búsqueda -->
          </select>
        </div>
      </div>
    </div>
  </div>
</div>




<!-- Modal para agregar citación -->
<div class="modal fade" id="citacionModal" tabindex="-1" aria-labelledby="citacionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="citacionModalLabel">Agregar Citación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="citacionForm" action="{{ route('citaciones.guardar') }}" method="POST">
          @csrf <!-- Protección contra CSRF -->
          <!-- Campos ocultos -->
          <input  id="runInput" name="run" type="text">
          <input id="digitoVerInput" name="digito_ver" type="text">
          
          <!-- Tipo de Acción (Opcional: Si debe mostrarse, agregar un campo) -->
          <input id="tipo_accion" name="tipo_accion" value="Citacion Apoderado" type="text">

          <!-- Fecha de la Citación -->
          <div class="mb-3">
            <label for="fecha_citacion" class="form-label">Fecha de la Citación</label>
            <input type="date" class="form-control" id="fecha_citacion" name="fecha_citacion" required>
          </div>
          
          <!-- Hora de la Citación -->
          <div class="mb-3">
            <label for="hora_citacion" class="form-label">Hora de la Citación</label>
            <input type="time" class="form-control" id="hora_citacion" name="hora_citacion" required>
          </div>

          <!-- Observaciones -->
          <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
          </div>

          <!-- Botón para guardar -->
          <button type="submit" class="btn btn-primary">Agregar Citación</button>
        </form>
      </div>
    </div>
  </div>
</div>




<!-- Modal para mostrar el detalle de la citación -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleModalLabel">Detalle de Citación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Título:</strong> <span id="detalleTitulo"></span></p>
                <p><strong>Alumno:</strong> <span id="detalleAlumno"></span></p>
                <p><strong>Citado por:</strong> <span id="detalleColaborador"></span></p>
                <p><strong>Fecha:</strong> <span id="detalleFecha"></span></p>
                <p><strong>Hora:</strong> <span id="detalleHora"></span></p>
                <p><strong>Observaciones:</strong> <span id="detalleObservaciones"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" id="redirectButton" class="btn btn-primary">
                    <i class="fas fa-eye"></i> Ir
                </a>
            </div>
        </div>
    </div>
</div>
</div> <!-- Fin del contenedor principal -->

<!-- Scripts y estilos -->
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@5.10.0/locales/es.js'></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* Estilo del calendario */
#calendar {
    max-width: 80%;
    margin: 0 auto;
    font-family: 'Arial', sans-serif;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}
#calendar2{
    height: 94%;
}
.fc .fc-toolbar-title {
    color: #002A45;
    font-weight: bold;
    font-size: 1.5rem;
}

.fc-daygrid-day-number {
    color: #002A45;
}

.fc-daygrid-event {
    border: none;
    color: #ffffff !important;
    font-weight: bold;
}

.fc-button-primary {
    background-color: #002A45 !important;
    border: none;
}

.fc-button-primary:hover {
    background-color: #014a70 !important;
}

/* Estilo de los modales */
.modal-header,
.modal-footer {
    background-color: #002A45;
    color: white;
}

.btn-primary:hover {
    background-color: #014a70;
}

/* Estilos adicionales */
#citacionesSemana {
    max-height: 400px;
    overflow-y: auto;
}

/* Ajustes para pantallas pequeñas */
@media (max-width: 768px) {
    #calendar {
        max-width: 100%;
        padding: 10px;
    }
}
</style>

<script>
$(document).ready(function() {
    const colors = [
        "#FF5733", "#33FF57", "#3357FF", "#FF33A8", "#FF8C33", "#33FFD2", "#AF33FF", "#FFAF33"
    ];

    const collaboratorColors = {};

    function getColorForCollaborator(name) {
        if (!collaboratorColors[name]) {
            const colorIndex = Object.keys(collaboratorColors).length % colors.length;
            collaboratorColors[name] = colors[colorIndex];
        }
        return collaboratorColors[name];
    }

    // Calendario principal
    const calendarEl = document.getElementById('calendar');
    const headerToolbar = window.innerWidth < 768 ? {
        left: 'prev,next',
        center: 'title',
        right: ''
    } : {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,dayGridDay'
    };

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        initialView: window.innerWidth < 768 ? 'dayGridWeek' : 'dayGridMonth',
        editable: true,
        headerToolbar: headerToolbar,
        selectable: true,
        events: '{{ route('citaciones.obtener') }}',

        eventDidMount: function(info) {
            const collaboratorName = info.event.extendedProps.colaborador_nombre;
            const color = getColorForCollaborator(collaboratorName);
            info.el.style.backgroundColor = color;
        },
          // Evento al seleccionar una fecha en el calendario
          select: function(info) {
            $('#fecha_citacion').val(info.start.toISOString().split('T')[0]); // Establece la fecha seleccionada
            $('#searchStudentModal').modal('show');  // Muestra el modal para buscar alumno
        },

        // Evento al hacer clic en un evento del calendario
        eventClick: function(info) {
            const event = info.event;
            $('#detalleTitulo').text(event.title);
            $('#detalleFecha').text(event.start.toLocaleDateString());
            $('#detalleHora').text(event.start.toLocaleTimeString());
            $('#detalleObservaciones').text(event.extendedProps.observaciones);
            $('#detalleColaborador').text(event.extendedProps.colaborador_nombre);
            $('#detalleAlumno').text(event.extendedProps.alumno_nombre);

            let url = '#';
            if (event.extendedProps.citacion_id && event.extendedProps.tipo_accion) {
                switch (event.extendedProps.tipo_accion) {
                    case 'Entrevista Alumno':
                        url = `{{ route('entrevistas.entrevistaAlumno', ['id' => 'REPLACE_DERIVACION', 'tipo_entrevista' => 1, 'citacion' => 'REPLACE_CITACION']) }}`;
                        break;
                    case 'Entrevista Apoderado':
                        url = `{{ route('entrevistas.entrevistaApoderado', ['id' => 'REPLACE_DERIVACION', 'tipo_entrevista' => 2, 'citacion' => 'REPLACE_CITACION']) }}`;
                        break;
                    case 'Tomar Acuerdos':
                        url = `{{ route('entrevistas.entrevistaCompromiso', ['id' => 'REPLACE_DERIVACION', 'tipo_entrevista' => 3, 'citacion' => 'REPLACE_CITACION']) }}`;
                        break;
                    case 'Citacion Apoderado':
                        url = `{{ route('entrevistas.citacionApoderado', ['tipo_entrevista' => 4, 'citacion' => 'REPLACE_CITACION']) }}`;
                        break;
                }
                url = url.replace('REPLACE_DERIVACION', event.extendedProps.derivacion_id)
                         .replace('REPLACE_CITACION', event.extendedProps.citacion_id);
            }
            $('#redirectButton').attr('href', url); // Establece la URL de redirección
            $('#detalleModal').modal('show'); // Muestra el modal con detalles del evento
        }
    });
    calendar.render(); // Renderiza el calendario


    // Calendario semanal en formato de lista
    const calendar2El = document.getElementById('calendar2');
    const calendar2 = new FullCalendar.Calendar(calendar2El, {
        locale: 'es',
        initialView: 'listWeek',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: ''
        },
        events: '{{ route('citaciones.obtener') }}',
        eventDidMount: function(info) {
            const collaboratorName = info.event.extendedProps.colaborador_nombre;
            const color = getColorForCollaborator(collaboratorName);
            info.el.style.backgroundColor = color;
        },

        
        eventClick: function(info) {
            const event = info.event;
            $('#detalleTitulo').text(event.title);
            $('#detalleFecha').text(event.start.toLocaleDateString());
            $('#detalleHora').text(event.start.toLocaleTimeString());
            $('#detalleObservaciones').text(event.extendedProps.observaciones);
            $('#detalleColaborador').text(event.extendedProps.colaborador_nombre);

            let url = '#';
            if (event.extendedProps.derivacion_id && event.extendedProps.citacion_id && event.extendedProps.tipo_accion) {
                switch (event.extendedProps.tipo_accion) {
                    case 'Entrevista Alumno':
                        url = `{{ route('entrevistas.entrevistaAlumno', ['id' => 'REPLACE_DERIVACION', 'tipo_entrevista' => 1, 'citacion' => 'REPLACE_CITACION']) }}`;
                        break;
                    case 'Entrevista Apoderado':
                        url = `{{ route('entrevistas.entrevistaApoderado', ['id' => 'REPLACE_DERIVACION', 'tipo_entrevista' => 2, 'citacion' => 'REPLACE_CITACION']) }}`;
                        break;
                    case 'Tomar Acuerdos':
                        url = `{{ route('entrevistas.entrevistaCompromiso', ['id' => 'REPLACE_DERIVACION', 'tipo_entrevista' => 3, 'citacion' => 'REPLACE_CITACION']) }}`;
                        break;
                    case 'Citacion Apoderado':
                        url = `{{ route('entrevistas.citacionApoderado', ['tipo_entrevista' => 4, 'citacion' => 'REPLACE_CITACION']) }}`;
                        break;
                }
                url = url.replace('REPLACE_DERIVACION', event.extendedProps.derivacion_id)
                    .replace('REPLACE_CITACION', event.extendedProps.citacion_id);
            }
            $('#redirectButton').attr('href', url);
            $('#detalleModal').modal('show');
        }
    });
    calendar2.render();
});

 // Buscando alumnos
 document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Evita el envío del formulario

        const cursoSeleccionado = document.querySelector('select[name="cod_tipo_ensenanza"]').value;
        const search = document.getElementById('searchInput').value;

        // Prepara la URL de la solicitud con los parámetros
        const url = new URL('{{ route("buscarAlumnos2") }}', window.location.origin);
        const params = new URLSearchParams();
        params.append('cod_tipo_ensenanza', cursoSeleccionado);
        params.append('search', search);

        // Realiza la solicitud fetch
        fetch(url + '?' + params.toString())
            .then(response => response.json())
            .then(data => {
                const alumnos = data.alumnos;
                let optionsHtml = '<option value="">-- Selecciona un alumno --</option>';

                if (alumnos.length > 0) {
                    alumnos.forEach(alumno => {
                        optionsHtml += `
                            <option value="${alumno.run}_${alumno.digito_ver}" data-run="${alumno.run}" data-digito_ver="${alumno.digito_ver}">
                                ${alumno.nombres} ${alumno.apellido_paterno} ${alumno.apellido_materno} 
                                (RUT: ${alumno.run}-${alumno.digito_ver})
                            </option>`;
                    });
                } else {
                    optionsHtml += '<option value="">No se encontraron alumnos</option>';
                }

                // Agregar las opciones al select
                document.getElementById('alumnoSelect').innerHTML = optionsHtml;
            })
            .catch(error => console.error('Error:', error)); // Manejo de errores
    });

    // Manejar el cambio de selección de alumno y actualizar los campos ocultos
    document.getElementById('alumnoSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (selectedOption.value) {
            const run = selectedOption.dataset.run;
            const digitoVer = selectedOption.dataset.digito_ver;

            // Actualizar los campos ocultos con el RUN y dígito verificador
            document.getElementById('runInput').value = run;
            document.getElementById('digitoVerInput').value = digitoVer;

            // Una vez que se selecciona el alumno, abrir el modal de citación
            $('#searchStudentModal').modal('hide');  // Cierra el modal de búsqueda de alumno
            $('#citacionModal').modal('show');     // Muestra el modal para añadir citación
        } else {
            // Limpiar los campos ocultos si no hay selección
            document.getElementById('runInput').value = '';
            document.getElementById('digitoVerInput').value = '';
        }
    });


</script>


@endsection
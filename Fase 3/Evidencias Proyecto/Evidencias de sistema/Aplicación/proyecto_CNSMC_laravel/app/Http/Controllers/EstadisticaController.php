<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
use App\Models\Derivacion;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;
use App\Models\CambioEstadoDerivacion;
use DateTime;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;




class estadisticaController extends BaseController {

    // Lista de los meses en español
    private $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'J ulio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];

    public function estadisticas(Request $request)
    {
        // Obtener parámetros o establecer valores por defecto (mes y año actuales)
        $month = $request->input('month', date('m'));  // Establecer mes actual si no se recibe
        $year = $request->input('year', date('Y'));    // Establecer año actual si no se recibe
        
        // Log para verificar la entrada de parámetros
        Log::info('Estadisticas - Parámetros recibidos', ['month' => $month, 'year' => $year]);
    
        // Validar que month y year sean números válidos
        if (!is_numeric($month) || !is_numeric($year)) {
            Log::error('Estadisticas - Parámetros inválidos', ['month' => $month, 'year' => $year]);
            return response()->json(['error' => 'Month and year are required and must be numeric'], 400);
        }
    
        // Comprobar que el mes y año sean válidos (1-12 para el mes, y un valor razonable para el año)
        if ($month < 1 || $month > 12 || $year < 1900 || $year > date('Y')) {
            Log::warning('Estadisticas - Mes o año inválido', ['month' => $month, 'year' => $year]);
            return response()->json(['error' => 'Invalid month or year'], 400);
        }
    
        // Consultar en la base de datos
        try {
            Log::info('Estadisticas - Realizando consulta en la base de datos', ['month' => $month, 'year' => $year]);
    
            $derivaciones = DB::table('derivacions')
                ->select('curso', DB::raw('count(*) as total'))
                ->whereYear('fecha_derivacion', $year)
                ->whereMonth('fecha_derivacion', $month)
                ->groupBy('curso')
                ->get();
    
            // Verificar si se obtuvieron resultados
            if ($derivaciones->isEmpty()) {
                Log::info('Estadisticas - No se encontraron registros', ['month' => $month, 'year' => $year]);
                return response()->json(['message' => 'No records found for the specified month and year'], 404);
            }
    
            // Procesar los cursos para obtener el formato deseado (ej. "Pre-kinder A", "Kinder B", etc.)
            $derivaciones = $derivaciones->map(function ($item) {
                if (strpos($item->curso, 'Transición') !== false) {
                    if (strpos($item->curso, 'Pre-kinder') !== false) {
                        $item->curso = str_replace("1er nivel de Transición (Pre-kinder)", "Pre-kinder", $item->curso);
                    } elseif (strpos($item->curso, 'Kinder') !== false) {
                        $item->curso = str_replace("2° nivel de Transición (Kinder)", "Kinder", $item->curso);
                    }
                }
                return $item;
            });
    
            Log::info('Estadisticas - Registros encontrados', ['data' => $derivaciones]);
    
        } catch (\Exception $e) {
            // Manejar errores de consulta
            Log::error('Estadisticas - Error en la consulta de base de datos', [
                'message' => $e->getMessage(),
                'exception' => $e
            ]);
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        }
    
        // Devolver JSON en caso de solicitud AJAX
        if ($request->ajax()) {
            Log::info('Estadisticas - Respuesta AJAX', ['data' => $derivaciones]);
            return response()->json($derivaciones);
        }

        // $usuarioId = Auth::id();
        // $derivacionesEstado1 = Derivacion::where('estado_id', 1)
        //     ->whereMonth('fecha_derivacion', $month)
        //     ->whereYear('fecha_derivacion', $year)
        //     ->count();

        // // Contar cuántas derivaciones están en estado 2 en el mes actual del usuario autenticado
        // $derivacionesEstado2MesActual = Derivacion::where('estado_id', 2)
        //     ->whereMonth('fecha_derivacion', $month)
        //     ->whereYear('fecha_derivacion', $year)
        //     ->count();

        // // Contar cuántas derivaciones están en estado 3 en el mes actual del usuario autenticado
        // $derivacionesEstado3MesActual = Derivacion::where('estado_id', 3)
        //     ->whereMonth('fecha_derivacion', $month)
        //     ->whereYear('fecha_derivacion', $year)
        //     ->count();

        // // Contar cuántas derivaciones totales hay en el mes actual
        // $derivacionesMesActual = Derivacion::whereMonth('fecha_derivacion', $month)
        //     ->whereYear('fecha_derivacion', $year)
        //     ->count();

        // Retornar la vista para solicitudes no AJAX
        Log::info('Estadisticas - Retornando vista', ['month' => $month, 'year' => $year]);
        return view('estadisticas.estadisticas', [
            'derivaciones' => $derivaciones,
            'mes' => $this->meses[intval($month)],  // Pasar el mes en español a la vista
            'year' => $year,
            // 'derivacionesEstado1' => $derivacionesEstado1,
            // 'derivacionesEstado2MesActual' => $derivacionesEstado2MesActual,
            // 'derivacionesEstado3MesActual' => $derivacionesEstado3MesActual,
            // 'derivacionesMesActual' => $derivacionesMesActual,
        ]);

    }

 

    public function getDerivaciones(Request $request)
    {
        $month = $request->input('month', date('m'));  // Establecer mes actual si no se recibe
        $year = $request->input('year', date('Y'));    // Establecer año actual si no se recibe
        
        $derivaciones = DB::table('derivacions')
            ->select('curso', DB::raw('count(*) as total'))
            ->whereYear('fecha_derivacion', $year)
            ->whereMonth('fecha_derivacion', $month)
            ->groupBy('curso')
            ->get();

        // Extraer los cursos para mostrar
        $derivaciones = $derivaciones->map(function ($item) {
            if (strpos($item->curso, 'Transición') !== false) {
                if (strpos($item->curso, 'Pre-kinder') !== false) {
                    $item->curso = str_replace("1er nivel de Transición (Pre-kinder)", "Pre-kinder", $item->curso);
                } elseif (strpos($item->curso, 'Kinder') !== false) {
                    $item->curso = str_replace("2° nivel de Transición (Kinder)", "Kinder", $item->curso);
                }
            }
            return $item;
        });

        // Extraer los cursos y los totales
        $cursos = $derivaciones->pluck('curso'); // Cursos obtenidos
        $totales = $derivaciones->pluck('total'); // Totales de derivaciones

        // Devolver los datos como JSON
        return response()->json([
            'cursos' => $cursos,
            'totales' => $totales,
            'mes' => $this->meses[intval($month)] // Enviar el mes en español
        ]);
    }

        public function getDerivacionesEstado(Request $request)
    {
        // Obtener mes y año de la solicitud o usar valores actuales como predeterminados
        $month = $request->input('month', date('m')); 
        $year = $request->input('year', date('Y'));

        // Consultar el número de derivaciones por estado en el mes y año seleccionados
        $derivacionesEstado1 = Derivacion::where('estado_id', 1)
            ->whereMonth('fecha_derivacion', $month)
            ->whereYear('fecha_derivacion', $year)
            ->count();

        $derivacionesEstado2 = Derivacion::where('estado_id', 2)
            ->whereMonth('fecha_derivacion', $month)
            ->whereYear('fecha_derivacion', $year)
            ->count();

        $derivacionesEstado3 = Derivacion::where('estado_id', 3)
            ->whereMonth('fecha_derivacion', $month)
            ->whereYear('fecha_derivacion', $year)
            ->count();

        $derivacionesTotales = Derivacion::whereMonth('fecha_derivacion', $month)
            ->whereYear('fecha_derivacion', $year)
            ->count();

        // Devolver los datos como respuesta JSON
        return response()->json([
            'estado1' => $derivacionesEstado1,
            'estado2' => $derivacionesEstado2,
            'estado3' => $derivacionesEstado3,
            'totales' => $derivacionesTotales,
            'mes' => $this->meses[intval($month)], // Traducir el mes si tienes una lista definida
            'año' => $year
        ]);
    }




    public function getDatosGraficos(Request $request)
    {
        // Obtener el año y mes desde el request, con valores predeterminados en caso de que no se proporcionen
        $year = $request->input('year');  // Valor predeterminado 2024
        $month = $request->input('month');  // Valor predeterminado 11 (noviembre)
    
        // Consulta para obtener los valores limpios y su frecuencia
        $resultados = DB::table(DB::raw('(SELECT 0 AS idx UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL SELECT 9) AS numbers'))
                        ->join('derivacions', function($join) {
                            $join->on(DB::raw('numbers.idx < JSON_LENGTH(derivacions.indicadores_personal)'), '=', DB::raw('1'));
                        })
                        ->select(DB::raw("REGEXP_REPLACE(
                                                JSON_UNQUOTE(JSON_EXTRACT(indicadores_personal, CONCAT('$[', numbers.idx, ']'))),
                                                '(:.*)$', ''
                                            ) AS valor"))
                        ->whereYear('fecha_derivacion', $year)
                        ->whereMonth('fecha_derivacion', $month)
                        ->whereRaw('numbers.idx < JSON_LENGTH(derivacions.indicadores_personal)')
                        ->groupBy(DB::raw("valor"))
                        ->selectRaw('COUNT(*) AS frecuencia')  // Contar la frecuencia de cada valor
                        ->get();
    
        // Formatear los resultados como un arreglo para el gráfico
        $resultados = $resultados->map(function ($item) {
            return [
                'valor' => $item->valor,
                'frecuencia' => $item->frecuencia,
            ];
        });
    
        // Retornar los resultados como JSON para el gráfico
        return response()->json($resultados);
    }

    public function getDatosGraficosFamiliar(Request $request)
    {
        // Obtener el año y mes desde el request, con valores predeterminados en caso de que no se proporcionen
        $year = $request->input('year');  // Valor predeterminado 2024
        $month = $request->input('month');  // Valor predeterminado 11 (noviembre)
    
        // Consulta para obtener los valores limpios y su frecuencia
        $resultados = DB::table(DB::raw('(SELECT 0 AS idx UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL SELECT 9) AS numbers'))
                        ->join('derivacions', function($join) {
                            $join->on(DB::raw('numbers.idx < JSON_LENGTH(derivacions.indicadores_familiar)'), '=', DB::raw('1'));
                        })
                        ->select(DB::raw("REGEXP_REPLACE(
                                                JSON_UNQUOTE(JSON_EXTRACT(indicadores_familiar, CONCAT('$[', numbers.idx, ']'))),
                                                '(:.*)$', ''
                                            ) AS valor"))
                        ->whereYear('fecha_derivacion', $year)
                        ->whereMonth('fecha_derivacion', $month)
                        ->whereRaw('numbers.idx < JSON_LENGTH(derivacions.indicadores_familiar)')
                        ->groupBy(DB::raw("valor"))
                        ->selectRaw('COUNT(*) AS frecuencia')  // Contar la frecuencia de cada valor
                        ->get();
    
        // Formatear los resultados como un arreglo para el gráfico
        $resultados = $resultados->map(function ($item) {
            return [
                'valor' => $item->valor,
                'frecuencia' => $item->frecuencia,
            ];
        });
    
        // Retornar los resultados como JSON para el gráfico
        return response()->json($resultados);
    }


    public function getDatosGraficosSocial(Request $request)
    {
        // Obtener el año y mes desde el request, con valores predeterminados en caso de que no se proporcionen
        $year = $request->input('year');  // Valor predeterminado 2024
        $month = $request->input('month');  // Valor predeterminado 11 (noviembre)
    
        // Consulta para obtener los valores limpios y su frecuencia
        $resultados = DB::table(DB::raw('(SELECT 0 AS idx UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL SELECT 9) AS numbers'))
                        ->join('derivacions', function($join) {
                            $join->on(DB::raw('numbers.idx < JSON_LENGTH(derivacions.indicadores_socio_comunitario)'), '=', DB::raw('1'));
                        })
                        ->select(DB::raw("REGEXP_REPLACE(
                                                JSON_UNQUOTE(JSON_EXTRACT(indicadores_socio_comunitario, CONCAT('$[', numbers.idx, ']'))),
                                                '(:.*)$', ''
                                            ) AS valor"))
                        ->whereYear('fecha_derivacion', $year)
                        ->whereMonth('fecha_derivacion', $month)
                        ->whereRaw('numbers.idx < JSON_LENGTH(derivacions.indicadores_socio_comunitario)')
                        ->groupBy(DB::raw("valor"))
                        ->selectRaw('COUNT(*) AS frecuencia')  // Contar la frecuencia de cada valor
                        ->get();
    
        // Formatear los resultados como un arreglo para el gráfico
        $resultados = $resultados->map(function ($item) {
            return [
                'valor' => $item->valor,
                'frecuencia' => $item->frecuencia,
            ];
        });
    
        // Retornar los resultados como JSON para el gráfico
        return response()->json($resultados);
    }

    public function getDatosGraficosCombinados(Request $request)
{
    // Obtener el año y mes desde el request, con valores predeterminados en caso de que no se proporcionen
    $year = $request->input('year');  // Valor predeterminado 2024
    $month = $request->input('month');  // Valor predeterminado 11 (noviembre)

    // Consulta para obtener los valores limpios y su frecuencia de indicadores_personal
    $resultadosPersonal = DB::table(DB::raw('(SELECT 0 AS idx UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL SELECT 9) AS numbers'))
                        ->join('derivacions', function($join) {
                            $join->on(DB::raw('numbers.idx < JSON_LENGTH(derivacions.indicadores_personal)'), '=', DB::raw('1'));
                        })
                        ->select(DB::raw("REGEXP_REPLACE(
                                                JSON_UNQUOTE(JSON_EXTRACT(indicadores_personal, CONCAT('$[', numbers.idx, ']'))),
                                                '(:.*)$', ''
                                            ) AS valor"))
                        ->whereYear('fecha_derivacion', $year)
                        ->whereMonth('fecha_derivacion', $month)
                        ->whereRaw('numbers.idx < JSON_LENGTH(derivacions.indicadores_personal)')
                        ->groupBy(DB::raw("valor"))
                        ->selectRaw('COUNT(*) AS frecuencia')
                        ->get();

    // Consulta para obtener los valores limpios y su frecuencia de indicadores_familiar
    $resultadosFamiliar = DB::table(DB::raw('(SELECT 0 AS idx UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL SELECT 9) AS numbers'))
                        ->join('derivacions', function($join) {
                            $join->on(DB::raw('numbers.idx < JSON_LENGTH(derivacions.indicadores_familiar)'), '=', DB::raw('1'));
                        })
                        ->select(DB::raw("REGEXP_REPLACE(
                                                JSON_UNQUOTE(JSON_EXTRACT(indicadores_familiar, CONCAT('$[', numbers.idx, ']'))),
                                                '(:.*)$', ''
                                            ) AS valor"))
                        ->whereYear('fecha_derivacion', $year)
                        ->whereMonth('fecha_derivacion', $month)
                        ->whereRaw('numbers.idx < JSON_LENGTH(derivacions.indicadores_familiar)')
                        ->groupBy(DB::raw("valor"))
                        ->selectRaw('COUNT(*) AS frecuencia')
                        ->get();

    // Consulta para obtener los valores limpios y su frecuencia de indicadores_socio_comunitario
    $resultadosSocial = DB::table(DB::raw('(SELECT 0 AS idx UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
                                        SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL 
                                        SELECT 8 UNION ALL SELECT 9) AS numbers'))
                        ->join('derivacions', function($join) {
                            $join->on(DB::raw('numbers.idx < JSON_LENGTH(derivacions.indicadores_socio_comunitario)'), '=', DB::raw('1'));
                        })
                        ->select(DB::raw("REGEXP_REPLACE(
                                                JSON_UNQUOTE(JSON_EXTRACT(indicadores_socio_comunitario, CONCAT('$[', numbers.idx, ']'))),
                                                '(:.*)$', ''
                                            ) AS valor"))
                        ->whereYear('fecha_derivacion', $year)
                        ->whereMonth('fecha_derivacion', $month)
                        ->whereRaw('numbers.idx < JSON_LENGTH(derivacions.indicadores_socio_comunitario)')
                        ->groupBy(DB::raw("valor"))
                        ->selectRaw('COUNT(*) AS frecuencia')
                        ->get();

    // Combinar todos los resultados en un solo arreglo
    $resultadosCombinados = $resultadosPersonal->merge($resultadosFamiliar)->merge($resultadosSocial);

    // Formatear los resultados combinados para el gráfico
    $resultadosCombinados = $resultadosCombinados->map(function ($item) {
        return [
            'valor' => $item->valor,
            'frecuencia' => $item->frecuencia,
        ];
    });

    // Retornar los resultados combinados como JSON para el gráfico
    return response()->json($resultadosCombinados);
}


    public function obtenerCuentaProgramas(Request $request)
    {
        // Obtener mes y año desde el request
        $month = $request->input('month');
        $year = $request->input('year');
    
        // Obtener las cuentas de los programas
        $cuentaRetencion = Derivacion::where('programa_retencion', 1)
                                    ->whereMonth('fecha_derivacion', $month)   // Filtrar por mes
                                    ->whereYear('fecha_derivacion', $year)     // Filtrar por año
                                    ->count();
    
        $cuentaIntegracion = Derivacion::where('programa_integracion', 1)
                                       ->whereMonth('fecha_derivacion', $month)   // Filtrar por mes
                                       ->whereYear('fecha_derivacion', $year)     // Filtrar por año
                                       ->count();
    
        // Preparar los datos para el gráfico
        $resultados = [
            [
                'valor' => 'Retención',
                'frecuencia' => $cuentaRetencion,
            ],
            [
                'valor' => 'PIE',
                'frecuencia' => $cuentaIntegracion,
            ]
        ];
    
        // Retornar los resultados como JSON
        return response()->json($resultados);
    }


        // En tu controlador de estadísticas
    public function obtenerEstadoDerivaciones(Request $request)
    {
        $month = $request->input('month');  // Obtener mes del request
        $year = $request->input('year');    // Obtener año del request
    
        // Obtener los datos de derivaciones agrupados por estado_id
        $derivaciones = DB::table('derivacions')
            ->select('estado_id', DB::raw('COUNT(*) as total'))
            ->whereMonth('fecha_derivacion', $month)  // Filtrar por mes
            ->whereYear('fecha_derivacion', $year)    // Filtrar por año
            ->groupBy('estado_id')
            ->get();
    
        // Si necesitas modificar los valores de estado_id (similar a lo que hacías con los cursos):
        $derivaciones = $derivaciones->map(function ($item) {
            // Aquí puedes realizar modificaciones similares a lo que hacías con los cursos, si es necesario
            if ($item->estado_id == 1) {
                $item->estado_id = 'Pendiente';
            } elseif ($item->estado_id == 2) {
                $item->estado_id = 'Aprobado';
            } elseif ($item->estado_id == 3) {
                $item->estado_id = 'Rechazado';
            }
            // Puedes seguir agregando más condiciones si tienes más estados para reemplazar
            return $item;
        });
    
        // Devolver los datos en formato JSON
        return response()->json([
            'estados' => $derivaciones->pluck('estado_id'),  // Estados
            'totales' => $derivaciones->pluck('total'),     // Totales de derivaciones
        ]);
    }

    
    public function exportarExcel(Request $request)
    {
        // Mapeo de meses en inglés a español
        $mesesEspañol = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre',
        ];
    
        // Obtener parámetros o establecer valores por defecto
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
    
        // Convertir número de mes a nombre
        $nombreMesIngles = DateTime::createFromFormat('!m', $month)->format('F');
        $nombreMes = ucfirst($mesesEspañol[$nombreMesIngles]);
    
        // Obtener datos
        $derivaciones = DB::table('derivacions')
            ->select('curso', DB::raw('count(*) as total'))
            ->whereYear('fecha_derivacion', $year)
            ->whereMonth('fecha_derivacion', $month)
            ->groupBy('curso')
            ->get();
    
        $cuentaRetencion = Derivacion::where('programa_retencion', 1)
            ->whereMonth('fecha_derivacion', $month)
            ->whereYear('fecha_derivacion', $year)
            ->count();
    
        $cuentaIntegracion = Derivacion::where('programa_integracion', 1)
            ->whereMonth('fecha_derivacion', $month)
            ->whereYear('fecha_derivacion', $year)
            ->count();
    
        $programas = [
            ['valor' => 'Programa Retención', 'frecuencia' => $cuentaRetencion],
            ['valor' => 'Programa Integración', 'frecuencia' => $cuentaIntegracion],
        ];
    
        // Obtener promedios
        $promedios = $this->promedioCambioEstadoMes($request)->getData();
    
        // Crear hoja de cálculo
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Título principal
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', "Estadísticas Derivaciones Escolares - $nombreMes $year");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
    
        // Encabezados para derivaciones por curso
        $sheet->setCellValue('A3', 'Curso')
              ->setCellValue('B3', 'Total Derivaciones');
        $sheet->getStyle('A3:B3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '0070C0'], // Azul
            ],
        ]);
    
        // Llenar datos de derivaciones por curso
        $row = 4;
        foreach ($derivaciones as $index => $derivacion) {
            $sheet->setCellValue('A' . $row, $derivacion->curso)
                  ->setCellValue('B' . $row, $derivacion->total);
    
            // Alternar color en las filas
            if ($index % 2 === 0) { // Filas pares
                $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'D9E2F3'], // Gris claro
                    ],
                ]);
            }
            $row++;
        }
    
        // Encabezados para programas
        $startRowProgramas = $row + 2;
        $sheet->setCellValue('A' . $startRowProgramas, 'Programa')
              ->setCellValue('B' . $startRowProgramas, 'Frecuencia');
        $sheet->getStyle('A' . $startRowProgramas . ':B' . $startRowProgramas)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4CAF50'], // Verde
            ],
        ]);
    
        $row = $startRowProgramas + 1;
        foreach ($programas as $programa) {
            $sheet->setCellValue('A' . $row, $programa['valor'])
                  ->setCellValue('B' . $row, $programa['frecuencia']);
    
            // Alternar color en las filas
            if (($row - $startRowProgramas) % 2 === 0) {
                $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'E8F5E9'], // Verde claro
                    ],
                ]);
            }
            $row++;
        }
    
        // Encabezados para promedios
        $startRowPromedios = $row + 2;
        $sheet->setCellValue('A' . $startRowPromedios, 'Promedios de Cambios de Estado');
        $sheet->getStyle('A' . $startRowPromedios)->getFont()->setBold(true);
    
        $sheet->setCellValue('A' . ($startRowPromedios + 1), 'Tipo')
              ->setCellValue('B' . ($startRowPromedios + 1), 'Promedio (días)');
        $sheet->getStyle('A' . ($startRowPromedios + 1) . ':B' . ($startRowPromedios + 1))->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF9800'], // Naranja
            ],
        ]);
    
        $sheet->setCellValue('A' . ($startRowPromedios + 2), 'Aceptada')
              ->setCellValue('B' . ($startRowPromedios + 2), $promedios->Aceptada);
        $sheet->setCellValue('A' . ($startRowPromedios + 3), 'Finalizada')
              ->setCellValue('B' . ($startRowPromedios + 3), $promedios->Finalizada);
    
        // Ajustar tamaño de columnas
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        // Crear escritor y enviar el archivo
        $writer = new Xlsx($spreadsheet);
        $filename = "Estadisticas_Derivaciones_Escolares_{$month}_{$year}.xlsx";
    
        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }
    

    public function promedioCambioEstadoMes(Request $request)
{
    // Obtener el año y mes desde el request, con valores predeterminados
    $year = $request->input('year', now()->year); // Año actual como predeterminado
    $month = $request->input('month', now()->month); // Mes actual como predeterminado

    // Variables para acumular diferencias de días entre estados
    $diferencias1A2 = [];
    $diferencias2A3 = [];

    // Obtener todos los cambios de estado filtrados por año y mes, agrupados por derivacion_id
    $cambiosEstado = CambioEstadoDerivacion::whereYear('fecha_actualizacion', $year)
        ->whereMonth('fecha_actualizacion', $month)
        ->orderBy('fecha_actualizacion')
        ->get()
        ->groupBy('derivacion_id'); // Agrupar por derivación

    foreach ($cambiosEstado as $cambios) {
        // Ordenar por fecha para garantizar el orden correcto
        $cambios = $cambios->sortBy('fecha_actualizacion')->values();

        for ($i = 0; $i < $cambios->count(); $i++) {
            $cambioActual = $cambios[$i];

            if ($cambioActual->estado_id == 1 && isset($cambios[$i + 1]) && $cambios[$i + 1]->estado_id == 2) {
                $fecha1 = Carbon::parse($cambioActual->fecha_actualizacion);
                $fecha2 = Carbon::parse($cambios[$i + 1]->fecha_actualizacion);
                $diferencias1A2[] = $fecha2->diffInDays($fecha1);
            }

            if ($cambioActual->estado_id == 2 && isset($cambios[$i + 1]) && $cambios[$i + 1]->estado_id == 3) {
                $fecha1 = Carbon::parse($cambioActual->fecha_actualizacion);
                $fecha2 = Carbon::parse($cambios[$i + 1]->fecha_actualizacion);
                $diferencias2A3[] = $fecha2->diffInDays($fecha1);
            }
        }
    }

    // Calcular los promedios
    $promedio1A2 = count($diferencias1A2) > 0 ? array_sum($diferencias1A2) / count($diferencias1A2) : 0;
    $promedio2A3 = count($diferencias2A3) > 0 ? array_sum($diferencias2A3) / count($diferencias2A3) : 0;

    // Asegurar que los valores sean positivos
    $promedio1A2 = round($promedio1A2 * -1);
    $promedio2A3 = round($promedio2A3 * -1);

    // Retornar los resultados como JSON
    return response()->json([
        'Año' => $year,
        'Mes' => $month,
        'Aceptada' => $promedio1A2,
        'Finalizada' => $promedio2A3,
    ]);
}


            

}

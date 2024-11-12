<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Matricula;
use Carbon\Carbon;

class MatriculaController extends Controller
{
    public function cargarArchivo(Request $request)
    {
        // Validar el archivo
        $request->validate([
            'archivo' => 'required|mimes:xls,xlsx'
        ]);

        // Cargar el archivo Excel
        $file = $request->file('archivo');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $hoja = $spreadsheet->getActiveSheet();

        // Limpiar la tabla antes de insertar los datos
        Matricula::truncate();

        // Procesar las filas del archivo Excel
        $filaActual = 2; // Asumimos que la primera fila es de encabezados
        while ($hoja->getCell("A" . $filaActual)->getValue() !== null) {
            // Obtener los valores de las celdas
            $ano = $hoja->getCell("A" . $filaActual)->getValue();
            $codTipoEnsenanza = $hoja->getCell("C" . $filaActual)->getValue();
            $codGrado = $hoja->getCell("D" . $filaActual)->getValue();
            $descGrado = $hoja->getCell("E" . $filaActual)->getValue();
            $letraCurso = $hoja->getCell("F" . $filaActual)->getValue();
            $run = $hoja->getCell("G" . $filaActual)->getValue();
            $digitoVer = $hoja->getCell("H" . $filaActual)->getValue();
            $genero = $hoja->getCell("I" . $filaActual)->getValue();
            $nombres = $hoja->getCell("J" . $filaActual)->getValue();
            $apellidoPaterno = $hoja->getCell("K" . $filaActual)->getValue();
            $apellidoMaterno = $hoja->getCell("L" . $filaActual)->getValue();
            $direccion = $hoja->getCell("M" . $filaActual)->getValue();
            $comunaResidencia = $hoja->getCell("N" . $filaActual)->getValue();
            $email = $hoja->getCell("P" . $filaActual)->getValue();
            $telefono = $hoja->getCell("R" . $filaActual)->getValue();

            // Convertir fechas
            $fechaNacimiento = $this->convertDate($hoja->getCell("S" . $filaActual)->getValue());
            $fechaIncorporacionCurso = $this->convertDate($hoja->getCell("U" . $filaActual)->getValue());
            $fechaRetiro = $this->convertDate($hoja->getCell("V" . $filaActual)->getValue());

            // Crear registro en la tabla matricula
            Matricula::create([
                'ano' => $ano,
                'cod_tipo_ensenanza' => $codTipoEnsenanza,
                'cod_grado' => $codGrado,
                'desc_grado' => $descGrado,
                'letra_curso' => $letraCurso,
                'run' => $run,
                'digito_ver' => $digitoVer,
                'genero' => $genero,
                'nombres' => $nombres,
                'apellido_paterno' => $apellidoPaterno,
                'apellido_materno' => $apellidoMaterno,
                'direccion' => $direccion,
                'comuna_residencia' => $comunaResidencia,
                'email' => $email,
                'telefono' => $telefono,
                'fecha_nacimiento' => $fechaNacimiento,
                'fecha_incorporacion_curso' => $fechaIncorporacionCurso,
                'fecha_retiro' => $fechaRetiro,
            ]);

            $filaActual++;
        }

        return redirect()->back()->with('success', 'Archivo cargado exitosamente.');
    }

    private function convertDate($date)
    {
        if (is_numeric($date)) {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date))->format('Y-m-d');
        }

        $formattedDate = \DateTime::createFromFormat('Y-m-d', $date);
        return $formattedDate ? $formattedDate->format('Y-m-d') : null;
    }
}

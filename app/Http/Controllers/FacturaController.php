<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\factura;
use Illuminate\Http\Request;
use App\Exports\FacturaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $path = public_path('xmls');
        $files = scandir($path);
        $array_f = [];
        $array_r = [];
        echo "<h1>Files:</h1>";
        for ($i = 2; $i < count($files); $i++) {
            echo "<p>" . $files[$i] . "</p>";
            $factura_file = public_path('xmls/' . $files[$i]);
            // crear el objeto CFDI
            $cfdi = \CfdiUtils\Cfdi::newFromString(
                file_get_contents($factura_file)
            );
            // obtener el QuickReader con el método dedicado
            $comprobante = $cfdi->getQuickReader();
            $serie = $comprobante['serie'];
            $folio = $comprobante['folio'];
            $uuid = $comprobante->complemento->timbreFiscalDigital['uuid'];
            $fecha_timbrado = $comprobante->complemento->timbreFiscalDigital['fechatimbrado'];

            $hijos = ($comprobante->conceptos)('concepto');

            $resultado = $array_f + $hijos;

            foreach ($resultado as $key => $hijo) {
                array_push($array_r, [
                    'Importe' => $hijo['Importe'],
                    'Descripcion' => $hijo['Descripcion'],
                    'NoIdentificacion' => $hijo['NoIdentificacion'],
                    'ClaveProdServ' => $hijo['ClaveProdServ'],
                    'Serie' => $serie,
                    'Folio' => $folio,
                    'UUID' => $uuid,
                    'Fecha_timbrado' => $fecha_timbrado,
                ]);
            }
        }

        $date = Carbon::now();

        Excel::store(new FacturaExport($array_r), $date->format('Y-m-d').'-report.xlsx');
        //return Storage::download('invoices.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(factura $factura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, factura $factura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(factura $factura)
    {
        //
    }
}

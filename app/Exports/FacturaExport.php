<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FacturaExport implements FromCollection, WithHeadings, WithMapping
{
    public $hijos;

    public function __construct($hijos)
    {

        $this->hijos = $hijos;
    }

    public function collection()
    {
        return collect($this->hijos);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function map($array_r): array
    {
        return [
            $array_r['Importe'],
            $array_r['Descripcion'],
            $array_r['NoIdentificacion'],
            $array_r['ClaveProdServ'],
            $array_r['Serie'],
            $array_r['Folio'],
            $array_r['UUID'],
            $array_r['Fecha_timbrado'],
        ];
    }

    public function headings(): array
    {
        return [
            'importe',
            'descripcion',
            'no identificacion',
            'claveprodserv',
            'serie',
            'folio',
            'uuid',
            'fecha_timbrado',
        ];
    }
}

<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Exports\FacturaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ExcelConverter extends Component
{
    use WithFileUploads, LivewireAlert;

    public $files = [];

    protected $listeners = ['refrescar' => '$refresh'];

    public function submit()
    {
        $validatedData = $this->validate([
            'files.*' => 'required',
        ]);

        foreach ($this->files as $file) {
            $file->store('public');
        }

        $this->emit('refrescar');
        $this->alert('success', 'Archivos cargados correctamente');
    }

    public function generarExcel()
    {
        // $path1 = public_path('xmls');
        // $files = scandir($path1);
        // dd($files);
        $path = Storage::allFiles('public');

        if (count($path) < 1) {
            $this->alert('warning', 'No se encontraron xml cargados');
        } else {
            $array_f = [];
            $array_r = [];
            for ($i = 2; $i < count($path); $i++) {
                $factura_file = public_path('storage/' . substr($path[$i], 7));
                // dd($factura_file);
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

            Excel::store(new FacturaExport($array_r), $date->format('Y-m-d') . '-report.xlsx', 'excel');
            $this->alert('success', 'Excel generado correctamente');
            Storage::delete($path);

        }
    }

    public function bajarfile($value)
    {
        $this->alert('success', 'Documento generado correctamente');
        return Storage::disk('excel')->download($value);
    }

    public function render()
    {
        return view('livewire.excel-converter');
    }
}

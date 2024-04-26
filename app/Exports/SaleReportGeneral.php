<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SaleReportGeneral implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;
    public function __construct($data)
    {
        $this->data     = $data;
    }
    
    public function view() : view
    {
        return view('admin.reports.sales.general.excel', 
        [
            'billings'              => $this->data["billings"],
            'total'                 => $this->data["total"],
            'anulado'               => $this->data["anulado"],
            'total_neto'            => $this->data["total_neto"],
            'fecha_inicial'         => $this->data["fecha_inicial"],
            'fecha_final'           => $this->data["fecha_final"],
            'business'              => $this->data["business"],
            'pagos'                 => $this->data["pagos"],
            'quantity'              => $this->data["quantity"],
            'doc_relacionados'      => $this->data["doc_relacionados"]
        ]);
    }
}

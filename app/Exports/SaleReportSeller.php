<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SaleReportSeller implements FromView, ShouldAutoSize
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
        return view('admin.reports.sales.sellers.excel', 
        [
            'billings'              => $this->data["billings"],
            'total'                 => $this->data["total"],
            'anulado'               => $this->data["anulado"],
            'total_neto'            => $this->data["total_neto"],
            'fecha_inicial'         => $this->data["fecha_inicial"],
            'fecha_final'           => $this->data["fecha_final"],
            'business'              => $this->data["business"],
            'pagos'                 => $this->data["pagos"],
            'seller'                => $this->data["seller"],
            'quantity'              => $this->data["quantity"]
        ]);
    }
}

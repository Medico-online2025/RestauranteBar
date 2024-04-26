<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PurchaseReportGeneral implements FromView, ShouldAutoSize
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
        return view('admin.reports.purchases.providers.excel', 
        [
            'buys'              => $this->data["buys"],
            'total'             => $this->data["total"],
            'fecha_inicial'     => $this->data["fecha_inicial"],
            'fecha_final'       => $this->data["fecha_final"],
            'business'          => $this->data["business"],
            'quantity'          => $this->data["quantity"]
        ]);
    }
}

<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\FromCollection;

class ExpenseReportPurchase implements FromView, ShouldAutoSize
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
        return view('admin.reports.purchases.expenses.excel', 
        [
            'expenses'          => $this->data["expenses"],
            'business'          => $this->data["business"],
            'total'             => $this->data["total"],
            'quantity'          => $this->data["quantity"]
        ]);
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ContactReportProvider implements FromView, ShouldAutoSize
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
        return view('admin.reports.contacts.providers.excel', 
        [
            'providers'         => $this->data["providers"],
            'business'          => $this->data["business"],
            'quantity'          => $this->data["quantity"]
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\InventoryReportProduct;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

class InventoryReportController extends Controller
{
    public function products()
    {
        return view('admin.reports.inventories.products.home');
    }

    public function search_products(Request $request)
    {
        if (!$request->ajax()) 
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $products       = DB::select("CALL get_list_products_data()");
        $quantity       = count($products);
        echo json_encode([
            "status"        => true,
            "products"      => $products,
            "quantity"      => $quantity
        ]);
    }

    public function export_products(Request $request)
    {
        $data["productos"]          = DB::select("CALL get_list_products_data()");
        $data["quantity"]           = count($data["productos"]);
        $data["business"]           = Business::where('id', 1)->first();
        $data['ruc']                = $data["business"]->ruc;
        $data['nombre_comercial']   = $data["business"]->nombre_comercial;
        $nombre_excel               = 'Reporte de Productos ' . date('d-m-Y H-i-s') . '.xlsx';
        $export_pdf                 = $request->input('export_pdf');

        if(!empty($export_pdf))
        {
            $pdf    = PDF::loadView('admin.reports.inventories.products.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Productos ' . date('d-m-Y H-i-s') . '.pdf');
        }
        else
        {
            return Excel::download(new InventoryReportProduct($data), $nombre_excel);
        }
    }
}

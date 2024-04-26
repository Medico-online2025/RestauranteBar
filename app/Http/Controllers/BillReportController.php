<?php

namespace App\Http\Controllers;

use App\Exports\ExpenseReportPurchase;
use App\Models\Bill;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

class BillReportController extends Controller
{
    public function purchases()
    {
        $data["users"]      = User::get();
        return view('admin.reports.purchases.expenses.home', $data);
    }

    public function search_expenses(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $fecha_inicial      = $request->input('fecha_inicial');
        $fecha_final        = $request->input('fecha_final');
        $user               = $request->input('user');
        switch ($user) {
            case '0':
                $expenses   = Bill::select('bills.*', 'users.nombres as usuario', 'purchase_descriptions.descripcion as gasto')
                            ->join('users', 'bills.idusuario', 'users.id')
                            ->join('purchase_descriptions', 'bills.idpurchase_description', '=' ,'purchase_descriptions.id')
                            ->whereBetween('fecha_emision', [$fecha_inicial, $fecha_final])
                            ->get();
                break;
            
            default:
                $expenses   = Bill::select('bills.*', 'users.nombres as usuario', 'purchase_descriptions.descripcion as gasto')
                            ->join('users', 'bills.idusuario', 'users.id')
                            ->join('purchase_descriptions', 'bills.idpurchase_description', '=' ,'purchase_descriptions.id')
                            ->where('idusuario', $user)
                            ->whereBetween('fecha_emision', [$fecha_inicial, $fecha_final])
                            ->get();
                break;
        }

        $quantity           = count($expenses);
        echo json_encode([
            'status'        => true,
            'expenses'      => $expenses,
            'quantity'      => $quantity
        ]);
    }

    public function export_expenses(Request $request)
    {
        $data["fecha_inicial"]      = $request->input('fecha_inicial');
        $data["fecha_final"]        = $request->input('fecha_final');
        $user                       = $request->input('user');
        $data["total"]              = 0;
        switch ($user) {
            case '0':
                $data["expenses"]   = Bill::select('bills.*', 'users.nombres as usuario', 'purchase_descriptions.descripcion as gasto')
                            ->join('users', 'bills.idusuario', 'users.id')
                            ->join('purchase_descriptions', 'bills.idpurchase_description', '=' ,'purchase_descriptions.id')
                            ->whereBetween('fecha_emision', [$data["fecha_inicial"], $data["fecha_final"]])
                            ->get();
                break;
            
            default:
                $data["expenses"]   = Bill::select('bills.*', 'users.nombres as usuario', 'purchase_descriptions.descripcion as gasto')
                            ->join('users', 'bills.idusuario', 'users.id')
                            ->join('purchase_descriptions', 'bills.idpurchase_description', '=' ,'purchase_descriptions.id')
                            ->where('idusuario', $user)
                            ->whereBetween('fecha_emision', [$data["fecha_inicial"], $data["fecha_final"]])
                            ->get();
                break;
        }
      
        $data["quantity"]           = count($data["expenses"]);
        $data["business"]           = Business::where('id', 1)->first();
        $data['ruc']                = $data["business"]->ruc;
        $data['nombre_comercial']   = $data["business"]->nombre_comercial;
        $nombre_excel               = 'Reporte de Gastos ' . date('d-m-Y H-i-s') . '.xlsx';
        $export_pdf                 = $request->input('export_pdf');
        if(!empty($export_pdf))
        {
            $pdf    = PDF::loadView('admin.reports.purchases.expenses.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Productos ' . date('d-m-Y H-i-s') . '.pdf');
        }
        else
        {
            return Excel::download(new ExpenseReportPurchase($data), $nombre_excel);
        }
    }
}

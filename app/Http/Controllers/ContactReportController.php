<?php

namespace App\Http\Controllers;

use App\Exports\ContactReportCustomer;
use App\Exports\ContactReportProvider;
use App\Models\Business;
use App\Models\Client;
use App\Models\Provider;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

class ContactReportController extends Controller
{
    public function customers()
    {
        return view('admin.reports.contacts.customers.home');
    }

    public function search_customers(Request $request)
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

        $clients            = Client::select('clients.*', 'identity_document_types.descripcion_documento as documento')
                            ->join('identity_document_types', 'clients.iddoc', 'identity_document_types.id')
                            ->orderBy('id', 'DESC')
                            ->get();
        $quantity           = count($clients);
        echo json_encode([
            "status"        => true,
            "clients"       => $clients,
            "quantity"      => $quantity
        ]);
    }

    public function export_customers(Request $request)
    {
        $data["clientes"]           = Client::select('clients.*', 'identity_document_types.descripcion_documento as documento')
                                    ->join('identity_document_types', 'clients.iddoc', 'identity_document_types.id')
                                    ->orderBy('id', 'DESC')
                                    ->get();
        $data["quantity"]           = count($data["clientes"]);
        $data["business"]           = Business::where('id', 1)->first();
        $data['ruc']                = $data["business"]->ruc;
        $data['nombre_comercial']   = $data["business"]->nombre_comercial;
        $nombre_excel               = 'Reporte de Clientes ' . date('d-m-Y H-i-s') . '.xlsx';
        $export_pdf                 = $request->input('export_pdf');
        if(!empty($export_pdf))
        {
            $pdf    = PDF::loadView('admin.reports.contacts.customers.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Clientes ' . date('d-m-Y H-i-s') . '.pdf');
        }
        else
        {
            return Excel::download(new ContactReportCustomer($data), $nombre_excel);
        }
    }

    ## Providers
    public function providers()
    {
        return view('admin.reports.contacts.providers.home');
    }

    public function search_providers(Request $request)
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

        $providers            = Provider::select('providers.*', 'identity_document_types.descripcion_documento as documento')
                            ->join('identity_document_types', 'providers.iddoc', 'identity_document_types.id')
                            ->orderBy('id', 'DESC')
                            ->get();
        $quantity           = count($providers);
        echo json_encode([
            "status"        => true,
            "providers"      => $providers,
            "quantity"      => $quantity
        ]);
    }

    public function export_providers(Request $request)
    {
        $data["providers"]           = Provider::select('providers.*', 'identity_document_types.descripcion_documento as documento')
                                    ->join('identity_document_types', 'providers.iddoc', 'identity_document_types.id')
                                    ->orderBy('id', 'DESC')
                                    ->get();
        $data["quantity"]           = count($data["providers"]);
        $data["business"]           = Business::where('id', 1)->first();
        $data['ruc']                = $data["business"]->ruc;
        $data['nombre_comercial']   = $data["business"]->nombre_comercial;
        $nombre_excel               = 'Reporte de Proveedores ' . date('d-m-Y H-i-s') . '.xlsx';
        $export_pdf                 = $request->input('export_pdf');
        if(!empty($export_pdf))
        {
            $pdf    = PDF::loadView('admin.reports.contacts.providers.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Proveedores ' . date('d-m-Y H-i-s') . '.pdf');
        }
        else
        {
            return Excel::download(new ContactReportProvider($data), $nombre_excel);
        }
    }
}

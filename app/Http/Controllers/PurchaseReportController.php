<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseReportGeneral;
use App\Exports\PurchaseReportProvider;
use App\Models\Business;
use App\Models\Buy;
use App\Models\Provider;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseReportController extends Controller
{
    public function purchases_provider()
    {
        $data['type_documents']   = TypeDocument::where('estado', 1)->limit(2)->get();
        $data['providers']        = Provider::get();
        return view('admin.reports.purchases.providers.home', $data);
    }

    public function search_purchases_provider(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $data["fecha_inicial"]      = $request->input('fecha_inicial');
        $data["fecha_final"]        = $request->input('fecha_final');
        $idtipo_documento           = $request->input('idtipo_documento');
        $provider                   = $request->input('provider');

        if ($idtipo_documento != '0')
            $buys   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                ->where('idtipo_comprobante', $idtipo_documento)
                ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])
                ->get();

        elseif ($provider != '0')
            $buys   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                ->where('idproveedor', $provider)
                ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])
                ->get();

        elseif ($idtipo_documento != '0' && $provider != '0')
            $buys   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                ->where('idtipo_comprobante', $idtipo_documento)
                ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                ->where('idproveedor', $provider)
                ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])
                ->get();

        else
            $buys   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])->get();


        $quantity           = count($buys);
        echo json_encode([
            "status"        => true,
            "buys"          => $buys,
            "quantity"      => $quantity
        ]);
    }

    public function export_purchases_provider(Request $request)
    {
        $data["fecha_inicial"]          = $request->input('fecha_inicial');
        $data["fecha_final"]            = $request->input('fecha_final');
        $data["idtipo_documento"]       = $request->input('idtipo_documento');
        $data["provider"]               = $request->input('provider');
        $data["business"]               = Business::where('id', 1)->first();
        $data['ruc']                    = $data["business"]->ruc;
        $data['nombre_comercial']       = $data["business"]->nombre_comercial;
        $data['total']                  = 0;
        $nombre_excel                   = 'Reporte de Compras por Proveedor ' . date('d-m-Y H-i-s') . '.xlsx';
        $export_pdf                     = $request->input('export_pdf');

        if ($data["idtipo_documento"] != '0')
            $data["buys"]   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                ->where('idtipo_comprobante', $data["idtipo_documento"])
                ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])
                ->get();

        elseif ($data["provider"] != '0')
            $data["buys"]   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                ->where('idproveedor', $data["provider"])
                ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])
                ->get();

        elseif ($data["idtipo_documento"] != '0' && $data["provider"] != '0')
            $data["buys"]   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                ->where('idtipo_comprobante', $data["idtipo_documento"])
                ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                ->where('idproveedor', $data["provider"])
                ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])
                ->get();

        else
            $data["buys"]   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])->get();

        $data["quantity"]           = count($data["buys"]);
        if(!empty($export_pdf))
        {
            $pdf    = PDF::loadView('admin.reports.purchases.providers.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Compras por Proveedor ' . date('d-m-Y H-i-s') . '.pdf');
        }
        else
        {
            return Excel::download(new PurchaseReportProvider($data), $nombre_excel);
        }
    }

    ##General
    public function purchases_general()
    {
        $data['type_documents']   = TypeDocument::where('estado', 1)->limit(2)->get();
        return view('admin.reports.purchases.general.home', $data);
    }

    public function search_purchases_general(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $data["fecha_inicial"]      = $request->input('fecha_inicial');
        $data["fecha_final"]        = $request->input('fecha_final');
        $idtipo_documento           = $request->input('idtipo_documento');

        switch ($idtipo_documento) {
            case '0':
                $buys   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                        ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                        ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])->get();
                break;
            
            default:
                $buys   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                        ->where('idtipo_comprobante', $idtipo_documento)
                        ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                        ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])
                        ->get();
                break;
        }

        $quantity           = count($buys);
        echo json_encode([
            "status"        => true,
            "buys"          => $buys,
            "quantity"      => $quantity
        ]);
    }

    public function export_purchases_general(Request $request)
    {
        $data["fecha_inicial"]          = $request->input('fecha_inicial');
        $data["fecha_final"]            = $request->input('fecha_final');
        $data["idtipo_documento"]       = $request->input('idtipo_documento');
        $data["provider"]               = $request->input('provider');
        $data["business"]               = Business::where('id', 1)->first();
        $data['ruc']                    = $data["business"]->ruc;
        $data['nombre_comercial']       = $data["business"]->nombre_comercial;
        $data['total']                  = 0;
        $nombre_excel                   = 'Reporte de Compras General ' . date('d-m-Y H-i-s') . '.xlsx';
        $export_pdf                     = $request->input('export_pdf');

        switch ($data["idtipo_documento"]) {
            case '0':
                $data["buys"]   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                                ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                                ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])->get();
                break;
            
            default:
                $data["buys"]   = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
                                ->where('idtipo_comprobante', $data["idtipo_documento"])
                                ->join('providers', 'buys.idproveedor', '=', 'providers.id')
                                ->whereBetween('fecha_emision', [$data['fecha_inicial'], $data['fecha_final']])
                                ->get();
                break;
        }

        $data["quantity"]           = count($data["buys"]);
        if(!empty($export_pdf))
        {
            $pdf    = PDF::loadView('admin.reports.purchases.general.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Compras General ' . date('d-m-Y H-i-s') . '.pdf');
        }
        else
        {
            return Excel::download(new PurchaseReportGeneral($data), $nombre_excel);
        }
    }
}

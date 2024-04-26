<?php

namespace App\Http\Controllers;

use App\Exports\SaleReportGeneral;
use App\Exports\SaleReportProduct;
use App\Exports\SaleReportSeller;
use App\Models\Billing;
use App\Models\Business;
use App\Models\Client;
use App\Models\TypeDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

class SaleReportController extends Controller
{
    public function sales_general()
    {
        $data["clients"]        = Client::get();
        $data["type_documents"] = TypeDocument::where('id', 1)->orWhere('id', 2)->orWhere('id', 7)->orWhere('id', 6)->get();
        return view('admin.reports.sales.general.home', $data);
    }

    public function search_sales_general(Request $request)
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
        $idclient           = $request->input('idclient');
        $data["pagos"]      = [];
        $data["total"]      = 0;
        $document           = $request->input('document');
        switch ($document) {
            case '0':
                switch ($idclient) {
                    case '0':
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM sale_notes 
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        break;

                    default:
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante != 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM sale_notes 
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE idcliente = $idclient
                                                AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        break;
                }
                break;

            case '1':
                switch ($idclient) {
                    case '0':
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '2':
                switch ($idclient) {
                    case '0':
                        $billings           = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '6':
                switch ($idclient) {
                    case '0':
                        $billings           = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                    clients.dni_ruc as dni_ruc, 
                                                    clients.nombres as nombre_cliente 
                                                    FROM billings 
                                                    INNER JOIN clients ON billings.idcliente = clients.id
                                                    WHERE idtipo_comprobante = 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                    ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                    clients.dni_ruc as dni_ruc, 
                                                    clients.nombres as  nombre_cliente 
                                                    FROM billings 
                                                    INNER JOIN clients ON billings.idcliente = clients.id
                                                    WHERE idcliente = $idclient AND idtipo_comprobante = 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                    ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '7':
                switch ($idclient) {
                    case '0':
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                        clients.nombres as  nombre_cliente 
                                        FROM sale_notes 
                                        INNER JOIN clients ON sale_notes.idcliente = clients.id
                                        WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                        ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta,
                                            clients.dni_ruc as dni_ruc, 
                                            clients.nombres as  nombre_cliente 
                                            FROM sale_notes 
                                            INNER JOIN clients ON sale_notes.idcliente = clients.id
                                            WHERE idcliente = $idclient AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                            ORDER BY id ASC");

                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;
        }

        $doc_relacionados           = [];
        foreach ($data["billings"] as $billing) {
            $data["total"]          += strval($billing["total"]);
            $idtipo_comprobante     = $billing["idtipo_comprobante"];
            $idfactura_anular       = $billing["idfactura_anular"];
            $idfactura              = $billing["id"];
            $idcaja                 = $billing["idcaja"];
            $data["pagos"][]        = DB::select("SELECT SUM(monto) as monto, idpago, idfactura, idtipo_comprobante, 
                                    pay_modes.descripcion as tipo_pago
                                    FROM detail_payments
                                    INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                                    WHERE idcaja = $idcaja AND idtipo_comprobante = $idtipo_comprobante AND idfactura = $idfactura
                                    GROUP BY idpago, pay_modes.descripcion, idfactura, idtipo_comprobante");
            if ($idtipo_comprobante != 7) {
                $doc_relacionados[] = Billing::where('id', $idfactura_anular)->first();
            } else {
                $doc_relacionados[] = null;
            }
        }
        $quantity           = count($data["billings"]);
        echo json_encode([
            "status"            => true,
            "billings"          => $data["billings"],
            "pagos"             => $data["pagos"],
            "quantity"          => $quantity,
            "total"             => $data["total"],
            'doc_relacionados'  => $doc_relacionados
        ]);
    }

    public function export_sales_general(Request $request)
    {
        $fecha_inicial              = $request->input('fecha_inicial');
        $fecha_final                = $request->input('fecha_final');
        $idclient                   = $request->input('idclient');
        $data["pagos"]              = [];
        $data["total"]              = 0;
        $data["anulado"]            = 0;
        $data["total_neto"]         = 0;
        $export_pdf                 = $request->input('export_pdf');
        $data["business"]           = Business::where('id', 1)->first();
        $data['ruc']                = $data["business"]->ruc;
        $data['nombre_comercial']   = $data["business"]->nombre_comercial;
        $document                   = $request->input('document');
        switch ($document) {
            case '0':
                switch ($idclient) {
                    case '0':
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM sale_notes 
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        break;

                    default:
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante != 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM sale_notes 
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE idcliente = $idclient
                                                AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        break;
                }
                break;

            case '1':
                switch ($idclient) {
                    case '0':
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '2':
                switch ($idclient) {
                    case '0':
                        $billings           = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '6':
                switch ($idclient) {
                    case '0':
                        $billings           = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                        clients.dni_ruc as dni_ruc, 
                                                        clients.nombres as nombre_cliente 
                                                        FROM billings 
                                                        INNER JOIN clients ON billings.idcliente = clients.id
                                                        WHERE idtipo_comprobante = 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                        ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                        clients.dni_ruc as dni_ruc, 
                                                        clients.nombres as  nombre_cliente 
                                                        FROM billings 
                                                        INNER JOIN clients ON billings.idcliente = clients.id
                                                        WHERE idcliente = $idclient AND idtipo_comprobante = 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                        ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '7':
                switch ($idclient) {
                    case '0':
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                        clients.nombres as  nombre_cliente 
                                        FROM sale_notes 
                                        INNER JOIN clients ON sale_notes.idcliente = clients.id
                                        WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                        ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta,
                                            clients.dni_ruc as dni_ruc, 
                                            clients.nombres as  nombre_cliente 
                                            FROM sale_notes 
                                            INNER JOIN clients ON sale_notes.idcliente = clients.id
                                            WHERE idcliente = $idclient AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                            ORDER BY id ASC");

                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;
        }
        
        $data["doc_relacionados"]           = [];
        foreach ($data["billings"] as $billing) {
            if ($billing["estado_venta"] == 2)
                $data["anulado"]    += $billing["total"];

            if ($billing["idtipo_comprobante"] != 6)
                $data["total"]    += $billing["total"];

            $data["total_neto"]     = $data["total"] - $data["anulado"];
            $idtipo_comprobante     = $billing["idtipo_comprobante"];
            $idfactura_anular       = $billing["idfactura_anular"];
            $idfactura              = $billing["id"];
            $idcaja                 = $billing["idcaja"];
            $data["pagos"][]        = DB::select("SELECT SUM(monto) as monto, idpago, idfactura, idtipo_comprobante, 
                                    pay_modes.descripcion as tipo_pago
                                    FROM detail_payments
                                    INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                                    WHERE idcaja = $idcaja AND idtipo_comprobante = $idtipo_comprobante AND idfactura = $idfactura
                                    GROUP BY idpago, pay_modes.descripcion, idfactura, idtipo_comprobante");

            if($idtipo_comprobante != 7) {
                $data["doc_relacionados"][] = Billing::where('id', $idfactura_anular)->first();
            } else {
                $data["doc_relacionados"][] = null;
            }
        }
        $data["quantity"]           = count($data["billings"]);
        $data["fecha_inicial"]      = $fecha_inicial;
        $data["fecha_final"]        = $fecha_final;
        $nombre_excel               = 'Reporte de Ventas General ' . date('d-m-Y H-i-s') . '.xlsx';
        if (!empty($export_pdf)) {
            $pdf    = PDF::loadView('admin.reports.sales.general.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Ventas General ' . date('d-m-Y H-i-s') . '.pdf');
        } else {
            return Excel::download(new SaleReportGeneral($data), $nombre_excel);
        }
    }

    ## Seller
    public function sales_seller()
    {
        $data["sellers"]        = User::get();
        $data["type_documents"] = TypeDocument::where('id', 1)->orWhere('id', 2)->orWhere('id', 7)->get();
        return view('admin.reports.sales.sellers.home', $data);
    }

    public function search_sales_seller(Request $request)
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
        $iduser             = $request->input('iduser');
        $data["pagos"]      = [];
        $data["total"]      = 0;
        $document           = $request->input('document');

        switch ($document) {
            case '0':
                switch ($iduser) {
                    case '0':
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante != 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM sale_notes 
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        break;

                    default:
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idusuario = $iduser AND idtipo_comprobante != 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM sale_notes 
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE idusuario = $iduser
                                                AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        break;
                }
                break;

            case '1':
                switch ($iduser) {
                    case '0':
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idusuario = $iduser AND idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '2':
                switch ($iduser) {
                    case '0':
                        $billings           = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idusuario = $iduser AND idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '7':
                switch ($iduser) {
                    case '0':
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                        clients.nombres as  nombre_cliente 
                                        FROM sale_notes 
                                        INNER JOIN clients ON sale_notes.idcliente = clients.id
                                        WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                        ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta,
                                            clients.dni_ruc as dni_ruc, 
                                            clients.nombres as  nombre_cliente 
                                            FROM sale_notes 
                                            INNER JOIN clients ON sale_notes.idcliente = clients.id
                                            WHERE idusuario = $iduser AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                            ORDER BY id ASC");

                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;
        }

        foreach ($data["billings"] as $billing) {
            $data["total"]          += strval($billing["total"]);
            $idtipo_comprobante     = $billing["idtipo_comprobante"];
            $idfactura              = $billing["id"];
            $idcaja                 = $billing["idcaja"];
            $data["pagos"][]        = DB::select("SELECT SUM(monto) as monto, idpago, idfactura, idtipo_comprobante, 
                                    pay_modes.descripcion as tipo_pago
                                    FROM detail_payments
                                    INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                                    WHERE idcaja = $idcaja AND idtipo_comprobante = $idtipo_comprobante AND idfactura = $idfactura
                                    GROUP BY idpago, pay_modes.descripcion, idfactura, idtipo_comprobante");
        }
        $quantity           = count($data["billings"]);
        echo json_encode([
            "status"        => true,
            "billings"      => $data["billings"],
            "pagos"         => $data["pagos"],
            "quantity"      => $quantity,
            "total"         => $data["total"]
        ]);
    }

    public function export_sales_seller(Request $request)
    {
        $fecha_inicial              = $request->input('fecha_inicial');
        $fecha_final                = $request->input('fecha_final');
        $iduser                     = $request->input('iduser');
        $data["pagos"]              = [];
        $data["total"]              = 0;
        $data["anulado"]            = 0;
        $data["total_neto"]         = 0;
        $export_pdf                 = $request->input('export_pdf');
        $data["business"]           = Business::where('id', 1)->first();
        $data['ruc']                = $data["business"]->ruc;
        $data['nombre_comercial']   = $data["business"]->nombre_comercial;
        $document                   = $request->input('document');
        switch ($document) {
            case '0':
                switch ($iduser) {
                    case '0':
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante != 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM sale_notes 
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        $data["seller"]         = "TODOS";
                        break;

                    default:
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idusuario = $iduser AND idtipo_comprobante != 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM sale_notes 
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE idusuario = $iduser
                                                AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        $data["seller"]         = mb_strtoupper(User::where('id', $iduser)->first()["nombres"]);
                        break;
                }
                break;

            case '1':
                switch ($iduser) {
                    case '0':
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        $data["seller"]     = "TODOS";
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idusuario = $iduser AND idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        $data["seller"]     = mb_strtoupper(User::where('id', $iduser)->first()["nombres"]);
                        break;
                }
                break;

            case '2':
                switch ($iduser) {
                    case '0':
                        $billings           = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        $data["seller"]     = "TODOS";
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc, 
                                                clients.nombres as  nombre_cliente 
                                                FROM billings 
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idusuario = $iduser AND idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        $data["seller"]     = mb_strtoupper(User::where('id', $iduser)->first()["nombres"]);
                        break;
                }
                break;

            case '7':
                switch ($iduser) {
                    case '0':
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc, 
                                        clients.nombres as  nombre_cliente 
                                        FROM sale_notes 
                                        INNER JOIN clients ON sale_notes.idcliente = clients.id
                                        WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                        ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        $data["seller"]     = "TODOS";
                        break;

                    default:
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta,
                                            clients.dni_ruc as dni_ruc, 
                                            clients.nombres as  nombre_cliente 
                                            FROM sale_notes 
                                            INNER JOIN clients ON sale_notes.idcliente = clients.id
                                            WHERE idusuario = $iduser AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                            ORDER BY id ASC");

                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        $data["seller"]     = mb_strtoupper(User::where('id', $iduser)->first()["nombres"]);
                        break;
                }
                break;
        }
        //
        foreach ($data["billings"] as $billing) {
            if ($billing["estado_venta"] == 2)
                $data["anulado"]    += $billing["total"];

            if ($billing["idtipo_comprobante"] != 6)
                $data["total"]    += $billing["total"];

            $data["total_neto"]     = $data["total"] - $data["anulado"];
            $idtipo_comprobante     = $billing["idtipo_comprobante"];
            $idfactura              = $billing["id"];
            $idcaja                 = $billing["idcaja"];
            $data["pagos"][]        = DB::select("SELECT SUM(monto) as monto, idpago, idfactura, idtipo_comprobante, 
                                    pay_modes.descripcion as tipo_pago
                                    FROM detail_payments
                                    INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                                    WHERE idcaja = $idcaja AND idtipo_comprobante = $idtipo_comprobante AND idfactura = $idfactura
                                    GROUP BY idpago, pay_modes.descripcion, idfactura, idtipo_comprobante");
        }
        $data["quantity"]           = count($data["billings"]);
        $data["fecha_inicial"]      = $fecha_inicial;
        $data["fecha_final"]        = $fecha_final;

        $nombre_excel               = 'Reporte de Ventas General ' . date('d-m-Y H-i-s') . '.xlsx';
        if (!empty($export_pdf)) {
            $pdf    = PDF::loadView('admin.reports.sales.sellers.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Ventas por Vendedor ' . date('d-m-Y H-i-s') . '.pdf');
        } else {
            return Excel::download(new SaleReportSeller($data), $nombre_excel);
        }
    }

    ## Products Seller
    public function sales_product()
    {
        return view('admin.reports.sales.products.home');
    }

    public function search_sales_product(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $s_products                 = DB::select("SELECT detail_billings.idproducto, products.codigo_interno as codigo,
                                        products.descripcion as producto, SUM(detail_billings.precio_total) as precio_total,
                                        SUM(detail_billings.cantidad ) AS cantidad
                                        FROM detail_billings
                                        INNER JOIN products ON detail_billings.idproducto = products.id
                                        GROUP BY detail_billings.idproducto, products.descripcion, products.codigo_interno
                                        ORDER BY SUM(detail_billings.cantidad ) DESC");

        $nv_products                = DB::select("SELECT detail_sale_notes.idproducto, products.codigo_interno as codigo,
                                        products.descripcion as producto, SUM(detail_sale_notes.precio_total) as precio_total,
                                        SUM(detail_sale_notes.cantidad ) AS cantidad
                                        FROM detail_sale_notes
                                        INNER JOIN products ON detail_sale_notes.idproducto = products.id
                                        GROUP BY detail_sale_notes.idproducto, products.descripcion, products.codigo_interno
                                        ORDER BY SUM(detail_sale_notes.cantidad ) DESC");

        $s_products                 = json_encode($s_products);
        $nv_products                = json_encode($nv_products);
        $sales_products             = array_merge(json_decode($s_products, true), json_decode($nv_products, true));
        $products__                 = [];
        $ids_products__             = [];

        foreach ($sales_products as $arr_product) {
            $id_product = $arr_product["idproducto"];
            if (!in_array($id_product, $ids_products__)) {
                $ids_products__[] = $id_product;
            }
        }
        $products = [];
        foreach ($ids_products__ as $unique_id) {
            $temp     = [];
            $quantity = 0;
            foreach ($sales_products as $arr_product) {
                $id = $arr_product["idproducto"];
                if ($id === $unique_id) {
                    $temp[] = $arr_product;
                }
            }

            $product = $temp[0];
            $product["cantidad"] = 0;
            foreach ($temp as $product_temp) {
                $product["cantidad"]        = $product["cantidad"] + $product_temp["cantidad"];
                $product["precio_total"]    = $product_temp["precio_total"];
                $product["codigo"]          = $product["codigo"];
            }
            $products[] = $product;
        }

        $quantity           = count($products);
        echo json_encode([
            "status"        => true,
            "products"      => $products,
            "quantity"      => $quantity
        ]);
    }

    public function export_sales_product(Request $request)
    {
        $s_products                 = DB::select("SELECT detail_billings.idproducto, products.codigo_interno as codigo,
                                        products.descripcion as producto, SUM(detail_billings.precio_total) as precio_total,
                                        SUM(detail_billings.cantidad ) AS cantidad
                                        FROM detail_billings
                                        INNER JOIN products ON detail_billings.idproducto = products.id
                                        GROUP BY detail_billings.idproducto, products.descripcion, products.codigo_interno
                                        ORDER BY SUM(detail_billings.cantidad ) DESC");

        $nv_products                = DB::select("SELECT detail_sale_notes.idproducto, products.codigo_interno as codigo,
                                        products.descripcion as producto, SUM(detail_sale_notes.precio_total) as precio_total,
                                        SUM(detail_sale_notes.cantidad ) AS cantidad
                                        FROM detail_sale_notes
                                        INNER JOIN products ON detail_sale_notes.idproducto = products.id
                                        GROUP BY detail_sale_notes.idproducto, products.descripcion, products.codigo_interno
                                        ORDER BY SUM(detail_sale_notes.cantidad ) DESC");

        $s_products                 = json_encode($s_products);
        $nv_products                = json_encode($nv_products);
        $sales_products             = array_merge(json_decode($s_products, true), json_decode($nv_products, true));
        $products__                 = [];
        $ids_products__             = [];

        foreach ($sales_products as $arr_product) {
            $id_product = $arr_product["idproducto"];
            if (!in_array($id_product, $ids_products__)) {
                $ids_products__[] = $id_product;
            }
        }
        $products = [];
        foreach ($ids_products__ as $unique_id) {
            $temp     = [];
            $quantity = 0;
            foreach ($sales_products as $arr_product) {
                $id = $arr_product["idproducto"];
                if ($id === $unique_id) {
                    $temp[] = $arr_product;
                }
            }

            $product = $temp[0];
            $product["cantidad"] = 0;
            foreach ($temp as $product_temp) {
                $product["cantidad"]        = $product["cantidad"] + $product_temp["cantidad"];
                $product["precio_total"]    = $product_temp["precio_total"];
                $product["codigo"]          = $product["codigo"];
            }
            $products[] = $product;
        }
        $data["products"]           = $products;
        $data["quantity"]           = count($data["products"]);
        $data["business"]           = Business::where('id', 1)->first();
        $data['ruc']                = $data["business"]->ruc;
        $data['nombre_comercial']   = $data["business"]->nombre_comercial;
        $nombre_excel               = 'Reporte de Prod. más vendidos ' . date('d-m-Y H-i-s') . '.xlsx';
        $export_pdf                 = $request->input('export_pdf');
        if (!empty($export_pdf)) {
            $pdf    = PDF::loadView('admin.reports.sales.products.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Prod. más vendidos ' . date('d-m-Y H-i-s') . '.pdf');
        } else {
            return Excel::download(new SaleReportProduct($data), $nombre_excel);
        }
    }
}

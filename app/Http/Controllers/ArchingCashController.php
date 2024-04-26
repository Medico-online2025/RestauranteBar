<?php

namespace App\Http\Controllers;

use App\Models\ArchingCash;
use App\Models\Bill;
use App\Models\Billing;
use App\Models\Business;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArchingCashController extends Controller
{
    public function index()
    {
        return view('admin.arching_cashes.list');
    }

    public function get()
    {
        $arching_cashes     = ArchingCash::select('arching_cashes.*', 'users.user as usuario')
                            ->join('users', 'arching_cashes.idusuario', '=' ,'users.id')
                            ->orderBy('id', 'DESC')
                            ->get();

        return Datatables()
                    ->of($arching_cashes)
                    ->addColumn('fecha', function($arching_cashes){
                        $fecha = date('d-m-Y', strtotime($arching_cashes->fecha_fin));
                        return $fecha;
                    })
                    ->addColumn('cajero', function($arching_cashes){
                        $cajero = mb_strtoupper($arching_cashes->usuario);
                        return $cajero;
                    })
                    ->addColumn('estado', function($arching_cashes){
                        $estado    = $arching_cashes->estado;
                        $btn        = '';
                        switch($estado)
                        {
                            case '1':
                                $btn .= '<span class="badge bg-success text-white">ABIERTO</span>';
                                break;

                            case '2':
                                $btn .= '<span class="badge bg-danger text-white">CERRADO</span>';
                                break;
                        }
                        return $btn;
                    })
                    ->addColumn('acciones', function($arching_cashes){
                        $id     = $arching_cashes->id;
                        $btn    = '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                    <div class="dropdown-menu">
                                    <a class="dropdown-item btn-detail-cash" data-id="'.$id.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                        <span> Ver Detalle</span>
                                    </a>
                                    <a class="dropdown-item btn-summary" data-id="'.$id.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                                        <span> Ver Resumen</span>
                                    </a>
                                    <a class="dropdown-item btn-download" data-id="'.$id.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        <span>Descargar Reporte</span>
                                    </a>
                                    <a class="dropdown-item btn-confirm" data-id="'.$id.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                        <span>Cerrar Caja</span>
                                    </a>
                                    </div>
                                </div>';
                        return $btn;
                    })
                    ->rawColumns(['fecha', 'cajero', 'estado', 'acciones'])
                    ->make(true);   
    }

    public function save(Request $request)
    {
        if(!$request->ajax())
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idcaja         = $request->input('idcaja');
        $idusuario      = $request->input('idusuario');
        $fecha_inicio   = date('Y-m-d');
        $fecha_fin      = date('Y-m-d');
        $monto_inicial  = $request->input('monto_inicial');
        $monto_final    = $request->input('monto_inicial');
        $total_ventas   = 0;
        $estado         = 1;

        $buscar_caja    = count(ArchingCash::where('idcaja', $idcaja)->where('idusuario', $idusuario)->where('estado', 1)->get());
        if($buscar_caja >= 1)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe cerrar caja actual',
                'type'      => 'warning'
            ]);
            return;
        }

        ArchingCash::insert([
            'idcaja'        => $idcaja,
            'idusuario'     => $idusuario,
            'fecha_inicio'  => $fecha_inicio,
            'fecha_fin'     => $fecha_fin,
            'monto_inicial' => $monto_inicial,
            'monto_final'   => $monto_final,
            'total_ventas'  => $total_ventas,
            'estado'        => $estado
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Caja aperturada correctamente',
            'type'      => 'success'
        ]);
    }

    public function close(Request $request)
    {
        if(!$request->ajax())
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = trim($request->input('id'));
        $cash               = ArchingCash::where('id', $id)->first();

        if($cash->idusuario != Auth::user()['id'])
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Esta apertura de caja no le pertenece',
                'type'      => 'warning'
            ]);
            return;
        }

        if($cash->estado == 2)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'La caja está cerrada',
                'type'      => 'warning'
            ]);
            return;
        }

        $monto_final        = Billing::where('idcaja', $id)->where('estado_cpe', 1)->where('idtipo_comprobante', '!=', 6)->where('idusuario', $cash->idusuario)->sum('total');
         
        ArchingCash::where('id', $id)->update([
            'estado'        => 2,
            'fecha_fin'     => date('Y-m-d'),
            'monto_final'   => ($monto_final + $cash->monto_inicial)
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Caja cerrada correctamente',
            'type'      => 'success'
        ]);
    }

    public function get_detail_cash(Request $request)
    {
        if(!$request->ajax())
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id         = (int) $request->input('id');
        echo json_encode([
            'status' => true,
            'id'     => $id
        ]);
    }

    public function get_detail_cashes(Request $request)
    {
        if(!$request->ajax())
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }
        $id             = $request->input('id');
        $b_f            = DB::select("CALL get_list_billings_cash(?)", [$id]);
        $n_v            = DB::select("CALL get_list_sale_notes_cash(?)", [$id]);
        $b_f            = json_encode($b_f);
        $n_v            = json_encode($n_v);
        $billings       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
        return Datatables()
                        ->of($billings)
                        ->addColumn('cliente', function($billings){
                            $cliente  = $billings["nombre_cliente"];
                            return $cliente;
                        })
                        ->addColumn('documento', function($billings){
                            $documento  = $billings["serie"] . '-' . $billings["correlativo"];
                            return $documento;
                        })
                        ->addColumn('fecha', function($billings){
                            $fecha_emision = date('d-m-Y', strtotime($billings["fecha_emision"]));
                            return $fecha_emision;
                        })
                        ->rawColumns(['fecha', 'documento', 'cliente'])
                        ->make(true);
    }

    public function get_summary(Request $request)
    {
        if(!$request->ajax())
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }
        $id                 = $request->input('id');
        $cash               = ArchingCash::where('id', $id)->first();
        $monto_inicial      = $cash->monto_inicial;
        $b_f                = DB::select("CALL get_list_billings_cash(?)", [$id]);
        $n_v                = DB::select("CALL get_list_sale_notes_cash(?)", [$id]);
        $b_f                = json_encode($b_f);
        $n_v                = json_encode($n_v);
        $billings           = array_merge(json_decode($b_f, true), json_decode($n_v, true));
        $cantidad_ventas    = count($billings);
        $monto_ventas       = 0;
        $idusuario          = $cash->idusuario;
        $idcaja             = $cash->idcaja;
        $idarqueocaja       = $cash->id;
        foreach($billings as $billing)
        {
            $monto_ventas += $billing["total"];
        }

        // Bills
        $bills              = DB::select("SELECT sum(monto) as monto, idpurchase_description, 
                            purchase_descriptions.descripcion as gasto
                            FROM bills
                            INNER JOIN purchase_descriptions ON bills.idpurchase_description = purchase_descriptions.id
                            WHERE idcaja = $idcaja AND idusuario = $idusuario AND idarqueocaja = $idarqueocaja
                            GROUP BY idpurchase_description, purchase_descriptions.descripcion");

        $sum_bills          = Bill::where('idcaja', $idcaja)->where('idusuario', $idusuario)->where('idarqueocaja', $idarqueocaja)->sum('monto');
        $html_bills         = '';
        $bills_empty        = null;
        if(count($bills) == 0)
        {
            $html_bills     .= 'S/' . number_format($sum_bills, 2, ".", "");
            $bills_empty    = true;
        }
        else
        {
            foreach($bills as $bill)
            {
                $html_bills .= '<div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0"></p>
                                <h6 class="mb-0"><span style="font-size: 13px;">'. $bill->gasto .': </span><span>S/'. number_format($bill->monto, 2, ".", "") .'</span></h6>
                                </div>';
            }
            $bills_empty    = false;
        }
        // Sales
        $sales              = DB::select("SELECT SUM(monto) as monto, idpago, pay_modes.descripcion as tipo_pago
                            FROM detail_payments
                            INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                            WHERE idcaja = $idcaja AND idtipo_comprobante != 6 AND estado = 1
                            GROUP BY idpago, pay_modes.descripcion");

        $html_sales         = '';
        $sales_empty        = null;
        if(count($sales) == 0)
        {
            $html_sales     .= 'S/0.00';
            $sales_empty    = true;
        }
        else
        {
            foreach($sales as $sale)
            {
                $html_sales .= '<div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0"></p>
                                <h6 class="mb-0"><span style="font-size: 13px;">'. $sale->tipo_pago .': </span><span>S/'. number_format($sale->monto, 2, ".", "") .'</span></h6>
                                </div>';
            }
            $sales_empty    = false;
        }

        $total              = number_format(($monto_ventas) + ($monto_inicial - $sum_bills), 2, ".", "");
        echo json_encode([
            'status'            => true,
            'monto_inicial'     => $monto_inicial,
            'cantidad_ventas'   => $cantidad_ventas,
            'suma_gastos'       => $sum_bills,
            'html_bills'        => $html_bills,
            'bills_empty'       => $bills_empty,
            'html_sales'        => $html_sales,
            'sales_empty'       => $sales_empty,
            'monto_ventas'      => number_format($monto_ventas, 2, ".", ""),
            'total'             => $total
        ]);
    }

    public function download($id)
    {
        $b_f                    = DB::select("CALL get_list_billings_cash(?)", [$id]);
        $n_v                    = DB::select("CALL get_list_sale_notes_cash(?)", [$id]);
        $b_f                    = json_encode($b_f);
        $n_v                    = json_encode($n_v);
        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
        $data["monto_ventas"]   = 0;
        $data["pagos"]          = [];
        foreach($data["billings"] as $billing)
        {
            $data["monto_ventas"]  += $billing["total"];
            $idtipo_comprobante     = $billing["idtipo_comprobante"];
            $idfactura              = $billing["id"];
            $data["pagos"][]        = DB::select("SELECT SUM(monto) as monto, idpago, idfactura, idtipo_comprobante, 
                                    pay_modes.descripcion as tipo_pago
                                    FROM detail_payments
                                    INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                                    WHERE idcaja = $id AND idtipo_comprobante = $idtipo_comprobante AND idfactura = $idfactura
                                    GROUP BY idpago, pay_modes.descripcion, idfactura, idtipo_comprobante");
        }

        $data["cash"]           = ArchingCash::where('id', $id)->first();
        $data["sum_bills"]      = Bill::where('idcaja', $data["cash"]->idcaja)->where('idusuario', $data["cash"]->idusuario)->where('idarqueocaja', $id)->sum('monto');
        $data["total"]          = number_format(($data["monto_ventas"]) + ($data["cash"]->monto_inicial - $data["sum_bills"]), 2, ".", "");
        $data["business"]       = Business::where('id', 1)->first();
        $data["name"]           = $data["business"]->ruc . '-' . $data["cash"]->fecha_inicio;
        $pdf                    = PDF::loadView('admin.arching_cashes.report', $data)->setPaper('A4', 'landscape');
        return $pdf->download($data["name"] . '.pdf');
    }
}

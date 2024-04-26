<?php

namespace App\Http\Controllers;

use App\Models\ArchingCash;
use App\Models\Bill;
use App\Models\PurchaseDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function index()
    {
        $data['purchase_descriptions']   = PurchaseDescription::all();
        return view('admin.bills.list', $data);
    }

    public function get()
    {
        $bills     = Bill::select('bills.*', 'purchase_descriptions.descripcion as descripcion_compra', 'users.nombres as usuario')
                    ->join('purchase_descriptions', 'bills.idpurchase_description', 'purchase_descriptions.id')
                    ->join('users', 'bills.idusuario', 'users.id')
                    ->orderBy('id', 'DESC')
                    ->get();

        return Datatables()
                    ->of($bills)
                    ->addColumn('fecha', function($bills){
                        $fecha  = $bills->fecha_emision;
                        return date('d-m-Y', strtotime($fecha));
                    })
                    ->addColumn('acciones', function($bills){
                        $id     = $bills->id;
                        $btn    = '<div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item btn-confirm" data-id="'.$id.'" href="javascript:void(0);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50 menu-icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                <span> Eliminar</span>
                                            </a>
                                        </div>
                                    </div>';
                        return $btn;
                    })
                    ->rawColumns(['fecha','acciones'])
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

        $fecha_emision              = $request->input('fecha_emision');
        $idpurchase_description     = $request->input('idpurchase_description');
        $cuenta                     = "Cuenta General";
        $monto                      = $request->input('monto');
        $detalle                    = $request->input('detalle');
        $idusuario                  = Auth::user()['id'];
        $idcaja                     = Auth::user()['idcaja'];
        $idarqueocaja               = ArchingCash::where('idcaja', $idcaja)->where('idusuario', $idusuario)->where('estado', 1)->first()->id;

        Bill::insert([
            'fecha_emision'         => $fecha_emision,
            'idpurchase_description'=> $idpurchase_description,
            'cuenta'                => $cuenta,
            'monto'                 => $monto,
            'detalle'               => trim($detalle),
            'idusuario'             => $idusuario,
            'idcaja'                => $idcaja,
            'idarqueocaja'          => $idarqueocaja
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro insertado correctamente',
            'type'      => 'success'
        ]);
    }

    public function delete(Request $request)
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

        $id            = $request->input('id');
        Bill::where('id', $id)->delete();

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado correctamente',
            'type'      => 'success'
        ]);
    }
}

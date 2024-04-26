<?php

namespace App\Http\Controllers;

use App\Events\NewOrderEvent;
use App\Events\UpdateOrderEvent;
use App\Models\ArchingCash;
use App\Models\Billing;
use App\Models\Business;
use App\Models\Client;
use App\Models\Currency;
use App\Models\DetailBilling;
use App\Models\DetailOrder;
use App\Models\DetailOrderUpdate;
use App\Models\DetailPayment;
use App\Models\DetailSaleNote;
use App\Models\IdentityDocumentType;
use App\Models\IgvTypeAffection;
use App\Models\Order;
use App\Models\PayMode;
use App\Models\Product;
use App\Models\Room;
use App\Models\SaleNote;
use App\Models\Serie;
use App\Models\Table;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $data['type_inafects']      = IgvTypeAffection::where('estado', 1)->get();
        $data['type_documents']     = IdentityDocumentType::where('estado', 1)->get();
        $data['clients']            = Client::where('iddoc', 1)->orWhere('iddoc', 2)->get();
        $data['modo_pagos']         = PayMode::get();
        return view('admin.orders.list', $data);
    }

    public function get()
    {
        $orders         = DB::select("CALL get_list_orders()");
        return Datatables()
            ->of($orders)
            ->addColumn('fecha', function ($orders) {
                $fecha = date('d-m-Y', strtotime($orders->fecha));
                return $fecha . ' ' . $orders->hora;
            })
            ->addColumn('estado', function ($orders) {
                $estado    = $orders->estado;
                $btn    = '';
                switch ($estado) {
                    case '0':
                        $btn .= '<span class="badge bg-warning text-white">Por pagar</span>';
                        break;

                    case '1':
                        $btn .= '<span class="badge bg-success text-white">Pagado</span>';
                        break;

                    case '2':
                        $btn .= '<span class="badge bg-danger text-white">Anulado</span>';
                        break;
                }
                return $btn;
            })
            ->addColumn('acciones', function ($orders) {
                $id                 = $orders->id;
                $idtipo_documento   = $orders->idtipo_documento;
                $idventa            = $orders->idventa;
                $estado             = $orders->estado;
                $idmesa             = $orders->idmesa;
                $disabled           = ($estado == 2) ? 'disabled' : '';
                $btn    = '';
                $btn    = '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow ' . $disabled . '" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                    <div class="dropdown-menu">';
                if ($estado == 0) {
                    $btn .= '<a class="dropdown-item" href="' . route('admin.register_order', $idmesa) . '" data-id="' . $id . '"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye mr-50 menu-icon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        <span>Ver Pedido</span></a>
                                        <a class="dropdown-item btn-print-command" href="javascript:void(0);" data-id="' . $id . '"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-minus mr-50 menu-icon"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                        <span>Imprimir Comanda</span></a>
                                        <a class="dropdown-item btn-print-account" href="javascript:void(0);" data-id="' . $id . '"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer mr-50 menu-icon"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                        <span>Pre Cuenta</span></a>
                                        <a class="dropdown-item btn-confirm" data-id="' . $id . '" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-slash mr-50 menu-icon"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                        <span>Anular Pedido</span>
                                        </a>
                                        <a class="dropdown-item btn-gen" href="javascript:void(0);" data-id="' . $id . '"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save mr-50 menu-icon"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                        <span>Generar Comprobante</span></a>';
                } elseif ($estado == 1) {
                    $btn .= '<a class="dropdown-item btn-print-account" href="javascript:void(0);" data-id="' . $id . '"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer mr-50 menu-icon"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                        <span>Pre Cuenta</span></a>
                                        <a class="dropdown-item btn-open-whatsapp" data-idventa="' . $idventa . '" data-idtipo_documento="' . $idtipo_documento . '" data-id="' . $id . '" href="javascript:void(0);">
                                            <i class="fa-brands fa-whatsapp" style="margin-right: 0.5rem;"></i>
                                            <span> Enviar Documento</span>';
                }
                $btn .= '</div>
                                </div>';
                return $btn;
            })
            ->rawColumns(['fecha', 'estado', 'acciones'])
            ->make(true);
    }

    public function print_command(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = $request->input('id');
        $order              = Order::where('id', $id)->first();

        if (empty($order->ticket_comanda)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Genere nuevamente la comanda',
                'type'      => 'warning'
            ]);
            return;
        }

        echo json_encode([
            'status'        => true,
            'pdf'           => $order->ticket_comanda
        ]);
    }

    public function print_pre_account(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = $request->input('id');
        $order              = Order::where('id', $id)->first();
        if (empty($order->ticket_pre_cuenta))
            $data["name"]       = uniqid() . '.pdf';
        else
            $data["name"]       = $order->ticket_pre_cuenta;


        $data['order']      = Order::select('orders.*', 'users.user as mesero', 'tables.descripcion as mesa')
            ->join('users', 'orders.idusuario', 'users.id')
            ->join('tables', 'orders.idmesa', 'tables.id')
            ->where('orders.id', $id)
            ->first();

        $data['business']   = Business::where('id', 1)->first();
        $data['ubigeo']     = $this->get_ubigeo($data['business']->ubigeo);

        $data['detalle']    = DetailOrder::select(
            'detail_orders.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad'
        )
            ->join('products', 'detail_orders.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('detail_orders.idorden', $data["order"]->id)
            ->get();

        $customPaper        = array(0, 0, 300.00, 210.00);
        $pdf                = PDF::loadView('admin.orders.register.pre_account', $data)->setPaper($customPaper, 'landscape');
        $pdf->save(public_path('files/orders/pre-accounts/' .  $data["name"]));
        Order::where('id', $id)->update([
            'ticket_pre_cuenta' =>  $data["name"]
        ]);

        echo json_encode([
            'status'        => true,
            'pdf'           => $data["name"]
        ]);
    }

    public function anulled(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = (int) $request->input('id');
        $order              = Order::where('id', $id)->first();
        if (!file_exists(public_path('files/orders/pre-accounts/' . $order->ticket_pre_cuenta)) || !empty($order->ticket_pre_cuenta)) {
            unlink(public_path("files/orders/pre-accounts/" . $order->ticket_pre_cuenta));
        }

        if (!file_exists(public_path('files/orders/commands/' . $order->ticket_comanda)) || !empty($order->ticket_comanda)) {
            unlink(public_path("files/orders/commands/" . $order->ticket_comanda));
        }

        DetailOrder::where('idorden', $id)->delete();
        Order::where('id', $id)->update([
            'estado'        => 2
        ]);

        Table::where('id', $order->idmesa)->update([
            'estado'            => 1,
            'idorden'           => NULL
        ]);

        event(new UpdateOrderEvent);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Pedido anulado correctamente',
            'type'      => 'success'
        ]);
    }

    public function create()
    {
        $data['rooms']      = Room::get();
        return view('admin.orders.create.home', $data);
    }

    public function load_tables(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $tables             = Table::get();
        $rooms              = Room::get();
        $html_tables        = '';
        foreach ($rooms as $room) {
            $active = ($room["id"] == 1) ? 'active show' : '';
            $html_tables .= '<div class="tab-pane fade ' . $active . '" id="navs-pills-room-' . $room["id"] . '" role="tabpanel" style="height: calc(100% - 40px);">
                          <div class="row">';
            foreach ($tables as $table) {
                $class_status      = ($table["estado"] == 1) ? 'success' : 'warning';
                $text_status       = ($table["estado"] == 1) ? 'DISPONIBLE' : 'OCUPADO';
                $root_img          = ($table["estado"] == 1) ? 'assets/img/elements/utensils_success.png' : 'assets/img/elements/utensils_pending.png';
                if ($table["idsala"] == $room["id"]) {
                    $html_tables .= '<div class="col-xl-2 col-lg-3 col-md-3 pb-3">
                                                    <div class="card px-0 pt-3 h-100 shadow-none border">
                                                        <div class="rounded-2 text-center mb-2">
                                                        <a href="' . route("admin.register_order", $table["id"]) . '"><img class="img-fluid" src="' . asset("$root_img") . '" alt="Image utensils"></a>
                                                        </div>
                                                        <div class="card-body py-0 pt-2">
                                                        <div class="d-flex justify-content-center align-items-center mb-0">
                                                            <span class="badge bg-label-' . $class_status . '">' . $text_status . '</span>
                                                        </div>

                                                        <div class="d-flex justify-content-center align-items-center mb-0">
                                                            <a class="h5 mt-2" href="' . route("admin.register_order", $table["id"]) . '">' . $table["descripcion"] . '</a>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>';
                }
            }
            $html_tables .= '</div></div>';
        }

        echo json_encode([
            'status'    => true,
            'html'      => $html_tables
        ]);
    }

    public function register($id)
    {
        $table                     = Table::where('id', $id)->first();
        $data["idtable"]           = $id;
        $data["table"]             = $table;
        $data["units"]             = Unit::where('estado', 1)->get();
        $data['type_inafects']     = IgvTypeAffection::where('estado', 1)->get();
        $data['type_documents']    = IdentityDocumentType::where('estado', 1)->get();
        if ($table->estado != 1 && $table->idorden != NULL) {
            $data['detalle']        = DetailOrder::select(
                'detail_orders.*',
                'products.descripcion as producto',
                'products.codigo_interno as codigo_interno',
                'units.codigo as unidad',
                'products.impuesto as impuesto',
                'igv_type_affections.codigo as codigo_igv'
            )
                ->join('products', 'detail_orders.idproducto', '=', 'products.id')
                ->join('units', 'products.idunidad', '=', 'units.id')
                ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
                ->where('idorden', $data['table']->idorden)
                ->get();
            $data['order']          = Order::where('id', $data['table']->idorden)->first();
            $data['contador']       = 0;
            return view('admin.orders.update.home', $data);
        }

        return view('admin.orders.register.home', $data);
    }

    public function load_cart(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idtable            = $request->input('id');
        $table              = Table::where('id', $idtable)->first();
        $cart               = $this->create_cart($idtable);
        $html_cart          = '';
        $html_totales       = '';
        $contador           = 0;

        if (!empty($cart[$idtable]['products'])) {
            foreach ($cart[$idtable]['products'] as $i => $product) {
                $contador   = $contador + 1;
                $html_cart .= '<tr>
                                <td class="text-center">' . $contador . '</td>
                                <td class="text-left">' . $product["descripcion"] . '</td>
                                <td class="text-right">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text btn-down" style="cursor: pointer;" data-id="' . $product["id"] . '" data-cantidad="' . $product["cantidad"] . '" data-precio="' . $product["precio_venta"] . '" data-idtable="' . $product["idtable"] . '"><i class="ti ti-minus me-sm-1"></i></span>
                                        <input type="text" data-id="' . $product["id"] . '" class="quantity-counter text-center form-control" value="' . $product["cantidad"] . '" data-idtable="' . $product["idtable"] . '">
                                        <span class="input-group-text btn-up" style="cursor: pointer;" data-id="' . $product["id"] . '" data-cantidad="' . $product["cantidad"] . '" data-precio="' . $product["precio_venta"] . '" data-idtable="' . $product["idtable"] . '"><i class="ti ti-plus me-sm-1"></i></span>
                                    </div>
                                </td>
                                <td class="text-center"><input type="text" class="form-control form-control-sm text-center input-update" value="' . number_format($product["precio_venta"], 2, ".", "") . '" data-cantidad="' . $product["cantidad"] . '" data-id="' . $product["id"] . '" data-idtable="' . $product["idtable"] . '" name="precio"></td>
                                <td class="text-center">' . number_format(($product["precio_venta"] * $product["cantidad"]), 2, ".", "") . '</td>
                                <td class="text-center"><span data-id="' . $product["id"] . '" data-idtable="' . $product["idtable"] . '" class="text-danger btn-delete-product-cart" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                            </tr>';
            }
        } else {
            $html_cart .= '<tr>
                            <td colspan="6" class="text-center text-muted">Agregue productos al carrito</td>
                        </tr>';
        }

        $html_totales   .= '<div class="invoice-calculations">
                                        <div class="d-flex justify-content-between">
                                            <span class="w-px-100">OP. Gravadas:</span>
                                            <span class="fw-medium">S/' . number_format(($cart[$idtable]['exonerada'] + $cart[$idtable]['gravada'] + $cart[$idtable]['inafecta']), 2, ".", "") . '</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="w-px-100">IGV:</span>
                                            <span class="fw-medium">S/' . number_format($cart[$idtable]['igv'], 2, ".", "") . '</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span class="w-px-100">Total:</span>
                                            <span class="fw-medium">S/' . number_format($cart[$idtable]['total'], 2, ".", "") . '</span>
                                        </div>
                                    </div>';

        echo json_encode([
            'status'        => true,
            'cart_products' => $cart,
            'html_cart'     => $html_cart,
            'html_totals'   => $html_totales
        ]);
    }

    public function add_product(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = (int) $request->input('id');
        $cantidad           = (int) $request->input('cantidad');
        $precio             = number_format($request->input('precio'), 2, ".", "");
        $idtable            = (int) $request->input('idtable');

        if (!$this->add_product_order($id, $idtable, $cantidad, $precio)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Stock insuficiente',
                'type'      => 'warning'
            ]);
            return;
        }

        echo json_encode([
            'status'    => true,
            'msg'       => 'Producto agregado correctamente',
            'type'      => 'success',
            'idtable'   => $idtable
        ]);
    }

    public function delete_product(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id         = (int) $request->input('id');
        $idtable    = (int) $request->input('idtable');
        if (!$this->delete_product_cart($id, $idtable)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'No se pudo eliminar el producto',
                'type'      => 'warning'
            ]);
            return;
        }

        echo json_encode([
            'status'    => true,
            'msg'       => 'Producto eliminado correctamente',
            'type'      => 'success',
            'idtable'   => $idtable
        ]);
    }

    public function store_product(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id             = (int) $request->input('id');
        $cantidad       = (int) $request->input('cantidad');
        $precio         = number_format($request->input('precio'), 2, ".", "");
        $idtable        = (int) $request->input('idtable');

        if (!$this->update_quantity($id, $idtable, $cantidad, $precio)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Stock insuficiente',
                'type'      => 'warning'
            ]);
            return;
        }

        echo json_encode([
            'status'    => true,
            'msg'       => 'Actualizado correctamente',
            'type'      => 'success',
            'idtable'   => $idtable
        ]);
    }

    public function cancel_cart(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idtable            = (int) $request->input('idtable');
        $cart               = $this->create_cart($idtable);
        if (empty($cart[$idtable]["products"])) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'No existen productos en el carrito',
                'type'      => 'warning'
            ]);
            return;
        }

        $this->destroy_cart($idtable);
        echo json_encode([
            'status'    => true,
            'msg'       => 'La venta ha sido cancelada con éxito',
            'type'      => 'success'
        ]);
    }

    public function gen_order(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idtable            = $request->input('idtable');
        $table              = Table::where('id', $idtable)->first();
        $observaciones      = trim($request->input('observaciones'));
        $cart               = $this->create_cart($idtable);
        $idusuario          = Auth::user()['id'];
        if (empty($cart[$idtable]['products'])) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe ingresar al menos 1 producto',
                'type'      => 'warning'
            ]);
            return;
        }

        Order::insert([
            'fecha'         => date('Y-m-d'),
            'hora'          => date('H:i:s'),
            'exonerada'     => $cart[$idtable]['exonerada'],
            'inafecta'      => $cart[$idtable]['inafecta'],
            'gravada'       => $cart[$idtable]['gravada'],
            'anticipo'      => "0.00",
            'igv'           => $cart[$idtable]['igv'],
            'gratuita'      => "0.00",
            'otros_cargos'  => "0.00",
            'total'         => $cart[$idtable]['total'],
            'observaciones' => $observaciones,
            'idusuario'     => $idusuario,
            'idmesa'        => $idtable
        ]);

        $idorden            = Order::latest('id')->first()['id'];
        foreach ($cart[$idtable]['products'] as $product) {
            DetailOrder::insert([
                'idorden'           => $idorden,
                'idproducto'        => $product["id"],
                'cantidad'          => $product["cantidad"],
                'descuento'         => 0.0000000000,
                'igv'               => $product["igv"],
                'id_afectacion_igv' => $product["idcodigo_igv"],
                'precio_unitario'   => $product["precio_venta"],
                'precio_total'      => ($product["precio_venta"] * $product["cantidad"])
            ]);
        }

        $name               = uniqid();
        $customPaper        = array(0, 0, 450.00, 210.00);
        $data['business']   = Business::where('id', 1)->first();
        $data['ubigeo']     = $this->get_ubigeo($data['business']->ubigeo);
        $data['table']      = Table::where('id', $idtable)->first();
        $data['mesero']     = User::where('id', $idusuario)->first();
        $data['order']      = Order::where('id', $idorden)->first();
        $data['detalle']    = DetailOrder::select(
            'detail_orders.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad'
        )
            ->join('products', 'detail_orders.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('idorden', $idorden)
            ->get();

        $pdf                = PDF::loadView('admin.orders.register.comanda', $data)->setPaper($customPaper, 'landscape');
        $pdf->save(public_path('files/orders/commands/' . $name . '.pdf'));

        Order::where('id', $idorden)->update([
            'ticket_comanda'    => $name . '.pdf',
            'estado'            => 0
        ]);
        Table::where('id', $idtable)->update([
            'estado'    => 0,
            'idorden'   => $idorden
        ]);
        $this->destroy_cart($idtable);

        // Events
        event(new NewOrderEvent('Nuevo pedido en ' . $table->descripcion));

        echo json_encode([
            'status'    => true,
            'msg'       => 'Pedido realizado con éxito',
            'type'      => 'success',
            'idmesa'    => $idtable
        ]);
    }

    public function get_product_order(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = (int) $request->input('id');
        $cantidad           = (int) $request->input('cantidad');
        $precio             = number_format($request->input('precio'), 2, ".", "");
        $idtable            = (int) $request->input('idtable');
        $producto           = Product::select('products.*', 'igv_type_affections.codigo as codigo_igv')
            ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
            ->where('products.id', $id)->first();

        echo json_encode([
            'status'    => true,
            'msg'       => 'Producto agregado correctamente',
            'type'      => 'success',
            'producto'  => $producto,
            'cantidad'  => $cantidad
        ]);
    }

    public function store_product_order(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = (int) $request->input('id');
        $producto           = Product::select('products.*', 'igv_type_affections.codigo as codigo_igv')
            ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
            ->where('products.id', $id)
            ->first();
        $cantidad           = (int) $request->input('cantidad');
        $precio             = number_format($request->input('precio'), 2, ".", "");
        $idtable            = (int) $request->input('idtable');

        echo json_encode([
            'status'    => true,
            'cantidad'  => $cantidad,
            'id'        => $id,
            'precio'    => $precio,
            'producto'  => $producto,
            'msg'       => 'Actualizado correctamente',
            'type'      => 'success'
        ]);
    }

    public function gen_order_update(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $products           = json_decode($request->post('productos'));
        $totales            = json_decode($request->post('totales'));
        $observaciones      = $request->input('observaciones');
        $idtable            = $request->input('idtable');
        $table              = Table::where('id', $idtable)->first();
        $idusuario          = Auth::user()['id'];
        $idorden_db         = $table->idorden;

        if (empty($products)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe ingresar al menos 1 producto',
                'type'      => 'warning'
            ]);
            return;
        }

        # For comands
        $nuevo_detalle          = [];
        foreach($products as $product_search)
        {
            $producto_db        = DetailOrder::where('idorden', $idorden_db)->where('idproducto', $product_search->idproducto)->first();
            if($producto_db != NULL)
            {
                $nuevo_detalle[] = 
                [
                    'id'            => $producto_db["id"],
                    'idorden'       => $producto_db["idorden"],
                    'idproducto'    => $producto_db["idproducto"],
                    'cantidad'      => $producto_db["cantidad"]

                ];
            }
        }

        $productos_nuevos   = [];
        foreach ($products as $producto_entrante) 
        {
            foreach ($nuevo_detalle as $detalle_orden) 
            {
                if($producto_entrante->idproducto == $detalle_orden["idproducto"])
                {
                    if ((int) $producto_entrante->cantidad > (int) $detalle_orden["cantidad"]) 
                    {
                        $productos_nuevos[]   =
                        [
                            'idproducto'    => $producto_entrante->idproducto,
                            'cantidad'      => ((int) $producto_entrante->cantidad - (int) $detalle_orden["cantidad"]),
                            'precio'        => $producto_entrante->precio
                        ];
                    }
                }
            }
        }

        $result_1       = json_encode($products);
        $result_2       = json_encode($nuevo_detalle);

        $output_1       = json_decode($result_1, true);
        $output_2       = json_decode($result_2, true);
        $result_array   = array_diff_key($output_1, $output_2);

        foreach($result_array as $result)
        {
            $productos_nuevos[]   =
            [
                'idproducto'    => $result["idproducto"],
                'cantidad'      => (int) $result["cantidad"],
                'precio'        => $result["precio"]
            ];
        }

        if(!empty($productos_nuevos))
        {
            $search_to_update       = DetailOrderUpdate::where('idorden', $table->idorden)->get();
            if(!empty($search_to_update))
            {
                DetailOrderUpdate::where('idorden', $table->idorden)->delete();
            }

            foreach ($productos_nuevos as $product_update) 
            {
                $search_product         = Product::where('id', $product_update["idproducto"])->first();
                DetailOrderUpdate::insert([
                    'idorden'           => $table->idorden,
                    'idproducto'        => $product_update["idproducto"],
                    'cantidad'          => $product_update["cantidad"],
                    'descuento'         => 0.0000000000,
                    'igv'               => $search_product->igv,
                    'id_afectacion_igv' => $search_product->idcodigo_igv,
                    'precio_unitario'   => $product_update["precio"],
                    'precio_total'      => ($product_update["precio"] * $product_update["cantidad"]),
                ]);
            }
            $name                       = Order::where('id', $table->idorden)->first()->ticket_comanda;
            $customPaper                = array(0, 0, 300.00, 210.00);
            $data['total_moment']       = 0;
            $data['business']           = Business::where('id', 1)->first();
            $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
            $data['table']              = Table::where('id', $idtable)->first();
            $data['mesero']             = User::where('id', $idusuario)->first();
            $data['order']              = Order::where('id', $table->idorden)->first();
            $data['detalle']            = DetailOrderUpdate::select(
                'detail_order_updates.*',
                'products.descripcion as producto',
                'products.codigo_interno as codigo_interno',
                'units.codigo as unidad'
            )
            ->join('products', 'detail_order_updates.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('idorden', $table->idorden)
            ->get();

            $pdf                = PDF::loadView('admin.orders.register.comanda', $data)->setPaper($customPaper, 'landscape');
            $pdf->save(public_path('files/orders/commands/' . $name));

            Order::where('id', $table->idorden)->update([
                'ticket_comanda'    => $name
            ]);
        }
        # End

        $registros                  = DetailOrder::where('idorden', $idorden_db)->get();
        $existingIdentifiers        = $registros->pluck('idproducto')->toArray();
        $array_ids                  = [];
        $array_precio               = [];
        $array_cantidad             = [];
        
        foreach ($products as $producto) {
            $array_ids[]            = $producto->idproducto;
            $array_precio[]         = $producto->precio;
            $array_cantidad[]       = $producto->cantidad;
        }

        foreach ($existingIdentifiers as $i => $id_db) {
            if (in_array($id_db, $array_ids)) {
                foreach ($products as $producto) {
                    $search_product         = Product::where('id', $producto->idproducto)->first();
                    DetailOrder::updateOrCreate([
                        'idorden'           => $idorden_db,
                        'idproducto'        => $producto->idproducto
                    ], [
                        'idorden'           => $idorden_db,
                        'idproducto'        => $producto->idproducto,
                        'cantidad'          => $producto->cantidad,
                        'precio_unitario'   => $producto->precio,
                        'descuento'         => 0.0000000000,
                        'igv'               => $search_product->igv,
                        'id_afectacion_igv' => $search_product->idcodigo_igv,
                        'precio_total'      => ($producto->precio * $producto->cantidad)
                    ]);
                }
            } else {
                DetailOrder::where([
                    'idorden'           => $idorden_db,
                    'idproducto'        => $id_db
                ])->delete();
            }
        }

        Order::where('id', $table->idorden)->update([
            'fecha'         => date('Y-m-d'),
            'exonerada'     => $totales->exonerada,
            'inafecta'      => $totales->inafecta,
            'gravada'       => $totales->gravada,
            'anticipo'      => "0.00",
            'igv'           => $totales->igv,
            'gratuita'      => "0.00",
            'otros_cargos'  => "0.00",
            'total'         => $totales->total,
            'observaciones' => $observaciones,
            'idusuario'     => $idusuario,
            'idmesa'        => $idtable
        ]);

        event(new NewOrderEvent('Nuevo pedido en ' . $table->descripcion));
        echo json_encode([
            'status'    => true,
            'msg'       => 'Pedido actualizado con éxito',
            'type'      => 'success',
            'idtable'   => $idtable,
            'idorder'   => $table->idorden
        ]);
    }

    public function get_tables_availables(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idtable            = $request->input('idtable');
        $tables_availables  = Table::select('tables.*', 'rooms.descripcion as sala')
            ->join('rooms', 'tables.idsala', 'rooms.id')
            ->where('tables.id', '!=', $idtable)
            ->where('tables.estado', 1)->get();

        echo json_encode([
            'status'        => true,
            'tables'        => $tables_availables
        ]);
    }

    public function change_table(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idtable                = $request->input('idtable');
        $table                  = Table::where('id', $idtable)->first();
        $idtable_up             = $request->input('idtable_up');
        if (empty($idtable_up)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe seleccionar una mesa',
                'type'      => 'warning'
            ]);
            return;
        }

        Order::where('id', $table->idorden)->update([
            'idmesa'            => $idtable_up
        ]);

        Table::where('id', $idtable)->update([
            'estado'            => 1,
            'idorden'           => NULL
        ]);

        Table::where('id', $idtable_up)->update([
            'estado'            => 0,
            'idorden'           => $table->idorden
        ]);

        event(new UpdateOrderEvent);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Cambio de mesa realizado correctamente',
            'type'      => 'success',
            'idtable'   => $idtable_up
        ]);
    }

    public function process_pay(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = $request->input('id');
        $order              = Order::where('id', $id)->first();


        $iduser             = Auth::user()['id'];
        $idcash             = Auth::user()['idcaja'];
        $search             = count(ArchingCash::where('idcaja', $idcash)->where('idusuario', $iduser)->where('estado', 1)->get());
        if ($search < 1) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Primero debe aperturar caja',
                'type'      => 'warning'
            ]);
            return;
        }
        echo json_encode([
            'status'        => true,
            'order'         => $order
        ]);
    }

    public function save_billing(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }
        $idorder                = $request->input('idorder');
        $order                  = Order::where('id', $idorder)->first();
        $iddocumento_tipo       = $request->input('iddocumento_tipo');
        $dni_ruc                = $request->input('dni_ruc');
        $modo_pago              = $request->input('modo_pago');
        $difference             = $request->input('difference');
        $serie_sale             = explode('-', $request->input('serie_sale'));
        $serie                  = $serie_sale[0];
        $correlativo            = $serie_sale[1];
        $fecha_emision          = date('Y-m-d');
        $fecha_vencimiento      = date('Y-m-d');
        $id_arching             = ArchingCash::where('idcaja', Auth::user()['idcaja'])->where('idusuario', Auth::user()['id'])->latest('id')->first()['id'];
        // Detail payments
        $quantity_paying        = number_format($request->input('quantity_paying'), 2, ".", "");
        $quantity_paying_2      = number_format($request->input('quantity_paying_2'), 2, ".", "");
        $quantity_paying_3      = number_format($request->input('quantity_paying_3'), 2, ".", "");
        $pay_mode               = $modo_pago;
        $pay_mode_2             = $request->input('modo_pago_2');
        $pay_mode_3             = $request->input('modo_pago_3');
        $id_sale                = NULL;
        $detalle                = DetailOrder::select(
            'detail_orders.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'products.idcodigo_igv',
            'units.codigo as unidad'
        )
            ->join('products', 'detail_orders.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
            ->where('detail_orders.idorden', $idorder)
            ->get();

        if (empty($dni_ruc)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Seleccione un cliente',
                'type'      => 'warning'
            ]);
            return;
        }

        if ($iddocumento_tipo == "7") // NV
        {
            SaleNote::insert([
                'idtipo_comprobante'    => $iddocumento_tipo,
                'serie'                 => $serie,
                'correlativo'           => $correlativo,
                'fecha_emision'         => $fecha_emision,
                'fecha_vencimiento'     => $fecha_vencimiento,
                'hora'                  => date('H:i:s'),
                'idcliente'             => $dni_ruc,
                'idmoneda'              => 1,
                'idpago'                => 1,
                'modo_pago'             => $modo_pago,
                'exonerada'             => $order->exonerada,
                'inafecta'              => $order->inafecta,
                'gravada'               => $order->gravada,
                'anticipo'              => "0.00",
                'igv'                   => $order->igv,
                'gratuita'              => "0.00",
                'otros_cargos'          => "0.00",
                'total'                 => $order->total,
                'estado'                => 1,
                'idusuario'             => Auth::user()['id'],
                'idcaja'                => $id_arching,
                'vuelto'                => $difference
            ]);

            $idfactura                  = SaleNote::latest('id')->first()['id'];
            // Detail
            foreach ($detalle as $product) {
                DetailSaleNote::insert([
                    'idnotaventa'           => $idfactura,
                    'idproducto'            => $product['idproducto'],
                    'cantidad'              => $product['cantidad'],
                    'descuento'             => 0.0000000000,
                    'igv'                   => $product["igv"],
                    'id_afectacion_igv'     => $product['idcodigo_igv'],
                    'precio_unitario'       => $product['precio_unitario'],
                    'precio_total'          => ($product['precio_unitario'] * $product['cantidad'])
                ]);

                if ($product["stock"] != NULL) {
                    Product::where('id', $product["idproducto"])->update([
                        "stock"  => $product["stock"] - $product["cantidad"]
                    ]);
                }
            }

            // Insert pay mode
            if ($quantity_paying != "0.00") {
                DetailPayment::insert([
                    'idtipo_comprobante'    => $iddocumento_tipo,
                    'idfactura'             => $idfactura,
                    'idpago'                => $pay_mode,
                    'monto'                 => $quantity_paying,
                    'idcaja'                => $id_arching
                ]);
            }

            if ($quantity_paying_2 != "0.00") {
                DetailPayment::insert([
                    'idtipo_comprobante'    => $iddocumento_tipo,
                    'idfactura'             => $idfactura,
                    'idpago'                => $pay_mode_2,
                    'monto'                 => $quantity_paying_2,
                    'idcaja'                => $id_arching
                ]);
            }

            if ($quantity_paying_3 != "0.00") {
                DetailPayment::insert([
                    'idtipo_comprobante'    => $iddocumento_tipo,
                    'idfactura'             => $idfactura,
                    'idpago'                => $pay_mode_3,
                    'monto'                 => $quantity_paying_3,
                    'idcaja'                => $id_arching
                ]);
            }


            // Gen ticket data to pdf
            $factura                            = SaleNote::where('id', $idfactura)->first();
            $ruc                                = Business::where('id', 1)->first()->ruc;
            $codigo_comprobante                 = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
            $name                               = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;
            $id_sale                            = $idfactura;
            $this->gen_ticket_sn($idfactura, $name);
            $ultima_serie                       = Serie::where('idtipo_documento', $iddocumento_tipo)->where('idcaja', Auth::user()['idcaja'])->first();
            $ultimo_correlativo                 = (int) $ultima_serie->correlativo + 1;
            $nuevo_correlativo                  = str_pad($ultimo_correlativo, 8, '0', STR_PAD_LEFT);
            Serie::where('idtipo_documento', $iddocumento_tipo)->where('idcaja', Auth::user()['idcaja'])->update([
                'correlativo'   => $nuevo_correlativo
            ]);
        } else { // B/F
            $business                   = Business::where('id', 1)->first();
            $type_document              = TypeDocument::where('id', $iddocumento_tipo)->first();
            $client                     = Client::where('id', $dni_ruc)->first();
            $identity_document          = IdentityDocumentType::where('id', $client->iddoc)->first();
            $qr                         = $business->ruc . ' | ' . $type_document->codigo . ' | ' . $serie . ' | ' . $correlativo . ' | ' . number_format($order->igv, 2, ".", "") . ' | ' . number_format($order->total, 2, ".", "") . ' | ' . $fecha_emision . ' | ' . $identity_document->codigo . ' | ' . $client->dni_ruc;
            $name_qr                    = $serie . '-' . $correlativo;

            // Gen Qr
            QrCode::format('png')
                ->size(140)
                ->generate($qr, 'files/billings/qr/' . $name_qr . '.png');

            Billing::insert([
                'idtipo_comprobante'    => $iddocumento_tipo,
                'serie'                 => $serie,
                'correlativo'           => $correlativo,
                'fecha_emision'         => $fecha_emision,
                'fecha_vencimiento'     => $fecha_vencimiento,
                'hora'                  => date('H:i:s'),
                'idcliente'             => $dni_ruc,
                'idmoneda'              => 1,
                'idpago'                => 1,
                'modo_pago'             => $modo_pago,
                'exonerada'             => $order->exonerada,
                'inafecta'              => $order->inafecta,
                'gravada'               => $order->gravada,
                'anticipo'              => "0.00",
                'igv'                   => $order->igv,
                'gratuita'              => "0.00",
                'otros_cargos'          => "0.00",
                'total'                 => $order->total,
                'cdr'                   => 0,
                'anulado'               => 0,
                'id_tipo_nota_credito'  => null,
                'estado_cpe'            => 0,
                'errores'               => null,
                'nticket'               => null,
                'idusuario'             => Auth::user()['id'],
                'idcaja'                => $id_arching,
                'vuelto'                => $difference,
                'qr'                    => $name_qr . '.png'
            ]);
            $idfactura                  = Billing::latest('id')->first()['id'];

            foreach ($detalle as $product) {
                DetailBilling::insert([
                    'idfacturacion'         => $idfactura,
                    'idproducto'            => $product['idproducto'],
                    'cantidad'              => $product['cantidad'],
                    'descuento'             => 0.0000000000,
                    'igv'                   => $product["igv"],
                    'id_afectacion_igv'     => $product['idcodigo_igv'],
                    'precio_unitario'       => $product['precio_unitario'],
                    'precio_total'          => ($product['precio_unitario'] * $product['cantidad'])
                ]);

                if ($product["stock"] != NULL) {
                    Product::where('id', $product["idproducto"])->update([
                        "stock"  => $product["stock"] - $product["cantidad"]
                    ]);
                }
            }

            // Insert pay mode
            if ($quantity_paying != "0.00") {
                DetailPayment::insert([
                    'idtipo_comprobante'    => $iddocumento_tipo,
                    'idfactura'             => $idfactura,
                    'idpago'                => $pay_mode,
                    'monto'                 => $quantity_paying,
                    'idcaja'                => $id_arching
                ]);
            }

            if ($quantity_paying_2 != "0.00") {
                DetailPayment::insert([
                    'idtipo_comprobante'    => $iddocumento_tipo,
                    'idfactura'             => $idfactura,
                    'idpago'                => $pay_mode_2,
                    'monto'                 => $quantity_paying_2,
                    'idcaja'                => $id_arching
                ]);
            }

            if ($quantity_paying_3 != "0.00") {
                DetailPayment::insert([
                    'idtipo_comprobante'    => $iddocumento_tipo,
                    'idfactura'             => $idfactura,
                    'idpago'                => $pay_mode_3,
                    'monto'                 => $quantity_paying_3,
                    'idcaja'                => $id_arching
                ]);
            }

            $factura                        = Billing::where('id', $idfactura)->first();
            $ruc                            = Business::where('id', 1)->first()->ruc;
            $codigo_comprobante             = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
            $name                           = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;
            $id_sale                        = $idfactura;
            $this->gen_ticket_b($idfactura, $name);
            $ultima_serie                       = Serie::where('idtipo_documento', $iddocumento_tipo)->where('idcaja', Auth::user()['idcaja'])->first();
            $ultimo_correlativo                 = (int) $ultima_serie->correlativo + 1;
            $nuevo_correlativo                  = str_pad($ultimo_correlativo, 8, '0', STR_PAD_LEFT);
            Serie::where('idtipo_documento', $iddocumento_tipo)->where('idcaja', Auth::user()['idcaja'])->update([
                'correlativo'   => $nuevo_correlativo
            ]);
        }

        Order::where('id', $order->id)->update([
            'estado'            => 1,
            'idtipo_documento'  => $iddocumento_tipo,
            'idventa'           => $id_sale
        ]);
        Table::where('idorden', $order->id)->update([
            'estado'    => 1,
            'idorden'   => NULL
        ]);
        event(new UpdateOrderEvent);
        echo json_encode([
            'status'        => true,
            'id'            => $id_sale,
            'pdf'           => $name . '.pdf',
            'type_document' => $iddocumento_tipo
        ]);
    }
    ## Cart
    public function create_cart($idtable)
    {
        if (!session()->get('order') || empty(session()->get('order')[$idtable]['products'])) {
            $order =
                [
                    'order' =>
                    [
                        $idtable    =>
                        [
                            'products'     => [],
                            'igv'          => 0,
                            'exonerada'    => 0,
                            'gravada'      => 0,
                            'inafecta'     => 0,
                            'subtotal'     => 0,
                            'total'        => 0
                        ]
                    ]
                ];

            session($order);
            return session()->get('order');
        }

        $exonerada  = 0;
        $gravada    = 0;
        $inafecta   = 0;
        $subtotal   = 0;
        $total      = 0;
        $igv        = 0;

        foreach (session('order')[$idtable]['products'] as $index => $product) {
            if ($product['impuesto'] == 1) {
                $igv        +=  number_format((((float) $product['precio_venta'] - (float) $product['precio_venta'] / 1.18) * (int) $product['cantidad']), 2, ".", "");
                $igv        = $this->redondeado($igv);
            }

            if ($product["codigo_igv"] == "10") {
                $gravada    += number_format((((float) $product['precio_venta'] / 1.18) * (int) $product['cantidad']), 2, ".", "");
                $gravada     = $this->redondeado($gravada);
            }

            if ($product["codigo_igv"] == "20") {
                $exonerada   += number_format(((float) $product['precio_venta'] * (int) $product['cantidad']), 2, ".", "");
                $exonerada   = $this->redondeado($exonerada);
            }

            if ($product["codigo_igv"] == "30") {
                $inafecta    += number_format(((float) $product['precio_venta'] * (int) $product['cantidad']), 2, ".", "");
                $inafecta     = str_replace(',', '', $inafecta);
                $inafecta     = $this->redondeado($inafecta);
            }

            $subtotal      = $exonerada + $gravada + $inafecta;
            session()->put('order.' . $idtable . '.products.' . $index, $product);
        }

        $total      = $subtotal + $igv;
        $order =
            [
                'order' =>
                [
                    $idtable    =>
                    [
                        'products'     => session('order')[$idtable]['products'],
                        'igv'          => $igv,
                        'exonerada'    => $exonerada,
                        'gravada'      => $gravada,
                        'inafecta'     => $inafecta,
                        'subtotal'     => $subtotal,
                        'total'        => $total,
                    ]
                ]
            ];

        session($order);
        return session()->get('order');
    }

    public function add_product_order($id, $idtable, $cantidad, $precio)
    {
        $product        = Product::select(
            'products.*',
            'units.codigo as unidad',
            'igv_type_affections.descripcion as tipo_afecto',
            'igv_type_affections.codigo as codigo_igv'
        )
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
            ->where('products.id', $id)
            ->first();

        if (!$product)
            return false;

        $new_product    =
            [
                'id'                => $product->id,
                'codigo_sunat'      => $product->codigo_sunat,
                'descripcion'       => $product->descripcion,
                'idunidad'          => $product->idunidad,
                'unidad'            => $product->unidad,
                'idcodigo_igv'      => $product->idcodigo_igv,
                'codigo_igv'        => $product->codigo_igv,
                'igv'               => $product->igv,
                'precio_compra'     => $product->precio_compra,
                'precio_venta'      => $precio,
                'impuesto'          => $product->impuesto,
                'cantidad'          => $cantidad,
                'idtable'           => $idtable
            ];

        /*
            Si los productos del carrito están vacíos entonces lo agregamos
        */

        if (empty(session()->get('order')[$idtable]['products'])) {
            session()->push('order.' . $idtable . '.products', $new_product);
            return true;
        }

        /*
            Si no, al menos ya hay uno, entonces lo recorremos
        */
        foreach (session()->get('order')[$idtable]['products'] as $index => $product) {
            /*
                Si el id del producto ingresado coincide con el id del producto del bucle, sumamos la cantidad,
                de lo contrario, asimilamos que es un producto nuevo y agregamos
            */
            if ($id == $product['id']) {
                if ($product['precio_venta'] == $precio) {
                    $product['cantidad'] = $product['cantidad'] + $cantidad;
                    session()->put('order.' . $idtable . '.products.' . $index, $product);
                    return true;
                }
            }
        }

        session()->push('order.' . $idtable . '.products', $new_product);
        return true;
    }

    public function delete_product_cart($id, $idtable)
    {
        if (!session()->get('order')[$idtable] || empty(session()->get('order')[$idtable]['products'])) {
            return false;
        }

        foreach (session()->get('order')[$idtable]['products'] as $index => $product) {
            if ($id == $product['id']) {
                session()->forget('order.' . $idtable . '.products.' . $index, $product);
                return true;
            }
        }
    }

    public function update_quantity($id, $idtable, $cantidad, $precio)
    {
        if (empty(session()->get('order')[$idtable]['products'])) {
            return false;
        }

        foreach (session()->get('order')[$idtable]['products'] as $index => $product) {
            if ($id == $product['id'] && $product['idtable'] == $idtable) {
                $product['cantidad']        = $cantidad;
                $product['precio_venta']    = $precio;
                session()->put('order.' . $idtable . '.products.' . $index, $product);
                return true;
            }
        }
    }

    public function destroy_cart($idtable)
    {
        if (!session()->get('order') || empty(session()->get('order')[$idtable]['products'])) {
            return false;
        }
        session()->forget('order.' . $idtable);
        return true;
    }

    public function gen_ticket_sn($id, $name)
    {
        $customPaper                = array(0, 0, 630.00, 210.00);
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $ruc                        = $data['business']->ruc;
        $factura                    = SaleNote::where('id', $id)->first();
        $codigo_comprobante         = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $data["name"]               = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;

        $data['factura']            = SaleNote::where('id', $id)->first();
        $data['cliente']            = Client::where('id', $factura->idcliente)->first();
        $data['tipo_documento']     = IdentityDocumentType::where('id', $data['cliente']->iddoc)->first();
        $data['moneda']             = Currency::where('id', $factura->idmoneda)->first();
        $data['modo_pago']          = PayMode::where('id', $factura->modo_pago)->first();
        $data['detalle']            = DetailSaleNote::select(
            'detail_sale_notes.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno'
        )
            ->join('products', 'detail_sale_notes.idproducto', '=', 'products.id')
            ->where('idnotaventa', $factura->id)
            ->get();

        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($factura->total, 2);
        $data['tipo_comprobante']   = TypeDocument::where('id', $factura->idtipo_comprobante)->first();
        $data['vendedor']           = mb_strtoupper(User::where('id', $data['factura']->idusuario)->first()->user);
        $data['payment_modes']      = DetailPayment::select('detail_payments.*', 'pay_modes.descripcion as modo_pago')
            ->join('pay_modes', 'detail_payments.idpago', 'pay_modes.id')
            ->where('idfactura', $factura->id)
            ->where('idtipo_comprobante', $factura->idtipo_comprobante)
            ->get();
        $data['count_payment']      = count($data['payment_modes']);
        $pdf                        = PDF::loadView('admin.billings.ticket_sn', $data)->setPaper($customPaper, 'landscape');
        return $pdf->save(public_path('files/sale-notes/ticket/' . $name . '.pdf'));
    }

    public function gen_ticket_b($id, $name)
    {
        $customPaper                = array(0, 0, 630.00, 210.00);
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $ruc                        = $data['business']->ruc;
        $factura                    = Billing::where('id', $id)->first();
        $codigo_comprobante         = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $data["name"]               = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;

        $data['factura']            = Billing::where('id', $id)->first();
        $data['cliente']            = Client::where('id', $factura->idcliente)->first();
        $data['tipo_documento']     = IdentityDocumentType::where('id', $data['cliente']->iddoc)->first();
        $data['moneda']             = Currency::where('id', $factura->idmoneda)->first();
        $data['modo_pago']          = PayMode::where('id', $factura->modo_pago)->first();
        $data['detalle']            = DetailBilling::select(
            'detail_billings.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno'
        )
            ->join('products', 'detail_billings.idproducto', '=', 'products.id')
            ->where('idfacturacion', $factura->id)
            ->get();

        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($factura->total, 2);
        $data['tipo_comprobante']   = TypeDocument::where('id', $factura->idtipo_comprobante)->first();
        $data['vendedor']           = mb_strtoupper(User::where('id', $data['factura']->idusuario)->first()->user);
        $data['payment_modes']      = DetailPayment::select('detail_payments.*', 'pay_modes.descripcion as modo_pago')
            ->join('pay_modes', 'detail_payments.idpago', 'pay_modes.id')
            ->where('idfactura', $factura->id)
            ->where('idtipo_comprobante', $factura->idtipo_comprobante)
            ->get();
        $data['count_payment']      = count($data['payment_modes']);
        $pdf                        = PDF::loadView('admin.billings.ticket_b', $data)->setPaper($customPaper, 'landscape');
        return $pdf->save(public_path('files/billings/ticket/' . $name . '.pdf'));
    }

    ## Test
    public function test_comanda()
    {
        $customPaper        = array(0, 0, 630.00, 210.00);
        $pdf                = PDF::loadView('admin.orders.register.test_comanda')->setPaper($customPaper, 'landscape');
        return $pdf->stream();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Buy;
use App\Models\DetailBuy;
use App\Models\IdentityDocumentType;
use App\Models\IgvTypeAffection;
use App\Models\PayMode;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Serie;
use App\Models\TypeDocument;
use App\Models\Unit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Luecano\NumeroALetras\NumeroALetras;
use Illuminate\Support\Facades\Auth;

class BuyController extends Controller
{
    public function index()
    {
        return view('admin.buys.list');
    }

    public function get()
    {
        $buys     = Buy::select('buys.*', 'providers.dni_ruc as dni_ruc', 'providers.nombres as proveedor')
            ->join('providers', 'buys.idproveedor', '=', 'providers.id')
            ->where('idtipo_comprobante', '!=', 6)
            ->orderBy('id', 'DESC')
            ->get();

        return Datatables()
            ->of($buys)
            ->addColumn('proveedor', function ($buys) {
                $proveedor  = $buys->proveedor;
                return $proveedor;
            })
            ->addColumn('documento', function ($buys) {
                $documento  = $buys->serie . '-' . $buys->correlativo;
                return $documento;
            })
            ->addColumn('fecha_de_emision', function ($buys) {
                $fecha_emision = date('d-m-Y', strtotime($buys->fecha_emision));
                return $fecha_emision;
            })
            ->addColumn('estado_compra', function ($buys) {
                $estado    = $buys->estado;
                $btn    = '';
                switch ($estado) {
                    case '0':
                        $btn .= '<span class="badge bg-warning text-white">Por pagar</span>';
                        break;

                    case '1':
                        $btn .= '<span class="badge bg-success text-white">Pagado</span>';
                        break;
                }
                return $btn;
            })
            ->addColumn('acciones', function ($buys) {
                $id     = $buys->id;
                $btn    = '<div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                <div class="dropdown-menu"><a class="dropdown-item btn-print" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                            <span> Imprimir</span>
                                        </a>
                                        <a class="dropdown-item btn-download" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                            <span>Descargar PDF A4</span>
                                        </a>
                                        <a class="dropdown-item btn-confirm" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span>Eliminar</span>
                                        </a>
                                        <a class="dropdown-item btn-open-whatsapp" data-id="' . $id . '" href="javascript:void(0);">
                                            <i class="fa-brands fa-whatsapp"></i>
                                            <span> Enviar Documento</span>
                                        </a>
                </div>
            </div>';
                return $btn;
            })
            ->rawColumns(['proveedor', 'documento', 'fecha_de_emision', 'estado_compra', 'acciones'])
            ->make(true);
    }

    public function create()
    {
        $data['type_documents_p']   = TypeDocument::where('estado', 1)->limit(2)->get();
        $data['type_documents']     = IdentityDocumentType::where('estado', 1)->get();
        $data['modo_pagos']         = PayMode::get();
        $data['providers']          = Provider::where('iddoc', 4)->get();
        $data['products']           = Product::where('stock', '!=', NULL)->get();
        $data["units"]              = Unit::where('estado', 1)->get();
        $data['type_inafects']      = IgvTypeAffection::where('estado', 1)->get();
        return view('admin.buys.create', $data);
    }

    public function load_serie(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idcaja     = Auth::user()['idcaja'];
        $serie      = Serie::where('id', 1)->where('idtipo_documento', '!=', 2)->where('idcaja', $idcaja)->first();
        echo json_encode(['status'  => true, 'serie'  => $serie]);
    }

    public function get_serie(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idtipo_documento = $request->input('idtipo_documento');
        if (empty($idtipo_documento)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Seleccione un tipo de comprobante',
                'type'      => 'warning'
            ]);
            return;
        }

        $type_document                      = TypeDocument::where('id', $idtipo_documento)->first();
        $serie                              = Serie::where('idtipo_documento', $type_document->id)->first();
        if ($type_document->id != 1)
            $providers                      = Provider::where('iddoc', 1)->orWhere('iddoc', 2)->orderBy('id', 'DESC')->get();
        else
            $providers                      = Provider::where('iddoc', 4)->orderBy('id', 'DESC')->get();

        echo json_encode(['status'  => true, 'serie'  => $serie, 'providers' => $providers]);
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

        $cart           = $this->create_cart();
        $html_cart      = '';
        $html_totales   = '';
        $contador       = 0;

        if (!empty($cart['products'])) {
            foreach ($cart['products'] as $i => $product) {
                $contador   = $contador + 1;
                $html_cart .= '<tr>
                                <td class="text-center">' . $contador . '</td>
                                <td>' . $product["descripcion"] . '</td>
                                <td class="text-center">' . $product["unidad"] . '</td>
                                <td class="text-right">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text btn-down" style="cursor: pointer;" data-id="' . $product["id"] . '" data-cantidad="' . $product["cantidad"] . '" data-precio="' . $product["precio_compra"] . '"><i class="ti ti-minus me-sm-1"></i></span>
                                        <input type="text" data-id="' . $product["id"] . '" class="quantity-counter text-center form-control" value="' . $product["cantidad"] . '">
                                        <span class="input-group-text btn-up" style="cursor: pointer;" data-id="' . $product["id"] . '" data-cantidad="' . $product["cantidad"] . '" data-precio="' . $product["precio_compra"] . '"><i class="ti ti-plus me-sm-1"></i></span>
                                    </div>
                                </td>
                                <td class="text-center"><input type="text" class="form-control form-control-sm text-center input-update" value="' . number_format($product["precio_compra"], 2, ".", "") . '" data-cantidad="' . $product["cantidad"] . '" data-id="' . $product["id"] . '" name="precio_compra"></td>
                                <td class="text-center">' . number_format(($product["precio_compra"] * $product["cantidad"]), 2, ".", "") . '</td>
                                <td class="text-center"><span data-id="' . $product["id"] . '" class="text-danger btn-delete-product" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                            </tr>';
            }
        }

        $html_totales   .= '<div class="d-flex justify-content-between mb-2">
                                        <span class="w-px-100">OP. Gravadas:</span>
                                        <span class="fw-medium">S/' . number_format(($cart['exonerada'] + $cart['gravada'] + $cart['inafecta']), 2, ".", "") . '</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="w-px-100">IGV:</span>
                                        <span class="fw-medium">S/' . number_format($cart['igv'], 2, ".", "") . '</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="w-px-100">Total:</span>
                                        <span class="fw-medium">S/' . number_format($cart['total'], 2, ".", "") . '</span>
                            </div>';

        echo json_encode([
            'status'        => true,
            'cart_products' => $cart,
            'html_cart'     => $html_cart,
            'html_totales'  => $html_totales
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

        $id             = (int) $request->input('id');
        $cantidad       = (int) $request->input('cantidad');
        $precio         = number_format($request->input('precio'), 2, ".", "");

        if (!$this->add_product_cart($id, $cantidad, $precio)) {
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
            'type'      => 'success'
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

        $id     = (int) $request->input('id');
        if (!$this->delete_product_cart($id)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'No se pudo eliminar el producto',
                'type'      => 'warning'
            ]);
            return;
        }

        echo json_encode([
            'status'    => true,
            'msg'       => 'Producto agregado correctamente',
            'type'      => 'success'
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
        if (!$this->update_quantity($id, $cantidad, $precio)) {
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
            'type'      => 'success'
        ]);
    }

    public function save(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }
        $idtipo_comprobante     = $request->input('idtipo_comprobante');
        $serie                  = trim($request->input('serie'));
        $correlativo            = trim($request->input('correlativo'));
        $fecha_emision          = $request->input('fecha_emision');
        $fecha_vencimiento      = $request->input('fecha_vencimiento');
        $idproveedor            = $request->input('dni_ruc');
        $tipo_cambio            = $request->input('tipo_cambio');
        $modo_pago              = $request->input('modo_pago');
        $cart                   = $this->create_cart();

        if (empty($idproveedor)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe seleccionar el proveedor',
                'type'      => 'warning'
            ]);
            return;
        }

        if (empty($cart['products'])) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe ingresar al menos 1 producto',
                'type'      => 'warning'
            ]);
            return;
        }

        if (strlen($serie) != 4) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Le serie debe contener 04 dígitos',
                'type'      => 'warning'
            ]);
            return;
        }

        $valid_buy          = Buy::where('idproveedor', $idproveedor)
            ->where('serie', mb_strtoupper($serie))
            ->where('correlativo', $correlativo)
            ->first();

        if (!empty($valid_buy)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Compra registrada con esos datos',
                'type'      => 'warning'
            ]);
            return;
        }
        Buy::insert([
            'idtipo_comprobante'    => $idtipo_comprobante,
            'serie'                 => mb_strtoupper($serie),
            'correlativo'           => $correlativo,
            'fecha_emision'         => $fecha_emision,
            'fecha_vencimiento'     => $fecha_vencimiento,
            'hora'                  => date('H:i:s'),
            'idproveedor'           => $idproveedor,
            'idmoneda'              => 1,
            'idpago'                => 1,
            'modo_pago'             => $modo_pago,
            'exonerada'             => $cart["exonerada"],
            'inafecta'              => $cart["inafecta"],
            'gravada'               => $cart["gravada"],
            'anticipo'              => "0.00",
            'igv'                   => $cart["igv"],
            'gratuita'              => "0.00",
            'otros_cargos'          => "0.00",
            'total'                 => $cart["total"],
            'observaciones'         => "",
            'estado'                => 1,
            'idusuario'             => Auth::user()['id'],
            'idcaja'                => Auth::user()['idcaja'],
        ]);
        $idcompra                   = Buy::latest('id')->first()['id'];
        foreach ($cart['products'] as $product) {
            DetailBuy::insert([
                'idcompra'              => $idcompra,
                'idproducto'            => $product['id'],
                'cantidad'              => $product['cantidad'],
                'descuento'             => 0.0000000000,
                'igv'                   => ($product['precio_compra'] * $product['igv']),
                'id_afectacion_igv'     => $product['idcodigo_igv'],
                'precio_unitario'       => $product['precio_compra'],
                'precio_total'          => ($product['precio_compra'] * $product['cantidad'])
            ]);

            Product::where('id', $product["id"])->update([
                "stock"         => $product["stock"] + $product["cantidad"],
                "precio_compra" => $product["precio_compra"]
            ]);
        }
        $this->destroy_cart();

        $id                         = $idcompra;
        $data["buy"]                = Buy::where('id', $id)->first();
        $data["business"]           = Business::where('id', 1)->first();
        $data["provider"]           = Provider::where('id', $data["buy"]["idproveedor"])->first();
        $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
        $data["name_buy"]           = mb_strtoupper($data["provider"]->dni_ruc . '-' . $data["buy"]["serie"]) . '-' . $data["buy"]["correlativo"];
        $data["type_document"]      = TypeDocument::where('id', $data["buy"]["idtipo_comprobante"])->first();
        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["buy"]->total, 2);
        $data["detail"]             = DetailBuy::select(
            'detail_buys.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad'
        )
            ->join('products', 'detail_buys.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('detail_buys.idcompra', $id)
            ->get();

        $this->gen_pdf($data, $data["name_buy"]);

        echo json_encode([
            'status'                => true,
            'idcompra'              => $idcompra,
            'pdf'                   => $data["name_buy"] . '.pdf',
            'idtipo_comprobante'    => $idtipo_comprobante
        ]);
    }

    public function print_buy(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }


        $id                         = $request->input('id');
        $data["buy"]                = Buy::where('id', $id)->first();
        $data["business"]           = Business::where('id', 1)->first();
        $data["provider"]           = Provider::where('id', $data["buy"]["idproveedor"])->first();
        $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
        $data["name_buy"]           = mb_strtoupper($data["provider"]->dni_ruc . '-' . $data["buy"]["serie"]) . '-' . $data["buy"]["correlativo"];
        $data["type_document"]      = TypeDocument::where('id', $data["buy"]["idtipo_comprobante"])->first();
        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["buy"]->total, 2);
        $data["detail"]             = DetailBuy::select(
            'detail_buys.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad'
        )
            ->join('products', 'detail_buys.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('detail_buys.idcompra', $id)
            ->get();

        $this->gen_pdf($data, $data["name_buy"]);
        echo json_encode([
            'status'    => true,
            'pdf'       => $data["name_buy"] . '.pdf'
        ]);
    }

    public function download($id)
    {
        $data["buy"]                = Buy::where('id', $id)->first();
        $data["business"]           = Business::where('id', 1)->first();
        $data["provider"]           = Provider::where('id', $data["buy"]["idproveedor"])->first();
        $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
        $data["name_buy"]           = mb_strtoupper($data["provider"]->dni_ruc . '-' . $data["buy"]["serie"]) . '-' . $data["buy"]["correlativo"];
        $data["type_document"]      = TypeDocument::where('id', $data["buy"]["idtipo_comprobante"])->first();
        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["buy"]->total, 2);
        $data["detail"]             = DetailBuy::select(
            'detail_buys.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad'
        )
            ->join('products', 'detail_buys.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('detail_buys.idcompra', $id)
            ->get();

        $pdf    = PDF::loadView('admin.buys.pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->download($data["name_buy"] . '.pdf');
    }

    public function delete(Request $request)
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
        $buy                = Buy::where('id', $id)->first();
        $detail_buy         = DetailBuy::select('detail_buys.*', 'products.descripcion as producto', 'units.codigo as unidad')
            ->join('products', 'detail_buys.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('detail_buys.idcompra', $id)
            ->get();

        foreach ($detail_buy as $item) {
            $id_product = (int) $item["idproducto"];
            $cantidad   = intval($item["cantidad"]);
            $product    = Product::where('id', $id_product)->first();
            if (((int) $product["stock"] - $cantidad) <= 0) {
                Product::where('id', $id_product)->update([
                    'stock' => 0
                ]);
            } else {
                Product::where('id', $id_product)->update([
                    'stock' => (int) $product["stock"] - $cantidad
                ]);
            }
        }

        $ruc_proveedor      = Provider::where('id', $buy->idproveedor)->first()->dni_ruc;
        if (file_exists(public_path('files/buys/' . mb_strtoupper($ruc_proveedor . '-' . $buy->serie . '-' . $buy->correlativo . '.pdf')))) {
            unlink(public_path('files/buys/' . mb_strtoupper($ruc_proveedor . '-' . $buy->serie . '-' . $buy->correlativo . '.pdf')));
        }

        DetailBuy::where('idcompra', $id)->delete();
        Buy::where('id', $id)->delete();
        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado correctamente',
            'type'      => 'success'
        ]);
    }

    public function gen_pdf($data, $name)
    {
        $pdf    = PDF::loadView('admin.buys.pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->save(public_path('files/buys/' . $name . '.pdf'));
    }

    public function test()
    {
        $pdf    = PDF::loadView('admin.buys.test')->setPaper('A4', 'portrait');
        return $pdf->stream();
    }

    ## Functions to cart
    public function create_cart()
    {
        if (!session()->get('buy') || empty(session()->get('buy')['products'])) {
            $buy =
                [
                    'buy' =>
                    [
                        'products'     => [],
                        'igv'          => 0,
                        'exonerada'    => 0,
                        'gravada'      => 0,
                        'inafecta'     => 0,
                        'subtotal'     => 0,
                        'total'        => 0
                    ]
                ];

            session($buy);
            return session()->get('buy');
        }

        $exonerada  = 0;
        $gravada    = 0;
        $inafecta   = 0;
        $subtotal   = 0;
        $total      = 0;
        $igv        = 0;

        foreach (session('buy')['products'] as $index => $product) {
            if ($product['impuesto'] == 1) {
                $igv        +=  number_format((((float) $product['precio_compra'] - (float) $product['precio_compra'] / 1.18) * (int) $product['cantidad']), 2, ".", "");
                $igv        = $this->redondeado($igv);
            }

            if ($product["codigo_igv"] == "10") {
                $gravada    += number_format((((float) $product['precio_compra'] / 1.18) * (int) $product['cantidad']), 2, ".", "");
                $gravada     = $this->redondeado($gravada);
            }

            if ($product["codigo_igv"] == "20") {
                $exonerada   += number_format(((float) $product['precio_compra'] * (int) $product['cantidad']), 2, ".", "");
                $exonerada   = $this->redondeado($exonerada);
            }

            if ($product["codigo_igv"] == "30") {
                $inafecta    += number_format(((float) $product['precio_compra'] * (int) $product['cantidad']), 2, ".", "");
                $inafecta     = str_replace(',', '', $inafecta);
                $inafecta     = $this->redondeado($inafecta);
            }

            $subtotal      = $exonerada + $gravada + $inafecta;
            session()->put('buy.products.' . $index, $product);
        }

        $total      = $subtotal + $igv;

        $buy =
            [
                'buy' =>
                [
                    'products'     => session('buy')['products'],
                    'igv'          => $igv,
                    'exonerada'    => $exonerada,
                    'gravada'      => $gravada,
                    'inafecta'     => $inafecta,
                    'subtotal'     => $subtotal,
                    'total'        => $total,
                ]
            ];

        session($buy);
        return session()->get('buy');
    }

    public function add_product_cart($id, $cantidad, $precio)
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
                'precio_compra'     => $precio,
                'precio_venta'      => $product->precio_venta,
                'impuesto'          => $product->impuesto,
                'stock'             => $product->stock,
                'cantidad'          => $cantidad
            ];

        if (empty(session()->get('buy')['products'])) {
            session()->push('buy.products', $new_product);
            return true;
        }

        foreach (session()->get('buy')['products'] as $index => $product) {
            if ($id == $product['id']) {
                //if($product['precio_venta_igv'] == $precio)
                //{
                $product['cantidad'] = $product['cantidad'] + $cantidad;
                session()->put('buy.products.' . $index, $product);
                return true;
                //}
            }
        }

        session()->push('buy.products', $new_product);
        return true;
    }

    public function delete_product_cart($id)
    {
        if (!session()->get('buy') || empty(session()->get('buy')['products'])) {
            return false;
        }

        foreach (session()->get('buy')['products'] as $index => $product) {
            if ($id == $product['id']) {
                session()->forget('buy.products.' . $index, $product);
                return true;
            }
        }
    }

    public function update_quantity($id, $cantidad, $precio)
    {
        if (empty(session()->get('buy')['products'])) {
            return false;
        }

        foreach (session()->get('buy')['products'] as $index => $product) {
            if ($id == $product['id']) {
                $product['cantidad']            =  $cantidad;
                $product['precio_compra']       =  $precio;
                session()->put('buy.products.' . $index, $product);
                return true;
            }
        }
    }

    public function get_price_product(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }

        $id         = $request->input('id');
        $product    = Product::where('id', $id)->first();
        echo json_encode([
            'status'    => true,
            'product'   => $product
        ]);
    }

    public function destroy_cart()
    {
        if (!session()->get('buy') || empty(session()->get('buy')['products'])) {
            return false;
        }

        session()->forget('buy');
        return true;
    }

    public function get_products_update()
    {
        $products           = Product::where('stock', '!=', NULL)->orderBy('id', 'DESC')->get();
        return $products;
    }
}

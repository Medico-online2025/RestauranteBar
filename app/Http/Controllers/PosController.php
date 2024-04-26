<?php

namespace App\Http\Controllers;

use App\Models\ArchingCash;
use App\Models\Billing;
use App\Models\Business;
use App\Models\Client;
use App\Models\Currency;
use App\Models\DetailBilling;
use App\Models\DetailPayment;
use App\Models\DetailSaleNote;
use App\Models\IdentityDocumentType;
use App\Models\IgvTypeAffection;
use App\Models\PayMode;
use App\Models\Product;
use App\Models\SaleNote;
use App\Models\Serie;
use App\Models\TypeDocument;
use App\Models\Unit;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{

    public function check_cash(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $iduser             = $request->input('iduser');
        $idcash             = $request->input('idcash');
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
            'status'    => true
        ]);
    }

    public function index()
    {
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $data['clients']            = Client::where('iddoc', 1)->orWhere('iddoc', 2)->get();
        $data['modo_pagos']         = PayMode::get();
        $data["units"]              = Unit::where('estado', 1)->get();
        $data['type_inafects']      = IgvTypeAffection::where('estado', 1)->get();
        $data['type_documents']     = IdentityDocumentType::where('estado', 1)->get();
        return view('admin.pos.home', $data);
    }

    public function view_products(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $html_products  = '';
        $products       = DB::select("CALL get_list_products()");


        if (!empty($products)) {
            foreach ($products as $product) {
                $html_products .= '<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-2 item" style="cursor: pointer;" >
                                        <div id="' . $product->id . '" class="card h-100  btn-add-product-cart btn-update-product-cart" data-id="' . $product->id . '" data-cantidad="1" data-precio="' . $product->precio_venta . '">
                                            <div class="card-body" style="border-radius: 5px ">';
                if (!empty($product->codigo_interno))
                    $html_products .= '<small class="fw-bold">' . $product->codigo_interno . ' -</small>';
                $html_products .= '<h6 class="mb-2 pb-1">' . $product->descripcion . '</h6>
                                                <p class="small"></p>
                                                <div class="row mb-3 g-3">
                                                    <div class="col-6">
                                                        <div class="d-flex">
                                                            <div>
                                                                <h6 class="mb-0 fw-bold text-nowrap text-primary">S/ ' . number_format($product->precio_venta, 2, ".", "") . '
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
            }
        } else {
            $html_products .= '<div class="col-12 item">No hay productos registrados</div>';
        }

        echo json_encode([
            'status'        => true,
            'html_products' => $html_products
        ]);
    }

    public function search_view_product(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $value              = trim($request->input('value'));
        Cache::pull('search-products');
        $products = Cache::rememberForever('search-products', function () use ($value) {
            return Product::where('descripcion', 'like', "%$value%")
                ->orWhere('marca', 'like', "%$value%")
                ->orWhere('presentacion', 'like', "%$value%")
                ->orWhere('codigo_interno', 'like', "%$value%")
                ->get();
        });
        $html_products      = '';
        if (count($products) < 1) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Producto no registrado',
                'type'      => 'warning'
            ]);
            return;
        }

        foreach ($products as $product) {
            $html_products .= '<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-2 item" style="cursor: pointer;" >
                                        <div id="' . $product["id"] . '" class="card h-100 btn-add-product-cart btn-update-product-cart" data-id="' . $product["id"] . '" data-cantidad="1" data-precio="' . $product['precio_venta'] . '">
                                            <div class="card-body" style="border-radius: 5px ">';
            if (!empty($product["codigo_interno"]))
                $html_products .= '<small class="fw-bold">' . $product["codigo_interno"] . ' -</small>';
            $html_products .= '<h6 class="mb-2 pb-1">' . $product["descripcion"] . '</h6>
                                                <p class="small"></p>
                                                <div class="row mb-3 g-3">
                                                    <div class="col-6">
                                                        <div class="d-flex">
                                                            <div>
                                                                <h6 class="mb-0 fw-bold text-nowrap text-primary">S/ ' . number_format($product["precio_venta"], 2, ".", "") . '
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
        }

        echo json_encode([
            'status'        => true,
            'products'      => $products,
            'html_products' => $html_products
        ]);
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
                                <td class="text-left">' . $product["descripcion"] . '</td>
                                <td class="text-right">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text btn-down" style="cursor: pointer;" data-id="' . $product["id"] . '" data-cantidad="' . $product["cantidad"] . '" data-precio="' . $product["precio_venta"] . '"><i class="ti ti-minus me-sm-1"></i></span>
                                        <input type="text" data-id="' . $product["id"] . '" class="quantity-counter text-center form-control" value="' . $product["cantidad"] . '">
                                        <span class="input-group-text btn-up" style="cursor: pointer;" data-id="' . $product["id"] . '" data-cantidad="' . $product["cantidad"] . '" data-precio="' . $product["precio_venta"] . '"><i class="ti ti-plus me-sm-1"></i></span>
                                    </div>
                                </td>
                                <td class="text-center"><input type="text" class="form-control form-control-sm text-center input-update" value="' . number_format($product["precio_venta"], 2, ".", "") . '" data-cantidad="' . $product["cantidad"] . '" data-id="' . $product["id"] . '" name="precio"></td>
                                <td class="text-center">' . number_format(($product["precio_venta"] * $product["cantidad"]), 2, ".", "") . '</td>
                                <td class="text-center"><span data-id="' . $product["id"] . '" class="text-danger btn-delete-product-cart" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                            </tr>';
            }
        } else {
            $html_cart .= '<tr>
                            <td colspan="6" class="text-center text-muted">Agregue productos al carrito</td>
                        </tr>';
        }

        $html_totales   .= '<div class="d-flex justify-content-between align-items-center mt-3">
                                    <p class="mb-0">OP. Gravadas</p>
                                    <h6 class="mb-0">S/' . number_format(($cart['exonerada'] + $cart['gravada'] + $cart['inafecta']), 2, ".", "") . '</h6>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <p class="mb-0">IGV</p>
                                    <h6 class="mb-0">S/' . number_format($cart['igv'], 2, ".", "") . '</h6>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center mt-3 pb-1">
                                    <p class="mb-0">Total</p>
                                    <h6 class="mb-0">S/' . number_format($cart['total'], 2, ".", "") . '</h6>
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

        $id             = (int) $request->input('id');
        $producto       = Product::where('id', $id)->first();
        $opcion         = (int) $producto->opcion;
        $cantidad       = (int) $request->input('cantidad');
        $precio         = number_format($request->input('precio'), 2, ".", "");

        if (!$this->add_product_cart($id, $cantidad, $precio, $opcion)) {
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
        $producto       = Product::where('id', $id)->first();
        $opcion         = (int) $producto->opcion;
        if (!$this->delete_product_cart($id, $opcion)) {
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
        $producto       = Product::where('id', $id)->first();
        $opcion         = (int) $producto->opcion;
        $cantidad       = (int) $request->input('cantidad');
        $precio         = number_format($request->input('precio'), 2, ".", "");
        if (!$this->update_quantity($id, $cantidad, $precio, $opcion)) {
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

        $cart               = $this->create_cart();
        if (empty($cart["products"])) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'No existen productos en el carrito',
                'type'      => 'warning'
            ]);
            return;
        }

        $this->destroy_cart();
        echo json_encode([
            'status'    => true,
            'msg'       => 'La venta ha sido cancelada con éxito',
            'type'      => 'success'
        ]);
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
        $serie      = Serie::where('id', '!=', 1)->where('idtipo_documento', 2)->where('idcaja', $idcaja)->first();
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

        $idtipo_documento   = $request->input('idtipo_documento');
        $idcaja             = Auth::user()['idcaja'];

        if (empty($idtipo_documento)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Seleccione un tipo de comprobante',
                'type'      => 'warning'
            ]);
            return;
        }

        $serie                      = Serie::where('idtipo_documento', $idtipo_documento)->where('idcaja', $idcaja)->first();
        switch ($idtipo_documento) {
            case '1':
                $clientes                   = Client::where('iddoc', 4)->get();
                break;

            case '2':
                $clientes                   = Client::where('iddoc', 1)->orWhere('iddoc', 2)->get();
                break;

            case '7':
                $clientes                   = Client::where('iddoc', 1)->orWhere('iddoc', 2)->orWhere('iddoc', 4)->get();
                break;
        }

        echo json_encode(['status'  => true, 'serie'  => $serie, 'clientes' => $clientes, 'idtipo_documento' => $idtipo_documento]);
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

        $cart               = $this->create_cart();
        if (empty($cart['products'])) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe ingresar al menos 1 producto',
                'type'      => 'warning'
            ]);
            return;
        }

        echo json_encode([
            'status'    => true,
            'cart'      => $cart
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

        $iddocumento_tipo       = $request->input('iddocumento_tipo');
        $dni_ruc                = $request->input('dni_ruc');
        $modo_pago              = $request->input('modo_pago');
        $difference             = $request->input('difference');
        $serie_sale             = explode('-', $request->input('serie_sale'));
        $serie                  = $serie_sale[0];
        $correlativo            = $serie_sale[1];
        $fecha_emision          = date('Y-m-d');
        $fecha_vencimiento      = date('Y-m-d');
        $cart                   = $this->create_cart();
        $id_arching             = ArchingCash::where('idcaja', Auth::user()['idcaja'])->where('idusuario', Auth::user()['id'])->latest('id')->first()['id'];
        // Detail payments
        $quantity_paying        = number_format($request->input('quantity_paying'), 2, ".", "");
        $quantity_paying_2      = number_format($request->input('quantity_paying_2'), 2, ".", "");
        $quantity_paying_3      = number_format($request->input('quantity_paying_3'), 2, ".", "");
        $pay_mode               = $modo_pago;
        $pay_mode_2             = $request->input('modo_pago_2');
        $pay_mode_3             = $request->input('modo_pago_3');
        $id_sale                = NULL;
        $idalmacen              = Auth::user()['idalmacen'];

        if (empty($dni_ruc)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Seleccione un cliente',
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
                'exonerada'             => $cart["exonerada"],
                'inafecta'              => $cart["inafecta"],
                'gravada'               => $cart["gravada"],
                'anticipo'              => "0.00",
                'igv'                   => $cart['igv'],
                'gratuita'              => "0.00",
                'otros_cargos'          => "0.00",
                'total'                 => $cart['total'],
                'estado'                => 1,
                'idusuario'             => Auth::user()['id'],
                'idcaja'                => $id_arching,
                'vuelto'                => $difference
            ]);

            $idfactura                  = SaleNote::latest('id')->first()['id'];
            // Detail
            foreach ($cart['products'] as $product) {
                DetailSaleNote::insert([
                    'idnotaventa'           => $idfactura,
                    'idproducto'            => $product['id'],
                    'cantidad'              => $product['cantidad'],
                    'descuento'             => 0.0000000000,
                    'igv'                   => $product["igv"],
                    'id_afectacion_igv'     => $product['idcodigo_igv'],
                    'precio_unitario'       => $product['precio_venta'],
                    'precio_total'          => ($product['precio_venta'] * $product['cantidad'])
                ]);

                if ($product["stock"] != NULL) {
                    Product::where('id', $product["id"])->update([
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
                    'idcaja'                => $id_arching,
                    'estado'                => 1
                ]);
            }

            if ($quantity_paying_2 != "0.00") {
                DetailPayment::insert([
                    'idtipo_comprobante'    => $iddocumento_tipo,
                    'idfactura'             => $idfactura,
                    'idpago'                => $pay_mode_2,
                    'monto'                 => $quantity_paying_2,
                    'idcaja'                => $id_arching,
                    'estado'                => 1
                ]);
            }

            if ($quantity_paying_3 != "0.00") {
                DetailPayment::insert([
                    'idtipo_comprobante'    => $iddocumento_tipo,
                    'idfactura'             => $idfactura,
                    'idpago'                => $pay_mode_3,
                    'monto'                 => $quantity_paying_3,
                    'idcaja'                => $id_arching,
                    'estado'                => 1
                ]);
            }


            // Gen ticket data to pdf
            $factura                        = SaleNote::where('id', $idfactura)->first();
            $ruc                            = Business::where('id', 1)->first()->ruc;
            $codigo_comprobante             = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
            $name                           = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;
            $id_sale                        = $idfactura;
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
            $qr                         = $business->ruc . ' | ' . $type_document->codigo . ' | ' . $serie . ' | ' . $correlativo . ' | ' . number_format($cart["igv"], 2, ".", "") . ' | ' . number_format($cart["total"], 2, ".", "") . ' | ' . $fecha_emision . ' | ' . $identity_document->codigo . ' | ' . $client->dni_ruc;
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
                'exonerada'             => $cart["exonerada"],
                'inafecta'              => $cart["inafecta"],
                'gravada'               => $cart["gravada"],
                'anticipo'              => "0.00",
                'igv'                   => $cart['igv'],
                'gratuita'              => "0.00",
                'otros_cargos'          => "0.00",
                'total'                 => $cart['total'],
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

            foreach ($cart['products'] as $product) {
                DetailBilling::insert([
                    'idfacturacion'         => $idfactura,
                    'idproducto'            => $product['id'],
                    'cantidad'              => $product['cantidad'],
                    'descuento'             => 0.0000000000,
                    'igv'                   => $product["igv"],
                    'id_afectacion_igv'     => $product['idcodigo_igv'],
                    'precio_unitario'       => $product['precio_venta'],
                    'precio_total'          => ($product['precio_venta'] * $product['cantidad'])
                ]);

                if ($product["stock"] != NULL) {
                    Product::where('id', $product["id"])->update([
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
                    'idcaja'                => $id_arching,
                    'estado'                => 1
                ]);
            }

            if ($quantity_paying_2 != "0.00") {
                DetailPayment::insert([
                    'idtipo_comprobante'    => $iddocumento_tipo,
                    'idfactura'             => $idfactura,
                    'idpago'                => $pay_mode_2,
                    'monto'                 => $quantity_paying_2,
                    'idcaja'                => $id_arching,
                    'estado'                => 1
                ]);
            }

            if ($quantity_paying_3 != "0.00") {
                DetailPayment::insert([
                    'idtipo_comprobante'    => $iddocumento_tipo,
                    'idfactura'             => $idfactura,
                    'idpago'                => $pay_mode_3,
                    'monto'                 => $quantity_paying_3,
                    'idcaja'                => $id_arching,
                    'estado'                => 1
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


        $this->destroy_cart();

        echo json_encode([
            'status'        => true,
            'id'            => $id_sale,
            'pdf'           => $name . '.pdf',
            'type_document' => $iddocumento_tipo
        ]);
    }

    public function test()
    {
        $id         = 1;
        $factura    = DB::select("CALL get_detail_bf(?)", [$id]);
    }

    ## Functions to cart
    public function create_cart()
    {
        if (!session()->get('pos') || empty(session()->get('pos')['products'])) {
            $pos =
                [
                    'pos' =>
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

            session($pos);
            return session()->get('pos');
        }

        $exonerada  = 0;
        $gravada    = 0;
        $inafecta   = 0;
        $subtotal   = 0;
        $total      = 0;
        $igv        = 0;

        foreach (session('pos')['products'] as $index => $product) {
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
            session()->put('pos.products.' . $index, $product);
        }

        $total      = $subtotal + $igv;

        $pos =
            [
                'pos' =>
                [
                    'products'     => session('pos')['products'],
                    'igv'          => $igv,
                    'exonerada'    => $exonerada,
                    'gravada'      => $gravada,
                    'inafecta'     => $inafecta,
                    'subtotal'     => $subtotal,
                    'total'        => $total,
                ]
            ];

        session($pos);
        return session()->get('pos');
    }

    public function add_product_cart($id, $cantidad, $precio, $opcion)
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

        if ($opcion == 1) {
            if ($product->stock != NULL) {
                if ($product->stock < $cantidad) {
                    return false;
                } elseif ($product->stock == 0) {
                    return false;
                }
            }
        }

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
                'stock'             => $product->stock,
                'cantidad'          => $cantidad,
                'opcion'            => $opcion
            ];

        if (empty(session()->get('pos')['products'])) {
            session()->push('pos.products', $new_product);
            return true;
        }

        foreach (session()->get('pos')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                if($opcion == 1) {
                    if ($product["stock"] != NULL) {
                        if ($product["stock"] < ($product['cantidad'] + $cantidad)) {
                            return false;
                        }
                    }
                }
                $product['cantidad'] = $product['cantidad'] + $cantidad;
                session()->put('pos.products.' . $index, $product);
                return true;
            }
        }

        session()->push('pos.products', $new_product);
        return true;
    }

    public function delete_product_cart($id, $opcion)
    {
        if (!session()->get('pos') || empty(session()->get('pos')['products'])) {
            return false;
        }

        foreach (session()->get('pos')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                session()->forget('pos.products.' . $index, $product);
                return true;
            }
        }
    }

    public function update_quantity($id, $cantidad, $precio, $opcion)
    {
        if (empty(session()->get('pos')['products'])) {
            return false;
        }

        foreach (session()->get('pos')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                if($opcion == 1) {
                    if ($product["stock"] != NULL) {
                        if ($product["stock"] < $cantidad) {
                            return false;
                        } elseif ($product["stock"] == 0) {
                            return false;
                        }
                    }
                }

                $product['cantidad']           =  $cantidad;
                $product['precio_venta']       =  $precio;
                session()->put('pos.products.' . $index, $product);
                return true;
            }
        }
    }

    public function destroy_cart()
    {
        if (!session()->get('pos') || empty(session()->get('pos')['products'])) {
            return false;
        }

        session()->forget('pos');
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
}

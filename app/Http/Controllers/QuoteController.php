<?php

namespace App\Http\Controllers;

use App\Models\ArchingCash;
use App\Models\Billing;
use App\Models\Business;
use App\Models\Client;
use App\Models\Currency;
use App\Models\DetailBilling;
use App\Models\DetailPayment;
use App\Models\DetailQuote;
use App\Models\IdentityDocumentType;
use App\Models\IgvTypeAffection;
use App\Models\PayMode;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Serie;
use App\Models\TypeDocument;
use App\Models\Unit;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    public function index()
    {
        return view('admin.quotes.list');
    }

    public function get()
    {
        $quotes     = Quote::select('quotes.*', 'clients.dni_ruc as dni_ruc', 'clients.nombres as cliente', 
                    'type_documents.descripcion as tipo_documento')
                    ->join('clients', 'quotes.idcliente', '=', 'clients.id')
                    ->join('type_documents', 'quotes.idtipo_comprobante', 'type_documents.id')
                    ->where('idtipo_comprobante', '!=', 6)
                    ->orderBy('id', 'DESC')
                    ->get();

        return Datatables()
                    ->of($quotes)
                    ->addColumn('cliente', function ($quotes) {
                        $cliente  = $quotes->cliente;
                        return $cliente;
                    })
                    ->addColumn('documento', function ($quotes) {
                        $documento  = $quotes->serie . '-' . $quotes->correlativo;
                        return $documento;
                    })
                    ->addColumn('fecha_de_emision', function ($quotes) {
                        $fecha_emision = date('d-m-Y', strtotime($quotes->fecha_emision));
                        return $fecha_emision;
                    })
                    ->addColumn('acciones', function($quotes){
                        /* <a class="dropdown-item btn-print" data-id="'.$id.'" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            <span> Actualizar</span>
                        </a> 
                        <a class="dropdown-item btn-open-whatsapp" data-id="'.$id.'" href="javascript:void(0);">
                                            <i class="fa-brands fa-whatsapp"></i>
                                            <span> Enviar Documento</span>
                                        </a>*/
                        $id     = $quotes->id;
                        $idtipo_comprobante = $quotes->idtipo_comprobante;
                        $btn    = '<div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                            <div class="dropdown-menu">
                                        <a class="dropdown-item" href="'.route("admin.edit_quote", $id).'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50 menu-icon"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            <span> Editar</span>
                                        </a>
                                        <a class="dropdown-item btn-print" data-id="'.$id.'" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer mr-50 menu-icon"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                            <span> Imprimir</span>
                                        </a>
                                        <a class="dropdown-item btn-download" data-id="'.$id.'" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download mr-50 menu-icon"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                            <span>Descargar PDF A4</span>
                                        </a>
                                        <a class="dropdown-item btn-confirm" data-idtipo_comprobante="'.$idtipo_comprobante.'" data-id="'.$id.'" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file mr-50 menu-icon"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                            <span>Generar Boleta/Factura</span>
                                        </a>
                                        <a class="dropdown-item btn-open-whatsapp" data-id="'. $id .'" href="javascript:void(0);">
                                            <i class="fa-brands fa-whatsapp" style="margin-right: 0.5rem;"></i>
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
        $data['clients']            = Client::where('iddoc', 4)->get();
        $data['type_documents_p']   = TypeDocument::where('estado', 1)->limit(2)->get();
        $data['type_documents']     = IdentityDocumentType::where('estado', 1)->get();
        $data['modo_pagos']         = PayMode::get();
        $data['products']           = Product::get();
        $data["units"]              = Unit::where('estado', 1)->get();
        $data['type_inafects']      = IgvTypeAffection::where('estado', 1)->get();
        return view('admin.quotes.create', $data);
    }

    public function edit($id)
    {
        $data["type_documents_p"]   = TypeDocument::where('estado', 1)->limit(2)->get();
        $data["quote"]              = Quote::where('id', $id)->first();
        $data["client"]             = Client::where('id', $data["quote"]->idcliente)->first();
        $data["clients"]            = Client::where('iddoc', $data["client"]->iddoc)->get();
        $data["type_documents"]     = IdentityDocumentType::where('estado', 1)->get();
        $data['products']           = Product::get();
        $data["modo_pagos"]         = PayMode::get();
        $data["units"]              = Unit::where('estado', 1)->get();
        $data["type_inafects"]      = IgvTypeAffection::where('estado', 1)->get();
        $data["detalle"]            = DetailQuote::select('detail_quotes.*', 'products.descripcion as producto',
                                    'products.codigo_interno as codigo_interno','units.codigo as unidad', 'products.idcodigo_igv as idcodigo_igv', 'igv_type_affections.codigo as codigo_igv',
                                    'products.igv as igv', 'products.opcion as opcion', 'products.impuesto as impuesto')
                                    ->join('products', 'detail_quotes.idproducto', '=', 'products.id')
                                    ->join('units', 'products.idunidad', '=', 'units.id')
                                    ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
                                    ->where('detail_quotes.idcotizacion', $id)
                                    ->get();
        return view('admin.quotes.edit', $data);
    }

    public function get_product_update(Request $request)
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
        $producto           = Product::select(
                            'products.id', 'products.precio_venta','products.descripcion as producto', 
                            'products.codigo_interno as codigo_interno', 
                            'units.codigo as unidad', 'products.idcodigo_igv as idcodigo_igv', 'igv_type_affections.codigo as codigo_igv',
                            'products.igv as igv', 'products.opcion as opcion', 'products.impuesto as impuesto', 'products.stock as stock')
                            ->join('units', 'products.idunidad', '=', 'units.id')
                            ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
                            ->where('products.id', $id)
                            ->first();

        $cantidad           = (int) $request->input('cantidad');
        $precio             = number_format($request->input('precio'), 2, ".", "");
     
        if($producto->opcion == 1) 
        {
            if($producto->stock != null)
            {
                if((int) $producto->stock <= 0)
                {
                    echo json_encode([
                        'status'    => false,
                        'msg'       => 'Stock insuficiente',
                        'type'      => 'warning'
                    ]);
                    return;
                }
            }
        }

        echo json_encode([
            'status'    => true,
            'msg'       => 'Producto agregado correctamente',
            'type'      => 'success',
            'producto'  => $producto,
            'cantidad'  => $cantidad
        ]);
    }

    public function store_product_update(Request $request)
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
        $idalmacen          = (int) $request->input('idalmacen');
        $producto           = Product::select(
                            'products.id', 'products.precio_venta','products.descripcion as producto', 
                            'products.codigo_interno as codigo_interno', 
                            'units.codigo as unidad', 'products.idcodigo_igv as idcodigo_igv', 'igv_type_affections.codigo as codigo_igv',
                            'products.igv as igv', 'products.opcion as opcion', 'products.impuesto as impuesto', 'products.stock as stock')
                            ->join('units', 'products.idunidad', '=', 'units.id')
                            ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
                            ->where('products.id', $id)
                            ->first();

        $cantidad           = (int) $request->input('cantidad');
        $precio             = number_format($request->input('precio'), 2, ".", "");
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

    public function gen_update(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }
 
        $idquote                = (int) $request->input('idquote');
        $idtipo_comprobante     = $request->input('idtipo_comprobante');
        $fecha_emision          = date('Y-m-d');
        $fecha_vencimiento      = date('Y-m-d');
        $idcliente              = $request->input('idcliente');
        $tipo_cambio            = $request->input('tipo_cambio');
        $modo_pago              = $request->input('modo_pago');
        $products               = json_decode($request->post('productos'));
        $totales                = json_decode($request->post('totales'));
        if (empty($products)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe ingresar al menos 1 producto',
                'type'      => 'warning'
            ]);
            return;
        }

        $registros                  = DetailQuote::where('idcotizacion', $idquote)->get();
        $existingIdentifiers        = $registros->pluck('idproducto')->toArray();
        $array_ids                  = [];
        $array_precio               = [];
        $array_cantidad             = [];
        foreach($products as $producto) 
        {
            $array_ids[]            = $producto->idproducto;
            $array_precio[]         = $producto->precio;
            $array_cantidad[]       = $producto->cantidad;
        }
     
        foreach($existingIdentifiers as $i => $id_db)
        {
            if(in_array($id_db, $array_ids)) {
                foreach($products as $producto) {
                    DetailQuote::updateOrCreate([
                        'idcotizacion'      => $idquote,
                        'idproducto'        => $producto->idproducto
                    ], [
                        'idcotizacion'      => $idquote,
                        'idproducto'        => $producto->idproducto,
                        'cantidad'          => $producto->cantidad,
                        'precio_unitario'   => $producto->precio,
                        'precio_total'      => ($producto->precio * $producto->cantidad)
                    ]);
                }
            } 
            else {
                DetailQuote::where([
                    'idcotizacion'      => $idquote,
                    'idproducto'        => $id_db
                ])->delete();
            }
        }
 
        Quote::where('id', $idquote)->update([
            'idtipo_comprobante'    => $idtipo_comprobante,
            'fecha_emision'         => $fecha_emision,
            'fecha_vencimiento'     => $fecha_vencimiento,
            'hora'                  => date('H:i:s'),
            'idcliente'             => $idcliente,
            'idmoneda'              => 1,
            'idpago'                => 1,
            'modo_pago'             => $modo_pago,
            'exonerada'             => $totales->exonerada,
            'inafecta'              => $totales->inafecta,
            'gravada'               => $totales->gravada,
            'anticipo'              => "0.00",
            'igv'                   => $totales->igv,
            'gratuita'              => "0.00",
            'otros_cargos'          => "0.00",
            'total'                 => $totales->total,
            'observaciones'         => "",
            'estado'                => 1,
            'idusuario'             => Auth::user()['id'],
            'idcaja'                => Auth::user()['idcaja'],
        ]);

        $id                         = $idquote;
        $data["quote"]              = Quote::where('id', $id)->first();
        $data["business"]           = Business::where('id', 1)->first();
        $data["client"]             = Client::where('id', $data["quote"]["idcliente"])->first();
        $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
        $data["name_quote"]         = mb_strtoupper( $data["client"]->dni_ruc . '-' . $data["quote"]["serie"]) . '-' . $data["quote"]["correlativo"];
        $data["type_document"]      = TypeDocument::where('id', $data["quote"]["idtipo_comprobante"])->first();
        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["quote"]->total, 2);
        $data["detail"]             = DetailQuote::select('detail_quotes.*', 'products.descripcion as producto',
                                    'products.codigo_interno as codigo_interno','units.codigo as unidad')
                                    ->join('products', 'detail_quotes.idproducto', '=', 'products.id')
                                    ->join('units', 'products.idunidad', '=', 'units.id')
                                    ->where('detail_quotes.idcotizacion', $id)
                                    ->get();

        $this->gen_pdf($data, $data["name_quote"]);
        echo json_encode([
            'status'                => true,
            'pdf'                   => $data["name_quote"] . '.pdf',
            'idtipo_comprobante'    => $idtipo_comprobante,
            'idcotizacion'          => $idquote
        ]);
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
            $clients                        = Client::where('iddoc', 1)->orWhere('iddoc', 2)->orderBy('id', 'DESC')->get();
        else
            $clients                        = Client::where('iddoc', 4)->orderBy('id', 'DESC')->get();

        echo json_encode(['status'  => true, 'serie'  => $serie, 'clients' => $clients]);
    }

    public function get_clients(Request $request)
    {
        $clientes                       = Client::orderBy('id', 'DESC')->get();
        return $clientes;
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

        if (!empty($cart['products'])) 
        {
            foreach ($cart['products'] as $i => $product) {
                $contador   = $contador + 1;
                $html_cart .= '<tr>
                                <td class="text-center">' . $contador . '</td>
                                <td>' . $product["descripcion"] . '</td>
                                <td class="text-center">' . $product["unidad"] . '</td>
                                <td class="text-right">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text btn-down" style="cursor: pointer;" data-id="' . $product["id"] . '" data-cantidad="' . $product["cantidad"] . '" data-precio="' . $product["precio_venta"] . '"><i class="ti ti-minus me-sm-1"></i></span>
                                        <input type="text" data-id="' . $product["id"] . '" class="quantity-counter text-center form-control" value="' . $product["cantidad"] . '">
                                        <span class="input-group-text btn-up" style="cursor: pointer;" data-id="' . $product["id"] . '" data-cantidad="' . $product["cantidad"] . '" data-precio="' . $product["precio_venta"] . '"><i class="ti ti-plus me-sm-1"></i></span>
                                    </div>
                                </td>
                                <td class="text-center"><input type="text" class="form-control form-control-sm text-center input-update" value="' . number_format($product["precio_venta"], 2, ".", "") . '" data-cantidad="'.$product["cantidad"].'" data-id="' . $product["id"] . '" name="precio"></td>
                                <td class="text-center">' . number_format(($product["precio_venta"] * $product["cantidad"]), 2, ".", "") . '</td>
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
        $producto       = Product::where('id', $id)->first();
        $opcion         = (int) $producto->opcion;
        $cantidad       = (int) $request->input('cantidad');
        $precio         = number_format($request->input('precio'), 2, ".", "");

        if(!$this->add_product_cart($id, $cantidad, $precio, $opcion))
        {   
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
        if(!$request->ajax())
        {
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
        if(!$this->delete_product_cart($id, $opcion))
        {
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
        if(!$request->ajax())
        {
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
        if(!$this->update_quantity($id , $cantidad, $precio, $opcion))
        {
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
        if(!$request->ajax())
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idtipo_comprobante     = $request->input('idtipo_comprobante');
        $fecha_emision          = $request->input('fecha_emision');
        $fecha_vencimiento      = $request->input('fecha_vencimiento');
        $idcliente              = $request->input('dni_ruc');
        $tipo_cambio            = $request->input('tipo_cambio');
        $modo_pago              = $request->input('modo_pago');
        $cart                   = $this->create_cart(); 
        $ultimo_correlativo     = NULL;
        
        if(empty($idcliente))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe seleccionar el cliente',
                'type'      => 'warning'
            ]);
            return;
        }

        if(empty($cart['products']))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe ingresar al menos 1 producto',
                'type'      => 'warning'
            ]);
            return;
        }

        if(Quote::count() == 0)
        $correlativo = str_pad(1, 8, '0', STR_PAD_LEFT);
        else
        $ultimo_correlativo = Quote::latest('id')->first()["correlativo"];
        $correlativo = str_pad($ultimo_correlativo + 1, 8, '0', STR_PAD_LEFT);

        Quote::insert([
            'idtipo_comprobante'    => $idtipo_comprobante,
            'correlativo'           => $correlativo,
            'fecha_emision'         => $fecha_emision,
            'fecha_vencimiento'     => $fecha_vencimiento,
            'hora'                  => date('H:i:s'),
            'idcliente'             => $idcliente,
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

        $idquote                    = Quote::latest('id')->first()['id'];
        foreach($cart["products"] as $product)
        {
            DetailQuote::insert([
                'idcotizacion'      => $idquote,
                'idproducto'        => $product['id'],
                'cantidad'          => $product['cantidad'],
                'precio_unitario'   => $product['precio_venta'],
                'precio_total'      => ($product['precio_venta'] * $product['cantidad']),
            ]);
        }
        $this->destroy_cart();

        // PDF
        $id                         = $idquote;
        $data["quote"]              = Quote::where('id', $id)->first();
        $data["business"]           = Business::where('id', 1)->first();
        $data["client"]             = Client::where('id', $data["quote"]["idcliente"])->first();
        $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
        $data["name_quote"]         = mb_strtoupper( $data["client"]->dni_ruc . '-' . $data["quote"]["serie"]) . '-' . $data["quote"]["correlativo"];
        $data["type_document"]      = TypeDocument::where('id', $data["quote"]["idtipo_comprobante"])->first();
        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["quote"]->total, 2);
        $data["detail"]             = DetailQuote::select('detail_quotes.*', 'products.descripcion as producto',
                                    'products.codigo_interno as codigo_interno','units.codigo as unidad')
                                    ->join('products', 'detail_quotes.idproducto', '=', 'products.id')
                                    ->join('units', 'products.idunidad', '=', 'units.id')
                                    ->where('detail_quotes.idcotizacion', $id)
                                    ->get();

        $this->gen_pdf($data, $data["name_quote"]);
        echo json_encode([
            'status'                => true,
            'pdf'                   => $data["name_quote"] . '.pdf',
            'idtipo_comprobante'    => $idtipo_comprobante,
            'idcotizacion'          => $idquote
        ]);
    }

    public function print_quote(Request $request)
    {
        if(!$request->ajax())
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }


        $id                         = $request->input('id');
        $data["quote"]              = Quote::where('id', $id)->first();
        $data["business"]           = Business::where('id', 1)->first();
        $data["client"]             = Client::where('id', $data["quote"]["idcliente"])->first();
        $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
        $data["name_quote"]         = mb_strtoupper( $data["client"]->dni_ruc . '-' . $data["quote"]["serie"]) . '-' . $data["quote"]["correlativo"];
        $data["type_document"]      = TypeDocument::where('id', $data["quote"]["idtipo_comprobante"])->first();
        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["quote"]->total, 2);
        $data["detail"]             = DetailQuote::select('detail_quotes.*', 'products.descripcion as producto',
                                    'products.codigo_interno as codigo_interno','units.codigo as unidad')
                                    ->join('products', 'detail_quotes.idproducto', '=', 'products.id')
                                    ->join('units', 'products.idunidad', '=', 'units.id')
                                    ->where('detail_quotes.idcotizacion', $id)
                                    ->get();

        $this->gen_pdf($data, $data["name_quote"]);
        echo json_encode([
            'status'    => true,
            'pdf'       => $data["name_quote"] . '.pdf'
        ]);
    }

    public function download($id)
    {
        $data["quote"]              = Quote::where('id', $id)->first();
        $data["business"]           = Business::where('id', 1)->first();
        $data["client"]             = Client::where('id', $data["quote"]["idcliente"])->first();
        $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
        $data["name_quote"]         = mb_strtoupper( $data["client"]->dni_ruc . '-' . $data["quote"]["serie"]) . '-' . $data["quote"]["correlativo"];
        $data["type_document"]      = TypeDocument::where('id', $data["quote"]["idtipo_comprobante"])->first();
        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["quote"]->total, 2);
        $data["detail"]             = DetailQuote::select('detail_quotes.*', 'products.descripcion as producto',
                                    'products.codigo_interno as codigo_interno','units.codigo as unidad')
                                    ->join('products', 'detail_quotes.idproducto', '=', 'products.id')
                                    ->join('units', 'products.idunidad', '=', 'units.id')
                                    ->where('detail_quotes.idcotizacion', $id)
                                    ->get();

        $pdf    = PDF::loadView('admin.quotes.pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->download($data["name_quote"] . '.pdf');
    }

    public function gen_voucher(Request $request)
    {
        if(!$request->ajax()){
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                     = $request->input('id');
        $quote                  = Quote::where('id', $id)->first();
        $detalle                = DetailQuote::select('detail_quotes.*', 'products.descripcion as producto',
                                'products.codigo_interno as codigo_interno','units.codigo as unidad', 'products.idcodigo_igv as idcodigo_igv',
                                'products.igv as igv', 'products.opcion as opcion')
                                ->join('products', 'detail_quotes.idproducto', '=', 'products.id')
                                ->join('units', 'products.idunidad', '=', 'units.id')
                                ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
                                ->where('detail_quotes.idcotizacion', $id)
                                ->get();

        $client                 = Client::where('id', $quote->idcliente)->first();
        $idtipo_comprobante     = $request->input('idtipo_comprobante');
        $fecha_emision          = date('Y-m-d');
        $fecha_vencimiento      = date('Y-m-d');
        $id_arching             = ArchingCash::where('idcaja', Auth::user()['idcaja'])->where('idusuario', Auth::user()['id'])->latest('id')->first()['id'];

        // Save
        $business                   = Business::where('id', 1)->first();
        $type_document              = TypeDocument::where('id', $idtipo_comprobante)->first();
        $client                     = Client::where('id', $client->id)->first();
        $identity_document          = IdentityDocumentType::where('id', $client->iddoc)->first();

        $ultima_serie               = Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->first();
        $ultimo_correlativo         = (int) $ultima_serie->correlativo;
        $serie                      = $ultima_serie->serie;
        $correlativo                = str_pad($ultimo_correlativo, 8, '0', STR_PAD_LEFT);
        
        $qr                         = $business->ruc . ' | ' . $type_document->codigo . ' | ' . $serie . ' | ' . $correlativo . ' | ' . number_format($quote->igv, 2, ".", "") . ' | ' . number_format($quote->total, 2, ".", "") . ' | ' . $fecha_emision . ' | ' . $identity_document->codigo . ' | ' . $client->dni_ruc;
        $name_qr                    = $serie . '-' . $correlativo;

        // Gen Qr
        QrCode::format('png')
        ->size(140)
        ->generate($qr, 'files/billings/qr/' . $name_qr . '.png');

        Billing::insert([
            'idtipo_comprobante'    => $idtipo_comprobante,
            'serie'                 => $serie,
            'correlativo'           => $correlativo,
            'fecha_emision'         => $fecha_emision,
            'fecha_vencimiento'     => $fecha_vencimiento,
            'hora'                  => date('H:i:s'),
            'idcliente'             => $client->id,
            'idmoneda'              => 1,
            'idpago'                => 1,
            'modo_pago'             => $quote->modo_pago,
            'exonerada'             => $quote->exonerada,
            'inafecta'              => $quote->inafecta,
            'gravada'               => $quote->gravada,
            'anticipo'              => "0.00",
            'igv'                   => $quote->igv,
            'gratuita'              => "0.00",
            'otros_cargos'          => "0.00",
            'total'                 => $quote->total,
            'cdr'                   => 0,
            'anulado'               => 0,
            'id_tipo_nota_credito'  => null,
            'estado_cpe'            => 0,
            'errores'               => null,
            'nticket'               => null,
            'idusuario'             => Auth::user()['id'],
            'idcaja'                => $id_arching,
            'vuelto'                => "0.00",
            'qr'                    => $name_qr . '.png'
        ]);
        $idfactura                  = Billing::latest('id')->first()['id'];
        DetailPayment::insert([
            'idtipo_comprobante'    => $idtipo_comprobante,
            'idfactura'             => $idfactura,
            'idpago'                => $quote->modo_pago,
            'monto'                 => $quote->total,
            'idcaja'                => $id_arching
        ]);

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

            if($product["opcion"] == 1)
            {
                if($product["stock"] != NULL) {
                    Product::where('id', $product["id"])->update([
                        "stock"  => $product["stock"] - $product["cantidad"]
                    ]);
                }
            }
        }

        $factura                = Billing::where('id', $idfactura)->first();
        $ruc                    = Business::where('id', 1)->first()->ruc;
        $code_sale              = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $name_sale              = $ruc . '-' . $code_sale . '-' . $factura->serie . '-' . $factura->correlativo;
        $id_sale                = $idfactura;
        $this->gen_ticket_b($idfactura, $name_sale);

        $ultima_serie_sale      = Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->first();
        $ultimo_correlativo_sale= (int) $ultima_serie_sale->correlativo + 1;
        $nuevo_correlativo_sale = str_pad($ultimo_correlativo_sale, 8, '0', STR_PAD_LEFT);
        Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->update([
            'correlativo'   => $nuevo_correlativo_sale
        ]);

        echo json_encode([
            'status'        => true,
            'id'            => $id_sale,
            'pdf'           => $name_sale . '.pdf',
            'type_document' => $idtipo_comprobante
        ]);
    }

    public function gen_pdf($data, $name)
    {
        $pdf    = PDF::loadView('admin.quotes.pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->save(public_path('files/quotes/' . $name . '.pdf'));
    }

    public function test()
    {
        $pdf    = PDF::loadView('admin.quotes.test')->setPaper('A4', 'portrait');
        return $pdf->stream();
    }

    public function create_cart()
    {
        if (!session()->get('quote') || empty(session()->get('quote')['products'])) {
            $quote =
                [
                    'quote' =>
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

            session($quote);
            return session()->get('quote');
        }

        $exonerada  = 0;
        $gravada    = 0;
        $inafecta   = 0;
        $subtotal   = 0;
        $total      = 0;
        $igv        = 0;

        foreach (session('quote')['products'] as $index => $product) {
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
            session()->put('quote.products.' . $index, $product);
        }

        $total      = $subtotal + $igv;
        $quote =
            [
                'quote' =>
                [
                    'products'     => session('quote')['products'],
                    'igv'          => $igv,
                    'exonerada'    => $exonerada,
                    'gravada'      => $gravada,
                    'inafecta'     => $inafecta,
                    'subtotal'     => $subtotal,
                    'total'        => $total,
                ]
            ];

        session($quote);
        return session()->get('quote');
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

        if(!$product)
            return false;

        if($opcion == 1)
        {
            if($product->stock != NULL)
            {
                if ($product->stock < $cantidad)
                {
                    return false;
                }
                elseif($product->stock == 0)
                {
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

        if (empty(session()->get('quote')['products'])) {
            session()->push('quote.products', $new_product);
            return true;
        }

        foreach (session()->get('quote')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                if($opcion == 1) {
                    if ($product["stock"] != NULL) {
                        if ($product["stock"] < ($product['cantidad'] + $cantidad)) {
                            return false;
                        }
                    }
                }
                $product['cantidad'] = $product['cantidad'] + $cantidad;
                session()->put('quote.products.' . $index, $product);
                return true;
            }
        }

        session()->push('quote.products', $new_product);
        return true;
    }

    public function delete_product_cart($id, $opcion)
    {
        if (!session()->get('quote') || empty(session()->get('quote')['products'])) {
            return false;
        }

        foreach (session()->get('quote')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                session()->forget('quote.products.' . $index, $product);
                return true;
            }
        }
    }

    public function update_quantity($id, $cantidad, $precio, $opcion)
    {
        if (empty(session()->get('quote')['products'])) {
            return false;
        }

        foreach (session()->get('quote')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                if ($product["stock"] != NULL) {
                    if ($product["stock"] < $cantidad) {
                        return false;
                    } elseif ($product["stock"] == 0) {
                        return false;
                    }
                }
                $product['cantidad']           =  $cantidad;
                $product['precio_venta']       =  $precio;
                session()->put('quote.products.' . $index, $product);
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
        if (!session()->get('quote') || empty(session()->get('quote')['products'])) {
            return false;
        }

        session()->forget('quote');
        return true;
    }

    public function get_products_update()
    {
        $products           = Product::orderBy('id', 'DESC')->get();
        return $products;
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

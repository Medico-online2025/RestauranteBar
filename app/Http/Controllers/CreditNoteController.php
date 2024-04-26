<?php

namespace App\Http\Controllers;

use App\Models\ArchingCash;
use App\Models\Billing;
use App\Models\Business;
use App\Models\Client;
use App\Models\CreditNoteType;
use App\Models\Currency;
use App\Models\DetailBilling;
use App\Models\DetailPayment;
use App\Models\IdentityDocumentType;
use App\Models\PayMode;
use App\Models\Product;
use App\Models\Serie;
use App\Models\TypeDocument;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreditNoteController extends Controller
{
    public function index()
    {
        return view('admin.credit_notes.list');
    }

    public function get()
    {
        $billings    = DB::select("CALL get_list_credit_notes()");
        return Datatables()
            ->of($billings)
            ->addColumn('cliente', function ($billings) {
                $cliente  = $billings->nombre_cliente;
                return $cliente;
            })
            ->addColumn('documento', function ($billings) {
                $documento  = $billings->serie . '-' . $billings->correlativo;
                return $documento;
            })
            ->addColumn('fecha_de_emision', function ($billings) {
                $fecha_emision = date('d-m-Y', strtotime($billings->fecha_emision));
                return $fecha_emision;
            })
            ->addColumn('xml', function ($billings) {
                $cdr            = $billings->cdr;
                $type_document  = TypeDocument::where('id', $billings->idtipo_comprobante)->first()->codigo;
                $business       = Business::where('id', 1)->first();
                $url_api        = $business->url_api;
                $ruc            = $business->ruc;
                $name_file      = $ruc . '-' . $type_document . '-' . $billings->serie . '-' . $billings->correlativo . '.xml';
                $btn            = '';
                switch ($cdr) {
                    case '0':
                        $btn = '';
                        break;

                    case '1':
                        $btn = '<a target="_blank" href="' . $url_api . 'Xml/xml-firmados/' . $name_file . '" class="text-center text-primary"><i class="far fa-file-code"></i></a>';
                        break;
                }
                return $btn;
            })
            ->addColumn('cdr', function ($billings) {
                $cdr            = $billings->cdr;
                $type_document  = TypeDocument::where('id', $billings->idtipo_comprobante)->first()->codigo;
                $business       = Business::where('id', 1)->first();
                $ruc            = $business->ruc;
                $url_api        = $business->url_api;
                $btn            = '';
                $name_file      = 'R-' . $ruc . '-' . $type_document . '-' . $billings->serie . '-' . $billings->correlativo . '.xml';
                switch ($cdr) {
                    case '0':
                        $btn .= '';
                        break;

                    case '1':
                        $btn .= '<a target="_blank" href="' . $url_api . 'Cdr/' . $name_file . '" class="text-center text-primary"><i class="fas fa-file-alt"></i></a>';
                        break;
                }
                return $btn;
            })
            ->addColumn('estado_sunat', function ($billings) {
                $cdr    = $billings->cdr;
                $btn    = '';
                switch ($cdr) {
                    case '0':
                        $btn .= '<span class="badge bg-warning text-white">Sin enviar</span>';
                        break;

                    case '1':
                        $btn .= '<span class="badge bg-success text-white">Aceptado</span>';
                        break;

                    case '2':
                        $btn .= '<span class="badge bg-danger text-white">Rechazado</span>';
                        break;
                }
                return $btn;
            })
            ->addColumn('estado_cpe', function ($billings) {
                $estado_cpe    = $billings->estado_cpe;
                $btn           = '';
                switch ($estado_cpe) {
                    case '0':
                        $btn .= '<span class="badge text-white" style="background-color: rgb(108, 117, 125);">Registrado</span>';
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
            ->addColumn('acciones', function ($billings) {
                $id     = $billings->id;
                $btn    = '<div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item btn-send-sunat" data-id="' . $id . '" href="javascript:void(0);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send mr-50 menu-icon"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                                <span> Enviar a SUNAT</span>
                                            </a>
                                            <a class="dropdown-item btn-print" data-id="' . $id . '" href="javascript:void(0);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer mr-50 menu-icon"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                <span> Imprimir Ticket</span>
                                            </a>
                                            <a class="dropdown-item btn-download" data-id="' . $id . '" href="javascript:void(0);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download mr-50 menu-icon"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                                <span>Descargar PDF A4</span>
                                            </a>
                                        </div>
                                    </div>';
                return $btn;
            })
            ->rawColumns(['fecha_de_emision', 'xml', 'cdr', 'documento', 'cliente', 'estado_sunat', 'estado_cpe', 'acciones'])
            ->make(true);
    }

    public function create($id)
    {
        $idcaja                     = Auth::user()['idcaja'];
        $data['idfactura_anular']   = $id;
        $data['type_documents']     = TypeDocument::where('id', 6)->where('estado', 1)->get();
        $data['currencies']         = Currency::get();
        $data['factura']            = Billing::select('billings.*', 'clients.nombres as cliente')
                                    ->join('clients', 'billings.idcliente', '=', 'clients.id')
                                    ->where('billings.id', $id)->first();
        $data['modo_pagos']         = PayMode::get();
        
        $data['detalle']            = DetailBilling::select(
                                        'detail_billings.*',
                                        'products.descripcion as producto',
                                        'products.codigo_interno as codigo_interno',
                                        'units.codigo as unidad'
                                    )
                                        ->join('products', 'detail_billings.idproducto', '=', 'products.id')
                                        ->join('units', 'products.idunidad', '=', 'units.id')
                                        ->where('idfacturacion', $id)
                                        ->get();

        $data['type_credit_notes']  = CreditNoteType::where('estado', 1)->get();
        $data['total']              = 0;
        $data['igv']                = 0;

        
        if ($data['factura']->idtipo_comprobante == 1) // Factura
            $data['serie']      = Serie::where('idtipo_documento_relacionado', 1)->where('idcaja', $idcaja)->first();
        else
            $data['serie']      = Serie::where('idtipo_documento_relacionado', 2)->where('idcaja', $idcaja)->first();
        return view('admin.credit_notes.create', $data);
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

        $idfactura_anular   = $request->input('idfactura_anular');
        $factura_anular     = DB::select("CALL get_bf(?)", [$idfactura_anular])[0];
        $serie              = $request->input('serie');
        $correlativo        = $request->input('correlativo');
        $fecha_emision      = date('Y-m-d');
        $fecha_vencimiento  = date('Y-m-d');
        $motivo             = trim($request->input('motivo'));
        $tipo_nc            = $request->input('tipo_nc');
        $modo_pago          = $factura_anular->modo_pago;
        $valid_nc           = Billing::where('idtipo_comprobante', 6)->where('idfactura_anular', $idfactura_anular)->first();
        $id_arching         = ArchingCash::where('idcaja', Auth::user()['idcaja'])->where('idusuario', Auth::user()['id'])->latest('id')->first()['id'];
        $pagos_anular       = DetailPayment::where('idtipo_comprobante', $factura_anular->idtipo_comprobante)->where('idfactura', $idfactura_anular)->get();

        if(!empty($valid_nc))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'El comprobante se encuentra anulado',
                'type'      => 'warning'
            ]);
            return;
        } 

        // Gen Qr
        $business                   = Business::where('id', 1)->first();
        $type_document              = TypeDocument::where('id', 6)->first();
        $client                     = Client::where('id', $factura_anular->idcliente)->first();
        $identity_document          = IdentityDocumentType::where('id', $client->iddoc)->first();
        $qr                         = $business->ruc . ' | ' . $type_document->codigo . ' | ' . $serie . ' | ' . $correlativo . ' | ' .number_format($factura_anular->igv, 2, ".", "") . ' | ' . number_format($factura_anular->total, 2, ".", "") . ' | ' . $fecha_emision . ' | ' .$identity_document->codigo . ' | ' . $client->dni_ruc;
        $name_qr                    = $serie . '-' . $correlativo;
        QrCode::format('png')
        ->size(140)
        ->generate($qr, 'files/billings/qr/' . $name_qr . '.png');

        Billing::insert([
            'idtipo_comprobante'    => 6,
            'serie'                 => $serie,
            'correlativo'           => $correlativo,
            'fecha_emision'         => $fecha_emision,
            'fecha_vencimiento'     => $fecha_vencimiento,
            'hora'                  => date('H:i:s'),
            'idcliente'             => $factura_anular->idcliente,
            'idmoneda'              => $factura_anular->idmoneda,
            'idpago'                => 1,
            'modo_pago'             => $modo_pago,
            'exonerada'             => $factura_anular->exonerada,
            'inafecta'              => $factura_anular->inafecta,
            'gravada'               => $factura_anular->gravada,
            'anticipo'              => "0.00",
            'igv'                   => $factura_anular->igv,
            'gratuita'              => "0.00",
            'otros_cargos'          => "0.00",
            'total'                 => $factura_anular->total,
            'observaciones'         => $factura_anular->observaciones,
            'cdr'                   => 0,
            'anulado'               => 0,
            'id_tipo_nota_credito'  => $tipo_nc,
            'idfactura_anular'      => $idfactura_anular,
            'motivo'                => strtoupper($motivo),
            'estado_cpe'            => 0,
            'errores'               => null,
            'nticket'               => null,
            'idusuario'             => Auth::user()['id'],
            'idcaja'                => $id_arching,
            'qr'                    => $name_qr . '.png'
        ]);

        $detalle                    = DetailBilling::select(
                                        'detail_billings.*',
                                        'products.descripcion as producto',
                                        'units.id as idunidad',
                                        'units.codigo as unidad',
                                        'igv_type_affections.codigo as codigo_igv',
                                    )
                                    ->join('products', 'detail_billings.idproducto', '=', 'products.id')
                                    ->join('units', 'products.idunidad', '=', 'units.id')
                                    ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
                                    ->where('idfacturacion', $idfactura_anular)
                                    ->get();

        $idfactura                  = Billing::latest('id')->first()['id'];
        foreach($detalle as $product)
        {
            DetailBilling::insert([
                'idfacturacion'         => $idfactura,
                'idproducto'            => $product['idproducto'],
                'cantidad'              => $product['cantidad'],
                'descuento'             => 0.0000000000,
                'igv'                   => $product["igv"],
                'id_afectacion_igv'     => $product['id_afectacion_igv'],
                'precio_unitario'       => $product['precio_unitario'],
                'precio_total'          => ($product['precio_unitario'] * $product['cantidad'])
            ]);
            $product_stock              = Product::where('id', $product["idproducto"])->first();
            if($product_stock->opcion == 1)
            {
                if ($product_stock->stock != NULL) 
                {
                    Product::where('id', $product_stock->id)->update([
                        "stock"  => $product_stock->stock + intval($product["cantidad"])
                    ]);
                }
            }
        }

        // Anulled
        DetailPayment::where('idtipo_comprobante', $factura_anular->idtipo_comprobante)->where('idfactura', $idfactura_anular)->update([
            'estado'    => 2
        ]);

        // Insert Payment
        foreach($pagos_anular as $pago_anular)
        {
            DetailPayment::insert([
                'idtipo_comprobante'    => 6,
                'idfactura'             => $idfactura,
                'idpago'                => $pago_anular["idpago"],
                'monto'                 => $pago_anular["monto"],
                'idcaja'                => $id_arching
            ]);
        }

        // Ticket
        $factura                        = Billing::where('id', $idfactura)->first();
        $ruc                            = Business::where('id', 1)->first()->ruc;
        $codigo_comprobante             = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $name                           = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;
        $this->gen_ticket_b($idfactura, $name);

        $ultima_serie                       = Serie::where('idtipo_documento', 6)->where('idcaja', Auth::user()['idcaja'])->first();
        $ultimo_correlativo                 = (int) $ultima_serie->correlativo + 1;
        $nuevo_correlativo                  = str_pad($ultimo_correlativo, 8, '0', STR_PAD_LEFT);
        Serie::where('idtipo_documento', 6)->where('idcaja', Auth::user()['idcaja'])->update([
            'correlativo'   => $nuevo_correlativo
        ]);

        echo json_encode([
            'status'        => true,
            'id'            => $idfactura,
            'idrelacionado' => $idfactura_anular,
            'pdf'           => $name . '.pdf',
            'type_document' => 6
        ]);
    }

    public function send(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }
        $id                         = $request->input('id');
        $factura                    = DB::select("CALL get_bf(?)", [$id])[0];
        $detalle                    = DB::select("CALL get_detail_bf(?)", [$id]);
        $factura_relacionada        = Billing::where('id', $factura->idfactura_anular)->first();

        if ($factura->cdr == 1) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'El comprobante ya fue enviado',
                'type'      => 'warning'
            ]);
            return;
        }

        $codigo_comprobante             = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $codigo_comprobante_relacionado = TypeDocument::where('id', $factura_relacionada->idtipo_comprobante)->first()->codigo;
        $id_doc_cliente                 = Client::where('id', $factura->idcliente)->first()->iddoc;
        $tipo_documento_cliente         = IdentityDocumentType::where('id', $id_doc_cliente)->first()->codigo;
        $dni_ruc                        = Client::where('id', $factura->idcliente)->first()->dni_ruc;
        $nombre_cliente                 = Client::where('id', $factura->idcliente)->first()->nombres;
        $direccion_cliente              = Client::where('id', $factura->idcliente)->first()->direccion;
        $ubigeo_cliente                 = Client::where('id', $factura->idcliente)->first()->ubigeo;
        $email_cliente                  = Client::where('id', $factura->idcliente)->first()->email;
        $detalle_ubigeo                 = $this->get_ubigeo($ubigeo_cliente);
        $tipo_moneda                    = Currency::where('id', $factura->idmoneda)->first()->codigo;
        $idserie                        = Serie::where('idtipo_documento', $factura->idtipo_comprobante)->where('serie', $factura->serie)->first()->id;
        $codigo_nc                      = CreditNoteType::where('id', $factura->id_tipo_nota_credito)->first()->codigo;
        $empresa                        = Business::where('id', 1)->first();
        $documento_referencia           = $empresa->ruc . '-' . $codigo_comprobante_relacionado . '-' . $factura_relacionada->serie . '-' . $factura_relacionada->correlativo;

        $data                           = [];
        $data['tipOperacion']           = "0101";
        $data['fecEmision']             = $factura->fecha_emision;
        $data['fecVencimiento']         = $factura->fecha_vencimiento;
        $data['tipComp']                = $codigo_comprobante;
        $data['serieComp']              = $factura->serie;
        $data['numeroComp']             = $factura->correlativo;
        $data['tipDocUsuario']          = $tipo_documento_cliente;
        $data['codCliente']             = strval($factura->idcliente);
        $data['numDocUsuario']          = $dni_ruc;
        $data['rznSocialUsuario']       = $nombre_cliente;
        $data['codPaisCliente']         = "PE";
        $data['codLocalEmisor']         = "";
        $data['desDireccionCliente']    = $direccion_cliente;
        $data['deptCliente']            = $detalle_ubigeo['departamento'];
        $data['provCliente']            = $detalle_ubigeo['provincia'];
        $data['distCliente']            = $detalle_ubigeo['distrito'];
        $data['urbCliente']             = "";
        $data['codUbigeoCliente']       = $ubigeo_cliente;
        $data['tipMoneda']              = $tipo_moneda;
        $data['tipCambio']              = "0.00";
        $data['Gravada']                = $factura->gravada;
        $data['Exonerada']              = $factura->exonerada;
        $data['Inafecta']               = $factura->inafecta;
        $data['Gratuita']               = "0.00";
        $data['Anticipo']               = "0.00";
        $data['DsctoGlobal']            = "0.00";
        $data['otrosCargos']            = "0.00";
        $data['mtoIgv']                 = $factura->igv;
        $data['mtoTotal']               = $factura->total;
        $data['servidorSunat']          = $empresa->servidor_sunat;
        $data['envioSunat']             = true;
        $data['UBL']                    = "2.1";
        $data['idserie']                = strval($idserie);
        $data['numdias']                = "0";
        $data['Cat09']                  = $codigo_nc; // Nota de crédito
        $data['Cat10']                  = "00"; // Nota de débito
        $data['detCat']                 = $factura->motivo;
        $data['docRef']                 = $documento_referencia;
        $data['CodDir']                 = "";
        $data['tipPago']                = "01";
        $data['accion']                 = false;
        $data['obs']                    = $factura->observaciones;
        $data['saltosLinea']            = "1";
        $data['impresion']              = "A4";
        $data['rucEmp']                 = $empresa->ruc;
        $data['dirEmp']                 = "";
        $data['emailCli']               = $email_cliente;
        $igv                            = 0;
        $precio__unitario               = 0;

        foreach ($detalle as $i => $product) {
            if ($product->idcodigo_igv == 1) // Aplica igv
            {
                $igv                    = number_format((((float) $product->precio_unitario - (float) $product->precio_unitario / 1.18) * (int) $product->cantidad), 2, ".", "");
                $precio__unitario   = number_format(($product->precio_unitario - ((float) $product->precio_unitario - (float) $product->precio_unitario / 1.18)), 5, ".", "");
            } else {
                $igv                = 0;
                $precio__unitario   = number_format($product->precio_unitario, 5, ".", "");
            }

            $data['items'][] =
                [
                    'CodItem'               => strval($product->id),
                    'codUnidadMedida'       => $product->unidad,
                    'ctdUnidadItem'         => strval(ceil($product->cantidad)),
                    'codProducto'           => strval($product->id),
                    'desItem'               => $product->producto,
                    'mtoValorUnitario'      => strval(number_format(($precio__unitario * $product->cantidad), 2, ".", "")),
                    'mtoDsctoItem'          => "0.00",
                    'mtoIgvItem'            => strval($igv),
                    'tipAfeIGV'             => $product->codigo_igv,
                    'tipPrecio'             => "01",
                    'mtoPrecioVentaItem'    => strval(number_format((($precio__unitario * $product->cantidad)) + $igv, 2, ".", "")),
                    'mtoValorVentaItem'     => strval(number_format((($precio__unitario * $product->cantidad)), 2, ".", "")),
                    'porcentajeIgv'         => strval(ceil($product->igv)),
                ];
        }

        echo json_encode([
            'status'                => true,
            'data'                  => $data,
            'idfactura'             => $id,
            'idfactura_relacionado' => $factura_relacionada->id,
            'empresa'               => $empresa
        ]);
    }

    public function update_cdr(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

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

        $id                 = $request->input('id');
        $idrelacionado      = $request->input('idrelacionado');
        Billing::where('id', $id)->update([
            'cdr'           => 1,
            'estado_cpe'    => 1
        ]);

        Billing::where('id', $idrelacionado)->update([
            'estado_cpe'    => 2
        ]);

        echo json_encode(['status'  => true]);
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

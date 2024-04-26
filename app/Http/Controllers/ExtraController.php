<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Business;
use App\Models\Buy;
use App\Models\Client;
use App\Models\Currency;
use App\Models\DetailBilling;
use App\Models\DetailBuy;
use App\Models\DetailPayment;
use App\Models\DetailQuote;
use App\Models\DetailSaleNote;
use App\Models\IdentityDocumentType;
use App\Models\PayMode;
use App\Models\Provider;
use App\Models\Quote;
use App\Models\SaleNote;
use App\Models\TypeDocument;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;

class ExtraController extends Controller
{
    public function prices()
    {
        return view('admin.extras.prices.home');
    }

    public function faq()
    {
        return view('admin.extras.faq.home');
    }

    public function send_voucher(Request $request)
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
        $phone              = trim($request->input('input__phone'));
        $type_document      = $request->input('type_document');
        if (empty($phone)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Ingrese un número de teléfono',
                'type'      => 'warning'
            ]);
            return;
        }

        if (!is_numeric($phone)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Solo se aceptan números',
                'type'      => 'warning'
            ]);
            return;
        }

        if (strlen($phone) != 9) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Ingrese un número válido',
                'type'      => 'warning'
            ]);
            return;
        }

        switch ($type_document) {
            case 'billing':
                $customPaper                = array(0, 0, 630.00, 210.00);
                $data['business']           = Business::where('id', 1)->first();
                $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
                $ruc                        = $data['business']->ruc;
                $factura                    = Billing::where('id', $id)->first();
                $codigo_comprobante         = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
                $data["name"]               = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;
                $cliente                    = Client::where('id', $factura->idcliente)->first();

                if (!file_exists(public_path('files/billings/ticket/' . $data["name"] . '.pdf'))) {
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
                    $pdf->save(public_path('files/billings/ticket/' . $data["name"] . '.pdf'));
                }

                $url_server                 = 'http://comprobantes.mytems.cloud/';
                $enlace                     = $url_server . $data["name"] . '.pdf';
                $cid                        = ftp_connect("mytems.cloud");
                $resultado                  = ftp_login($cid, 'mytemsc1', 'q+0%{tGW-~+y');
                if ((!$cid) || (!$resultado)) {
                    echo json_encode([
                        'status'    => false,
                        'msg'       => 'Servidor desconectado, intente de nuevo',
                        'type'      => 'warning'
                    ]);
                    return;
                }

                ftp_pasv($cid, true);
                ftp_chdir($cid, "comprobantes.mytems.cloud/");
                ftp_put(
                    $cid,
                    '/comprobantes.mytems.cloud/' . $data["name"] . '.pdf',
                    public_path('files/billings/ticket/' . $data["name"] . '.pdf'),
                    FTP_BINARY
                );

                $wpp_header                 = $cliente->nombres;
                $wpp_body                   = $enlace;
                $envio_wpp = $this->send_msg_wpp($phone, $wpp_header, $wpp_body);
                $envio_wpp  = json_decode($envio_wpp, true);
                if (array_key_exists("error", $envio_wpp)) {
                    echo json_encode([
                        'status'    => false,
                        'msg'       => 'No se pudo enviar el mensaje',
                        'type'      => 'warning'
                    ]);
                    return;
                }

                echo json_encode([
                    'status'        => true,
                    'msg'           => 'Comprobante enviado correctamente',
                    'type'          => 'success'
                ]);
                break;

            case 'sale_note':
                $customPaper                = array(0, 0, 630.00, 210.00);
                $data['business']           = Business::where('id', 1)->first();
                $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
                $ruc                        = $data['business']->ruc;
                $factura                    = SaleNote::where('id', $id)->first();
                $codigo_comprobante         = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
                $data["name"]               = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;
                $cliente                    = Client::where('id', $factura->idcliente)->first();
                if(!file_exists(public_path('files/sale-notes/ticket/' . $data["name"] . '.pdf')))
                {
                    $data['factura']            = SaleNote::where('id', $id)->first();
                    $data['cliente']            = Client::where('id', $factura->idcliente)->first();
                    $data['tipo_documento']     = IdentityDocumentType::where('id', $data['cliente']->iddoc)->first();
                    $data['moneda']             = Currency::where('id', $factura->idmoneda)->first();
                    $data['modo_pago']          = PayMode::where('id', $factura->modo_pago)->first();
                    $data['detalle']            = DetailSaleNote::select('detail_sale_notes.*', 'products.descripcion as producto', 
                                                'products.codigo_interno as codigo_interno')
                                                ->join('products', 'detail_sale_notes.idproducto', '=', 'products.id')
                                                ->where('idnotaventa', $factura->id)
                                                ->get();
            
                    $formatter                  = new NumeroALetras();
                    $data['numero_letras']      = $formatter->toWords($factura->total, 2);
                    $data['tipo_comprobante']   = TypeDocument::where('id', $factura->idtipo_comprobante)->first();
                    $data['vendedor']           = mb_strtoupper(User::where('id', $data['factura']->idusuario)->first()->user);
                    $pdf                        = PDF::loadView('admin.sale_notes.ticket', $data)->setPaper($customPaper, 'landscape');
                    $pdf->save(public_path('files/sale-notes/ticket/' . $data["name"] . '.pdf'));
                }

                $url_server                 = 'http://comprobantes.mytems.cloud/';
                $enlace                     = $url_server . $data["name"] . '.pdf';
                $cid                        = ftp_connect("mytems.cloud");
                $resultado                  = ftp_login($cid, 'mytemsc1', 'q+0%{tGW-~+y');
                if ((!$cid) || (!$resultado)) {
                    echo json_encode([
                        'status'    => false,
                        'msg'       => 'Servidor desconectado, intente de nuevo',
                        'type'      => 'warning'
                    ]);
                    return;
                }

                ftp_pasv($cid, true);
                ftp_chdir($cid, "comprobantes.mytems.cloud/");
                ftp_put(
                    $cid,
                    '/comprobantes.mytems.cloud/' . $data["name"] . '.pdf',
                    public_path('files/sale-notes/ticket/' . $data["name"] . '.pdf'),
                    FTP_BINARY
                );

                $wpp_header                 = $cliente->nombres;
                $wpp_body                   = $enlace;
                $envio_wpp = $this->send_msg_wpp($phone, $wpp_header, $wpp_body);
                $envio_wpp  = json_decode($envio_wpp, true);
                if (array_key_exists("error", $envio_wpp)) {
                    echo json_encode([
                        'status'    => false,
                        'msg'       => 'No se pudo enviar el mensaje',
                        'type'      => 'warning'
                    ]);
                    return;
                }

                echo json_encode([
                    'status'        => true,
                    'msg'           => 'Comprobante enviado correctamente',
                    'type'          => 'success'
                ]);
                break;


            case 'quote':
                $data["quote"]              = Quote::where('id', $id)->first();
                $data["business"]           = Business::where('id', 1)->first();
                $data["client"]             = Client::where('id', $data["quote"]["idcliente"])->first();
                $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
                $data["name"]               = mb_strtoupper( $data["client"]->dni_ruc . '-' . $data["quote"]["serie"]) . '-' . $data["quote"]["correlativo"];
                $cliente                    = Client::where('id', $data["quote"]->idcliente)->first();

                if(!file_exists(public_path('files/quotes/' . $data["name"] . '.pdf'))) {
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
                    $pdf->save(public_path('files/quotes/' . $data["name"] . '.pdf'));
                }

                $url_server                 = 'http://comprobantes.mytems.cloud/';
                $enlace                     = $url_server . $data["name"] . '.pdf';
                $cid                        = ftp_connect("mytems.cloud");
                $resultado                  = ftp_login($cid, 'mytemsc1', 'q+0%{tGW-~+y');
                if ((!$cid) || (!$resultado)) {
                    echo json_encode([
                        'status'    => false,
                        'msg'       => 'Servidor desconectado, intente de nuevo',
                        'type'      => 'warning'
                    ]);
                    return;
                }

                ftp_pasv($cid, true);
                ftp_chdir($cid, "comprobantes.mytems.cloud/");
                ftp_put(
                    $cid,
                    '/comprobantes.mytems.cloud/' . $data["name"] . '.pdf',
                    public_path('files/quotes/' . $data["name"] . '.pdf'),
                    FTP_BINARY
                );

                $wpp_header                 = $cliente->nombres;
                $wpp_body                   = $enlace;
                $envio_wpp = $this->send_msg_wpp($phone, $wpp_header, $wpp_body);
                $envio_wpp  = json_decode($envio_wpp, true);
                if (array_key_exists("error", $envio_wpp)) {
                    echo json_encode([
                        'status'    => false,
                        'msg'       => 'No se pudo enviar el mensaje',
                        'type'      => 'warning'
                    ]);
                    return;
                }

                echo json_encode([
                    'status'        => true,
                    'msg'           => 'Comprobante enviado correctamente',
                    'type'          => 'success'
                ]);
                break;

            case 'buy':
                $data["buy"]                = Buy::where('id', $id)->first();
                $data["business"]           = Business::where('id', 1)->first();
                $data["provider"]           = Provider::where('id', $data["buy"]["idproveedor"])->first();
                $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
                $data["name"]               = mb_strtoupper($data["provider"]->dni_ruc . '-' . $data["buy"]["serie"]) . '-' . $data["buy"]["correlativo"];
                $cliente                    = Provider::where('id', $data["buy"]->idproveedor)->first();

                if(!file_exists(public_path('files/buys/' . $data["name"] . '.pdf'))) {
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
                    $pdf->save(public_path('files/buys/' . $data["name"] . '.pdf'));
                }

                $url_server                 = 'http://comprobantes.mytems.cloud/';
                $enlace                     = $url_server . $data["name"] . '.pdf';
                $cid                        = ftp_connect("mytems.cloud");
                $resultado                  = ftp_login($cid, 'mytemsc1', 'q+0%{tGW-~+y');
                if ((!$cid) || (!$resultado)) {
                    echo json_encode([
                        'status'    => false,
                        'msg'       => 'Servidor desconectado, intente de nuevo',
                        'type'      => 'warning'
                    ]);
                    return;
                }

                ftp_pasv($cid, true);
                ftp_chdir($cid, "comprobantes.mytems.cloud/");
                ftp_put(
                    $cid,
                    '/comprobantes.mytems.cloud/' . $data["name"] . '.pdf',
                    public_path('files/buys/' . $data["name"] . '.pdf'),
                    FTP_BINARY
                );

                $wpp_header                 = $cliente->nombres;
                $wpp_body                   = $enlace;
                $envio_wpp = $this->send_msg_wpp($phone, $wpp_header, $wpp_body);
                $envio_wpp  = json_decode($envio_wpp, true);
                if (array_key_exists("error", $envio_wpp)) {
                    echo json_encode([
                        'status'    => false,
                        'msg'       => 'No se pudo enviar el mensaje',
                        'type'      => 'warning'
                    ]);
                    return;
                }

                echo json_encode([
                    'status'        => true,
                    'msg'           => 'Comprobante enviado correctamente',
                    'type'          => 'success'
                ]);
                break;
            default:
                # code...
                break;
        }
    }
}

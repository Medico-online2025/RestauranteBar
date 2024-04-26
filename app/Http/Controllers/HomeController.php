<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Billing;
use App\Models\Business;
use App\Models\Client as ClientDB;
use App\Models\Product;
use App\Models\SaleNote;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $data['facturas']        = Billing::where('idtipo_comprobante', 1)->where('estado_cpe', 0)->get();
        $data['boletas']         = Billing::where('idtipo_comprobante', 2)->where('estado_cpe', 0)->get();
        $data['stock']           = Product::where('stock', '!=', NULL)->where('stock', '<=', 10)->get();
        $expirations             = Product::where('fecha_vencimiento', '!=', NULL)->get();
        $data['clients']         = ClientDB::get();
        $data['products']        = Product::get();
        $data['billings']        = Billing::where('idtipo_comprobante', '!=', 6)->where('estado_cpe', 1)->get();
        $data['sale_notes']      = SaleNote::where('estado', 1)->get();
        $data['sum_billings']    = Billing::where('idtipo_comprobante', '!=', 6)->where('estado_cpe', 1)->sum('total');
        $data['sum_sale_notes']  = SaleNote::where('estado', 1)->sum('total');
        $start                   = date('Y-m-01');
        $final                   = date('Y-m-t');
        # Best seller
        $seller_b                = DB::select("SELECT SUM(total) as total, users.nombres as vendedor, users.id as idvendedor
                                    FROM billings
                                    INNER JOIN users ON billings.idusuario = users.id
                                    WHERE billings.estado_cpe = 1 AND billings.idtipo_comprobante != 6 AND fecha_emision BETWEEN '$start' AND '$final'
                                    GROUP BY users.nombres, users.id
                                    ORDER BY total DESC");

        $seller_nv               = DB::select("SELECT SUM(total) as total, users.nombres as vendedor, users.id as idvendedor
                                    FROM sale_notes
                                    INNER JOIN users ON sale_notes.idusuario = users.id
                                    WHERE sale_notes.estado = 1 AND fecha_emision BETWEEN '$start' AND '$final'
                                    GROUP BY users.nombres, users.id
                                    ORDER BY total DESC");

        $seller_b                 = json_encode($seller_b);
        $seller_nv                = json_encode($seller_nv);
        $sellers                  = array_merge(json_decode($seller_b, true), json_decode($seller_nv, true));
        $ids_sellers              = [];
        foreach ($sellers as $arr_product) {
            $id_product = $arr_product["idvendedor"];
            if (!in_array($id_product, $ids_sellers)) {
                $ids_sellers[] = $id_product;
            }
        }

        $seller = [];
        foreach ($ids_sellers as $unique_id) {
            $temp     = [];
            $quantity = 0;
            foreach ($sellers as $arr_product) {
                $id = $arr_product["idvendedor"];
                if ($id === $unique_id) {
                    $temp[] = $arr_product;
                }
            }
            $product            = $temp[0];
            $product["total"]   = 0;
            foreach ($temp as $product_temp) {
                $product["total"]    = $product_temp["total"] + $product["total"];
            }
            $seller[] = $product;
        }

        if (count($seller) < 1) {
            $data["seller_name"]            = '';
            $data["seller_total"]           = 0;
        } else {
            $data["seller_name"]            = '¡' . $seller[0]["vendedor"] . '!🎉';
            $data["seller_total"]           = $seller[0]["total"];
        }

        $current                 = date('d-m-Y');
        $message                 = [];
        foreach ($expirations as $expiration) {
            $fecha_vencimiento  = $expiration["fecha_vencimiento"];
            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 1 days")) == $current)
                $message[]          = $expiration["descripcion"] . " VENCE EN 01 DÍA";

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 2 days")) == $current)
                $message[]          = $expiration["descripcion"] . " VENCE EN 02 DÍAS";

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 3 days")) == $current)
                $message[]          = $expiration["descripcion"] . " VENCE EN 03 DÍAS";

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 4 days")) == $current)
                $message[]          = $expiration["descripcion"] . " VENCE EN 04 DÍAS";

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 5 days")) == $current)
                $message[]          = $expiration["descripcion"] . " VENCE EN 05 DÍAS";

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 6 days")) == $current)
                $message[]          = $expiration["descripcion"] . " VENCE EN 06 DÍAS";

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 7 days")) == $current)
                $message[]          = $expiration["descripcion"] . " VENCE EN 07 DÍAS";

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 8 days")) == $current)
                $message[]          = $expiration["descripcion"] . " VENCE EN 08 DÍAS";

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 9 days")) == $current)
                $message[]          = $expiration["descripcion"] . " VENCE EN 09 DÍAS";

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 10 days")) == $current)
                $message[]          = $expiration["descripcion"] . " VENCE EN 10 DÍAS";
        }

        $current_bill               = date('Y-m-d');
        $data["expirations"]        = $message;
        $data["bills"]              = Bill::sum('monto');
        $mes_anterior               = date("Y-m-d", strtotime($current_bill . "- 1 months"));
        $mes_2_anterior             = date("Y-m-d", strtotime($current_bill . "- 2 months"));
        $bills_former_p             = Bill::whereBetween('fecha_emision', [$mes_2_anterior, $mes_anterior])->sum('monto');
        $bills_former_v             = Bill::whereBetween('fecha_emision', [$mes_anterior, $current_bill])->sum('monto');
        $data["bills_former"]       = ($bills_former_v - $bills_former_p);
        $data["billing_profit"]     = Billing::where('idtipo_comprobante', '!=', 6)->where('estado_cpe', 1)->whereBetween('fecha_emision', [$mes_anterior, $current_bill])->sum('total');
        $data["sale_note_profit"]   = SaleNote::where('estado', 1)->whereBetween('fecha_emision', [$mes_anterior, $current_bill])->sum('total');
        $current_ganancias          = date("Y-01-01");
        $current_last               = date('Y-12-t');
        $ganancias_b                = Billing::whereBetween('fecha_emision', [$current_ganancias, $current_last])->where('idtipo_comprobante', '!=', 6)->where('estado_cpe', 1)->sum('total');
        $ganancias_nv               = SaleNote::whereBetween('fecha_emision', [$current_ganancias, $current_last])->where('estado', 1)->sum('total');
        $data["ganancias"]          = ($ganancias_b + $ganancias_nv);

        $user = User::with('roles')->where('id', Auth::user()['id'])->first();
        $role = $user->roles->first();

        ## Sales of product
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
        $data["sales_products"]              = $products;
        $data["sales_products"]              = array_slice($data["sales_products"], 0, 10);
        ## Sales of clients
        $b_f            = DB::select("SELECT COUNT(*) as cantidad_ventas, clients.id as idcliente, SUM(billings.total) as total,
                        clients.nombres as cliente, clients.dni_ruc
                        FROM billings
                        INNER JOIN clients ON billings.idcliente = clients.id
                        GROUP BY clients.id, clients.nombres, billings.total, clients.dni_ruc");

        $n_v            = DB::select("SELECT COUNT(*) as cantidad_ventas, clients.id as idcliente, SUM(sale_notes.total) as total,
                        clients.nombres as cliente, clients.dni_ruc
                        FROM sale_notes
                        INNER JOIN clients ON sale_notes.idcliente = clients.id
                        GROUP BY clients.id, clients.nombres, sale_notes.total, clients.dni_ruc");

        $b_f            = json_encode($b_f);
        $n_v            = json_encode($n_v);
        $billings       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
        $clients        = [];
        $ids_products   = [];

        foreach ($billings as $arr_product) {
            $id_product = $arr_product["idcliente"];
            if (!in_array($id_product, $ids_products)) {
                $ids_products[] = $id_product;
            }
        }

        $clients = [];
        foreach ($ids_products as $unique_id) {
            $temp     = [];
            $quantity = 0;
            foreach ($billings as $arr_product) {
                $id = $arr_product["idcliente"];
                if ($id === $unique_id) {
                    $temp[] = $arr_product;
                }
            }

            $product = $temp[0];
            $product["cantidad_ventas"] = 0;
            $product["total"]           = 0;
            foreach ($temp as $product_temp) {
                $product["cantidad_ventas"] = $product["cantidad_ventas"] + $product_temp["cantidad_ventas"];
                $product["total"]           = $product["total"] + $product_temp["total"];
                $product["dni_ruc"]         = $product["dni_ruc"];
            }
            $clients[] = $product;
        }
        $data["sales_clients"]              = $clients;
        $data["sales_clients"]              = array_slice($data["sales_clients"], 0, 10);


        if (empty($role))
            return view('admin.secondary', $data);

        if ($role->name == "VENDEDOR" || $role->name == "MESERO")
            return view('admin.secondary', $data);
        return view('admin.home', $data);
    }

    public function alerts(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $facturas       = count(Billing::where('idtipo_comprobante', 1)->where('estado_cpe', 0)->get());
        $boletas        = count(Billing::where('idtipo_comprobante', 2)->where('estado_cpe', 0)->get());
        $stock          = count(Product::where('stock', '!=', NULL)->where('stock', '<=', 10)->get());
        $current        = date('d-m-Y');
        $products       = Product::where('fecha_vencimiento', '!=', NULL)->get();
        $new_products    = [];

        foreach ($products as $expiration) {
            $fecha_vencimiento  = $expiration["fecha_vencimiento"];
            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 1 days")) == $current) {
                $messages[]         = "VENCE EN 01 DÍA";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 2 days")) == $current) {
                $messages[]         = "VENCE EN 02 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 3 days")) == $current) {
                $messages[]         = "VENCE EN 03 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 4 days")) == $current) {
                $messages[]         = "VENCE EN 04 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 5 days")) == $current) {
                $messages[]         = "VENCE EN 05 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 6 days")) == $current) {
                $messages[]         = "VENCE EN 06 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 7 days")) == $current) {
                $messages[]         = "VENCE EN 07 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 8 days")) == $current) {
                $messages[]         = "VENCE EN 08 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 9 days")) == $current) {
                $messages[]         = "VENCE EN 09 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 10 days")) == $current) {
                $messages[]         = "VENCE EN 10 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date(strtotime($fecha_vencimiento)) < strtotime($current)) {
                $messages[]         = "EL PRODUCTO YA VENCIÓ";
                $new_products[]     = $expiration;
            }
        }

        $expirations    = count($new_products);
        $quantity       = ($facturas + $boletas + $stock + $expirations);
        echo json_encode([
            'status'        => true,
            'quantity'      => $quantity,
            'facturas'      => $facturas,
            'boletas'       => $boletas,
            'stock'         => $stock,
            'expirations'   => $expirations
        ]);
    }

    public function dash_bills(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $bills                      = Bill::sum('monto');
        $current_bill               = date('Y-m-d');
        $mes_anterior               = date("Y-m-d", strtotime($current_bill . "- 1 months"));
        $mes_2_anterior             = date("Y-m-d", strtotime($current_bill . "- 2 months"));
        $bills_former_p             = Bill::whereBetween('fecha_emision', [$mes_2_anterior, $mes_anterior])->sum('monto');
        $bills_former_v             = Bill::whereBetween('fecha_emision', [$mes_anterior, $current_bill])->sum('monto');
        if (empty($bills))
            $porcentaje             = 0.00;
        else
            $porcentaje             = number_format((($bills_former_v * 100) / $bills), 2, ".", "");

        echo json_encode([
            'status'        => true,
            'porcentaje'    => $porcentaje
        ]);
    }

    public function dash_profits(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $current                = date('Y-m-d');
        $semana_4               = date("Y-m-d", strtotime($current . "- 1 months"));
        $semana_3               = date("Y-m-d", strtotime($current . "- 3 week"));
        $semana_2               = date("Y-m-d", strtotime($current . "- 2 week"));
        $semana_1               = date("Y-m-d", strtotime($current . "- 1 week"));
        $graphics               = [];
        $bf_1                   = Billing::where('estado_cpe', 1)->where('idtipo_comprobante', '!=', 6)->whereBetween('fecha_emision', [$semana_4, $semana_3])->sum('total');
        $nv_1                   = SaleNote::where('estado', 1)->whereBetween('fecha_emision', [$semana_4, $semana_3])->sum('total');

        $bf_2                   = Billing::where('estado_cpe', 1)->where('idtipo_comprobante', '!=', 6)->whereBetween('fecha_emision', [$semana_3, $semana_2])->sum('total');
        $nv_2                   = SaleNote::where('estado', 1)->whereBetween('fecha_emision', [$semana_3, $semana_2])->sum('total');

        $bf_3                   = Billing::where('estado_cpe', 1)->where('idtipo_comprobante', '!=', 6)->whereBetween('fecha_emision', [$semana_2, $semana_1])->sum('total');
        $nv_3                   = SaleNote::where('estado', 1)->whereBetween('fecha_emision', [$semana_2, $semana_1])->sum('total');

        $bf_4                   = Billing::where('estado_cpe', 1)->where('idtipo_comprobante', '!=', 6)->whereBetween('fecha_emision', [$semana_1, $current])->sum('total');
        $nv_4                   = SaleNote::where('estado', 1)->whereBetween('fecha_emision', [$semana_1, $current])->sum('total');
        $graphics               = [
            number_format((floatval($bf_1) + floatval($nv_1)), 2, ".", ""),
            number_format((floatval($bf_2) + floatval($nv_2)), 2, ".", ""),
            number_format((floatval($bf_3) + floatval($nv_3)), 2, ".", ""),
            number_format((floatval($bf_4) + floatval($nv_4)), 2, ".", ""),
        ];

        echo json_encode([
            'status'    => true,
            'graphics'  => $graphics
        ]);
    }

    public function dash_clients(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $b_f            = DB::select("SELECT COUNT(*) as cantidad_ventas, clients.id as idcliente, clients.nombres as cliente
                        FROM billings
                        INNER JOIN clients ON billings.idcliente = clients.id
                        GROUP BY clients.id, clients.nombres");

        $n_v            = DB::select("SELECT COUNT(*) as cantidad_ventas, clients.id as idcliente, clients.nombres as cliente
                        FROM sale_notes
                        INNER JOIN clients ON sale_notes.idcliente = clients.id
                        GROUP BY clients.id, clients.nombres");

        $b_f            = json_encode($b_f);
        $n_v            = json_encode($n_v);
        $billings       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
        $clients        = [];

        // Create an array with the Ids withouts repetitions to get
        // a list of unique products
        $ids_products = [];
        foreach ($billings as $arr_product) {
            $id_product = $arr_product["idcliente"];
            if (!in_array($id_product, $ids_products)) {
                $ids_products[] = $id_product;
            }
        }
        // Create array with the list of unique productos
        $clients = [];
        foreach ($ids_products as $unique_id) {
            $temp     = [];
            $quantity = 0;
            foreach ($billings as $arr_product) {
                $id = $arr_product["idcliente"];

                if ($id === $unique_id) {
                    $temp[] = $arr_product;
                }
            }

            $product = $temp[0];
            $product["cantidad_ventas"] = 0;
            foreach ($temp as $product_temp) {
                $product["cantidad_ventas"] = $product["cantidad_ventas"] + $product_temp["cantidad_ventas"];
            }
            $clients[] = $product;
        }

        $names                      = [];
        $quantitys                  = [];
        $total_quantity             = 0;

        if (empty($clients)) {
            $names[]                = 'CLIENTES VARIOS';
            $quantitys[]            = 0;
            $total_quantity         += 0.00;
        } else {
            foreach ($clients as $client) {
                $names[]            = $client["cliente"];
                $quantitys[]        = $client["cantidad_ventas"];
                $total_quantity     += $client["cantidad_ventas"];
            }
        }
        echo json_encode([
            'status'            => true,
            'names'             => $names,
            'quantitys'         => $quantitys,
            'total_quantity'    => $total_quantity
        ]);
    }

    public function dash_incomes(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $current            = date("Y-01-01");
        $feb                = date("Y-m-d", strtotime($current . "+ 1 months"));
        $mar                = date("Y-m-d", strtotime($current . "+ 2 months"));
        $abr                = date("Y-m-d", strtotime($current . "+ 3 months"));
        $may                = date("Y-m-d", strtotime($current . "+ 4 months"));
        $jun                = date("Y-m-d", strtotime($current . "+ 5 months"));
        $jul                = date("Y-m-d", strtotime($current . "+ 6 months"));
        $ago                = date("Y-m-d", strtotime($current . "+ 7 months"));
        $sep                = date("Y-m-d", strtotime($current . "+ 8 months"));
        $oct                = date("Y-m-d", strtotime($current . "+ 9 months"));
        $nov                = date("Y-m-d", strtotime($current . "+ 10 months"));
        $dic                = date("Y-m-d", strtotime($current . "+ 11 months"));
        $current_last       = date('Y-12-t');

        $enero_b              = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$current, $feb])
            ->sum('total');

        $enero_nv           = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$current, $feb])
            ->sum('total');
        $enero              = ($enero_b + $enero_nv);

        $febrero_b          = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$feb, $mar])
            ->sum('total');

        $febrero_nv         = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$feb, $mar])
            ->sum('total');
        $febrero            = ($febrero_b + $febrero_nv);

        $marzo_b            = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$mar, $abr])
            ->sum('total');

        $marzo_nv           = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$mar, $abr])
            ->sum('total');
        $marzo            = ($marzo_b + $marzo_nv);

        $abril_b          = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$abr, $may])
            ->sum('total');

        $abril_nv         = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$abr, $may])
            ->sum('total');
        $abril            = ($abril_b + $abril_nv);

        $mayo_b           = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$may, $jun])
            ->sum('total');

        $mayo_nv          = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$may, $jun])
            ->sum('total');
        $mayo             = ($mayo_b + $mayo_nv);

        $junio_b          = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$jun, $jul])
            ->sum('total');

        $junio_nv         = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$jun, $jul])
            ->sum('total');
        $junio            = ($junio_b + $junio_nv);

        $julio_b          = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$jul, $ago])
            ->sum('total');

        $julio_nv         = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$jul, $ago])
            ->sum('total');
        $julio            = ($julio_b + $julio_nv);
        

        $agosto_b         = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$ago, $sep])
            ->sum('total');

        $agosto_nv        = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$ago, $sep])
            ->sum('total');
        $agosto           = ($agosto_b + $agosto_nv);

        $septiembre_b     = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$sep, $oct])
            ->sum('total');

        $septiembre_nv     = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$sep, $oct])
            ->sum('total');
        $septiembre        = ($septiembre_b + $septiembre_nv);

        $octubre_b         = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$oct, $nov])
            ->sum('total');

        $octubre_nv        = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$oct, $nov])
            ->sum('total');
        $octubre           = ($octubre_b + $octubre_nv);

        $noviembre_b       = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$nov, $dic])
            ->sum('total');

        $noviembre_nv       = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$nov, $dic])
            ->sum('total');
        $noviembre          = ($noviembre_b + $noviembre_nv);

        $diciembre_b        = Billing::where('idtipo_comprobante', '!=', 6)
            ->where('estado_cpe', 1)
            ->whereBetween('fecha_emision', [$dic, $current_last])
            ->sum('total');

        $diciembre_nv       = SaleNote::where('estado', 1)
            ->whereBetween('fecha_emision', [$dic, $current_last])
            ->sum('total');
        $diciembre         = ($diciembre_b + $diciembre_nv);

        $ganancias         = [
            number_format($enero, 2, ".", ""),
            number_format($febrero, 2, ".", ""),
            number_format($marzo, 2, ".", ""),
            number_format($abril, 2, ".", ""),
            number_format($mayo, 2, ".", ""),
            number_format($junio, 2, ".", ""),
            number_format($julio, 2, ".", ""),
            number_format($agosto, 2, ".", ""),
            number_format($septiembre, 2, ".", ""),
            number_format($octubre, 2, ".", ""),
            number_format($noviembre, 2, ".", ""),
            number_format($diciembre, 2, ".", ""),
        ];

        $gastos_enero       = Bill::whereBetween('fecha_emision', [$current, $feb])->sum('monto');
        $gastos_febrero     = Bill::whereBetween('fecha_emision', [$feb, $mar])->sum('monto');
        $gastos_marzo       = Bill::whereBetween('fecha_emision', [$mar, $abr])->sum('monto');
        $gastos_abril       = Bill::whereBetween('fecha_emision', [$abr, $may])->sum('monto');
        $gastos_mayo        = Bill::whereBetween('fecha_emision', [$may, $jun])->sum('monto');
        $gastos_junio       = Bill::whereBetween('fecha_emision', [$jun, $jul])->sum('monto');
        $gastos_julio       = Bill::whereBetween('fecha_emision', [$jul, $ago])->sum('monto');
        $gastos_agosto      = Bill::whereBetween('fecha_emision', [$ago, $sep])->sum('monto');
        $gastos_septiembre  = Bill::whereBetween('fecha_emision', [$sep, $oct])->sum('monto');
        $gastos_octubre     = Bill::whereBetween('fecha_emision', [$oct, $nov])->sum('monto');
        $gastos_noviembre   = Bill::whereBetween('fecha_emision', [$nov, $dic])->sum('monto');
        $gastos_diciembre   = Bill::whereBetween('fecha_emision', [$dic, $current_last])->sum('monto');
        $gastos         = [
            number_format('-' . $gastos_enero, 2, ".", ""),
            number_format('-' . $gastos_febrero, 2, ".", ""),
            number_format('-' . $gastos_marzo, 2, ".", ""),
            number_format('-' . $gastos_abril, 2, ".", ""),
            number_format('-' . $gastos_mayo, 2, ".", ""),
            number_format('-' . $gastos_junio, 2, ".", ""),
            number_format('-' . $gastos_julio, 2, ".", ""),
            number_format('-' . $gastos_agosto, 2, ".", ""),
            number_format('-' . $gastos_septiembre, 2, ".", ""),
            number_format('-' . $gastos_octubre, 2, ".", ""),
            number_format('-' . $gastos_noviembre, 2, ".", ""),
            number_format('-' . $gastos_diciembre, 2, ".", ""),
        ];

        echo json_encode([
            'status'    => true,
            'ganancias' => $ganancias,
            'gastos'    => $gastos
        ]);
    }
}

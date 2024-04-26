<?php

namespace App\Http\Controllers;

use App\Exports\DownloadProduct;
use App\Imports\ProductImport;
use App\Models\Business;
use App\Models\IgvTypeAffection;
use App\Models\Product;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        $data["units"]             = Unit::where('estado', 1)->get();
        $data['type_inafects']     = IgvTypeAffection::where('estado', 1)->get();
        return view('admin.products.list', $data);
    }

    public function get()
    {
        $products     = DB::select("CALL get_list_products_data()");
        return Datatables()
                    ->of($products)
                    ->addColumn('acciones', function($products){
                        $id     = $products->id;
                        $btn    = '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item btn-view" data-id="'.$id.'" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye mr-50 menu-icon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            <span>Ver detalle</span>
                                        </a>
                                        <a class="dropdown-item btn-detail" data-id="'.$id.'" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50 menu-icon"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            <span> Editar</span>
                                        </a>
                                        <a class="dropdown-item btn-confirm" data-id="'.$id.'" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50 menu-icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span> Eliminar</span>
                                        </a>
                                    </div>
                                </div>';
                        return $btn;
                    })
                    ->rawColumns(['acciones'])
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

        $codigo_interno     = trim($request->input('codigo_interno'));
        $codigo_interno     = empty($codigo_interno) ? NULL : $codigo_interno;
        $codigo_barras      = trim($request->input('codigo_barras'));
        $idunidad           = $request->input('idunidad');
        $descripcion        = trim($request->input('descripcion'));
        $marca              = trim($request->input('marca'));
        $presentacion       = trim($request->input('presentacion'));
        $operacion          = $request->input('operacion'); // Si es 1 va IGV
        $impuesto           = 0;
        $precio_compra      = $request->input('precio_compra');
        $precio_venta       = $request->input('precio_venta');
        $stock__            = $request->input('stock');
        $stock              = NULL;
        $check_stock        = $request->input('check_stock');
        $fecha_vencimiento  = $request->input('fecha_vencimiento');
        $buscar_codigo      = Product::where('codigo_interno', $codigo_interno)->where('codigo_interno', '!=', NULL)->first();
        $igv                = NULL;
        $opcion             = $request->input('opcion');

        if(!empty($buscar_codigo))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Código interno existente',
                'type'      => 'warning'
            ]);
            return;
        }

        if(!empty($check_stock))
        {
            if(empty($stock__))
            {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una cantidad de stock válida',
                    'type'      => 'warning'
                ]);
                return;
            }
        }

        if(empty($check_stock))
            $stock          = NULL;
        else
            $stock          = $stock__;

        /**
         * 1 => Gravada
         * 10 => Exonerada
         * 30 => Inafecta
        */
        if($operacion == '1')
            $igv            = 18;
        else
            $igv            = 0;

        Product::insert([
            'codigo_sunat'      => '00000000',
            'codigo_interno'    => $codigo_interno,
            'codigo_barras'     => $codigo_barras,
            'descripcion'       => mb_strtoupper($descripcion),
            'marca'             => mb_strtoupper($marca),
            'presentacion'      => mb_strtoupper($presentacion),
            'idunidad'          => $idunidad,
            'idcodigo_igv'      => $operacion,
            'igv'               => $igv,
            'precio_compra'     => $precio_compra,
            'precio_venta'      => $precio_venta,
            'impuesto'          => ($operacion == '1') ? 1 : 0,
            'stock'             => $stock,
            'fecha_vencimiento' => $fecha_vencimiento,
            'opcion'            => $opcion
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro agregado correctamente',
            'type'      => 'success'
        ]);
    }

    public function detail(Request $request)
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

        $id                 = $request->input('id');
        $product            = Product::where('id', $id)->first();
        $type_inafectos     = IgvTypeAffection::where('estado', 1)->get();
        $unidades           = Unit::where('estado', 1)->orderBy('id', 'DESC')->get();
        echo json_encode([
            'status'            => true, 
            'product'           => $product,
            'type_inafectos'    => $type_inafectos,
            'unidades'          => $unidades
        ]);
    }

    public function store(Request $request)
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
  
        $id                 = $request->input('id');
        $codigo_interno     = $request->input('codigo_interno');
        $codigo_barras      = $request->input('codigo_barras');
        $idunidad           = $request->input('idunidad');
        $descripcion        = trim($request->input('descripcion'));
        $marca              = trim($request->input('marca'));
        $presentacion       = trim($request->input('presentacion'));
        $operacion          = $request->input('operacion'); // Si es 1 va IGV
        $impuesto           = 0;
        $precio_compra      = $request->input('precio_compra');
        $precio_venta       = $request->input('precio_venta');
        $stock__            = $request->input('stock');
        $stock              = NULL;
        $check_stock        = $request->input('check_stock');
        $fecha_vencimiento  = $request->input('fecha_vencimiento');
        $igv                = null;
        $opcion             = $request->input('opcion');


        if($operacion == '1')
            $igv            = 18;
        else
            $igv            = 0;

        if(empty($check_stock))
            $stock          = NULL;
        else
            $stock          = $stock__;

        Product::where('id', $id)->update([ 
            'codigo_interno'    => $codigo_interno,
            'codigo_barras'     => $codigo_barras,
            'descripcion'       => mb_strtoupper($descripcion),
            'marca'             => mb_strtoupper($marca),
            'presentacion'      => mb_strtoupper($presentacion),
            'idunidad'          => $idunidad,
            'idcodigo_igv'      => $operacion,
            'igv'               => $igv,
            'precio_compra'     => $precio_compra,
            'precio_venta'      => $precio_venta,
            'impuesto'          => ($operacion == '1') ? 1 : 0,
            'stock'             => $stock,   
            'fecha_vencimiento' => $fecha_vencimiento,
            'opcion'            => $opcion
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro actualizado correctamente',
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
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }

        $id            = $request->input('id');
        Product::where('id', $id)->delete();

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado correctamente',
            'title'     => '¡Bien!',
            'type'      => 'success'
        ]);
    }

    public function upload(Request $request)
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

        $excel      = $request->file('excel');
        if(empty($excel))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Seleccione un documento',
                'type'      => 'warning'
            ]);
            return;
        }

        $extension          = $excel->extension();
        if($extension != 'xlsx')
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Seleccione un documento válido',
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }

        try
        {
            Excel::import(new ProductImport, $excel);
            echo json_encode([
                'status'    => true,
                'msg'       => 'Importación de datos exitosa',
                'type'      => 'success'
            ]);
        }
        catch(Exception $e)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Se encontraron observaciones en el documento',
                'type'      => 'warning'
            ]);
        }
    }

    public function download(Request $request)
    {
        $business           = Business::where('id', 1)->first();
        $nombre_documento   = 'Lista de productos ' . $business->razon_social;
        $data['productos']  = Product::get();

        return Excel::download(new DownloadProduct($data), $nombre_documento . '.xlsx');
    }   

    public function view_stocks(Request $request)
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

        $id                         = (int) $request->input('id');
        $product                    = Product::where('id', $id)->first();
        $data["codigo"]             = (empty($product->codigo_interno)) ? '-' : $product->codigo_interno;
        $data["descripcion"]        = $product->descripcion;
        $data["marca"]              = (empty($product->marca)) ? '-' : $product->marca;
        $data["presentacion"]       = (empty($product->presentacion)) ? '-' : $product->presentacion;
        $data["precio_compra"]      = 'S/' . $product->precio_compra;
        $data["precio_venta"]       = 'S/' . $product->precio_venta;
        $data["stock"]              = (empty($product->stock)) ? '-' : $product->stock;
        $data["fecha_vencimiento"]  =(empty($product->fecha_vencimiento)) ? '-' : $product->fecha_vencimiento;
        echo json_encode([
            'status'    => true,
            'data'      => $data
        ]);
    }
}
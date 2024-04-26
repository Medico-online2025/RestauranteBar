<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Models\Department;
use App\Models\District;
use App\Models\IdentityDocumentType;
use App\Models\Provider;
use App\Models\Province;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $data['type_documents']     = IdentityDocumentType::where('estado', 1)->get();
        return view('admin.providers.list', $data);
    }

    public function get()
    {
        $providers     = Provider::orderBy('id', 'DESC')->get();
        return Datatables()
                    ->of($providers)
                    ->addColumn('acciones', function($providers){
                        $id     = $providers->id;
                        $btn    = '<div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                        <div class="dropdown-menu">
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
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }

        $tipo_documento         = $request->input('tipo_documento');
        $idtipo_comprobante_    = ($tipo_documento == "4") ? "1" : "2"; 
        $dni_ruc                = $request->input('dni_ruc');
        $razon_social           = trim($request->input('razon_social'));
        $direccion              = trim($request->input('direccion'));
        $telefono               = trim($request->input('telefono'));
        $departamento           = $request->input('departamento');
        $provincia              = $request->input('provincia');
        $distrito               = $request->input('distrito');
        $search_provider        = Provider::where('dni_ruc', $dni_ruc)->first();

        if(!empty($search_provider))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'El proveedor ya se encuentra registrado',
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }

        if(!empty($departamento))
        {
            if(empty($provincia))
            {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'title'     => 'Espere',
                    'type'      => 'warning'
                ]);
            }

            elseif(empty($distrito))
            {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'title'     => 'Espere',
                    'type'      => 'warning'
                ]);
            }

            else
            {
                Provider::insert([
                    'iddoc'         => $tipo_documento,
                    'dni_ruc'       => $dni_ruc,
                    'nombres'       => mb_strtoupper($razon_social),
                    'direccion'     => mb_strtoupper($direccion),
                    'codigo_pais'   => 'PE',
                    'ubigeo'        => $distrito,
                    'telefono'      => $telefono
                ]);

                $last_id            = Provider::latest('id')->first()['id'];

                echo json_encode([
                    'status'                => true,
                    'msg'                   => 'Registro agregado correctamente',
                    'type'                  => 'success',
                    'idtipo_comprobante_'   => $idtipo_comprobante_,
                    'last_id'               => $last_id
                ]);
            }
        }
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


        $id             = $request->input('id');
        $provider       = Provider::where('id', $id)->first();
        $district       = District::where('codigo', $provider->ubigeo)->first();
        $province       = Province::where('codigo', $district->provincia_codigo)->first();
        $department     = Department::where('codigo', $district->departamento_codigo)->first();
        $departments    = Department::get();
        $provinces      = Province::get();
        $districts      = District::get();

        echo json_encode([
            'status'        => true,
            'provider'      => $provider,
            'district'      => $district,
            'province'      => $province,
            'department'    => $department,
            'departments'   => $departments,
            'provinces'     => $provinces,
            'districts'     => $districts
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
        $tipo_documento     = $request->input('tipo_documento');
        $dni_ruc            = $request->input('dni_ruc');
        $razon_social       = trim($request->input('razon_social'));
        $direccion          = trim($request->input('direccion'));
        $telefono           = trim($request->input('telefono'));
        $departamento       = $request->input('departamento');
        $provincia          = $request->input('provincia');
        $distrito           = $request->input('distrito');
        
        if(!empty($departamento))
        {
            if(empty($provincia))
            {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'title'     => 'Espere',
                    'type'      => 'warning'
                ]);
            }

            elseif(empty($distrito))
            {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'title'     => 'Espere',
                    'type'      => 'warning'
                ]);
            }

            else
            {
                Provider::where('id', $id)->update([
                    'iddoc'         => $tipo_documento,
                    'dni_ruc'       => $dni_ruc,
                    'nombres'       => mb_strtoupper($razon_social),
                    'direccion'     => mb_strtoupper($direccion),
                    'codigo_pais'   => 'PE',
                    'ubigeo'        => $distrito,
                    'telefono'      => $telefono
                ]);

                echo json_encode([
                    'status'    => true,
                    'msg'       => 'Registro actualizado correctamente',
                    'title'     => '¡Bien!',
                    'type'      => 'success'
                ]);
            }
        }
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

        $id         = $request->input('id');
        $search_b   = count(Buy::where('idproveedor', $id)->get()); 
        if($search_b)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'No se puede eliminar. El proveedor tiene compras realizadas',
                'type'      => 'warning'
            ]);
            return;
        }

        Provider::where('id', $id)->delete();
        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado correctamente',
            'title'     => '¡Bien!',
            'type'      => 'success'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Client;
use App\Models\Department;
use App\Models\District;
use App\Models\IdentityDocumentType;
use App\Models\Province;
use App\Models\SaleNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        $data['type_documents']     = IdentityDocumentType::where('estado', 1)->get();
        return view('admin.clients.list', $data);
    }

    public function get()
    {
        $clients     = Client::orderBy('id', 'DESC')->get();
        return Datatables()
                    ->of($clients)
                    ->addColumn('acciones', function($clients){
                        $id     = $clients->id;
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

    public function load_ubigeo()
    {
        $departments    = Department::get();
        $provinces      = Province::get();
        $districts      = District::get();

        echo json_encode([
            'departments'   => $departments,
            'provinces'     => $provinces,
            'districts'     => $districts
        ]);
    }

    public function search(Request $request)
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

        $type_document      = trim($request->input('type_document'));
        $dni_ruc            = trim($request->input('dni_ruc'));
        $client             = $this->verify__client($dni_ruc);

        if($client->status == 404)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => $client->message,
                'type'      => 'warning'
            ]);
        }
        else
        {   
            $data           = $client->data;
            $nombres        = '';
            $direccion      = '';
            $ubigeo         = NULL;

            if($type_document == '2') // DNI
            {
                $nombres        = $data->nombres . ' ' . $data->apellido_paterno . ' ' . $data->apellido_materno;
                $direccion      = $data->direccion;
                $ubigeo         = $data->ubigeo_sunat;
            }
            else
            {
                $nombres        = $data->nombre_o_razon_social;
                $direccion      = $data->direccion;
                $ubigeo         = ($data->ubigeo_sunat == "-" || empty($data->ubigeo_sunat)) ? null : $data->ubigeo_sunat;
            }

            echo json_encode([
                'status'    => true,
                'nombres'   => $nombres,
                'direccion' => $direccion,
                'ubigeo'    => $ubigeo
            ]);
        }
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

        $tipo_documento         = $request->input('tipo_documento');
        $idtipo_comprobante_    = ($tipo_documento == "4") ? "1" : "2"; 
        $dni_ruc                = $request->input('dni_ruc');
        $razon_social           = trim($request->input('razon_social'));
        $direccion              = trim($request->input('direccion'));
        $telefono               = trim($request->input('telefono'));
        $departamento           = $request->input('departamento');
        $provincia              = $request->input('provincia');
        $distrito               = $request->input('distrito');
        $search_client          = Client::where('dni_ruc', $dni_ruc)->first();

        if(!empty($search_client))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'El cliente ya se encuentra registrado',
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
                    'type'      => 'warning'
                ]);
            }

            elseif(empty($distrito))
            {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'type'      => 'warning'
                ]);
            }
            else
            {
                Client::insert([
                    'iddoc'         => $tipo_documento,
                    'dni_ruc'       => $dni_ruc,
                    'nombres'       => mb_strtoupper($razon_social),
                    'direccion'     => mb_strtoupper($direccion),
                    'codigo_pais'   => 'PE',
                    'ubigeo'        => $distrito,
                    'telefono'      => $telefono
                ]);
                
                $last_id            = Client::latest('id')->first()['id'];
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
                'type'      => 'warning'
            ]);
            return;
        }


        $id             = $request->input('id');
        $client         = Client::where('id', $id)->first();
        $district       = District::where('codigo', $client->ubigeo)->first();
        $province       = Province::where('codigo', $district->provincia_codigo)->first();
        $department     = Department::where('codigo', $district->departamento_codigo)->first();
        $departments    = Department::get();
        $provinces      = Province::get();
        $districts      = District::get();

        echo json_encode([
            'status'        => true,
            'client'        => $client,
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
                    'type'      => 'warning'
                ]);
            }

            elseif(empty($distrito))
            {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'type'      => 'warning'
                ]);
            }

            else
            {
                Client::where('id', $id)->update([
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
        $search_bf  = count(Billing::where('idcliente', $id)->get()); 
        $search_nv  = count(SaleNote::where('idcliente', $id)->get());

        if($search_bf > 0 || $search_nv > 0)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'No se puede eliminar. El cliente tiene ventas realizadas',
                'type'      => 'warning'
            ]);
            return;
        }

        Client::where('id', $id)->delete();
        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado correctamente',
            'type'      => 'success'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $data["roles"]      = Role::get();
        $data['cashes']     = Cash::all();
        return view('admin.users.list', $data);
    }

    public function get()
    {
        $profiles     = User::select('users.*', 'cashes.descripcion as caja')
                        ->join('cashes', 'users.idcaja', 'cashes.id')                
                        ->orderBy('id', 'DESC')->get();

        return Datatables()
                    ->of($profiles)
                    ->addColumn('acciones', function($profiles){
                        $id     = $profiles->id;
                        $btn    = '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                    <div class="dropdown-menu">
                                    <a class="dropdown-item btn-view-roles" data-id="'.$id.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-unlock mr-50 menu-icon"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>
                                            <span>Asignar Roles</span>
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
        if(!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $nombres            = trim($request->input('nombres'));
        $user               = trim($request->input('user'));
        $password           = trim($request->input('password'));
        $idcaja             = trim($request->input('idcaja'));
        User::create([
            'nombres'   => mb_strtoupper($nombres),
            'user'      => mb_strtolower($user),
            'password'  => bcrypt($password),
            'estado'    => 1,
            'idcaja'    => $idcaja
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro insertado con éxito',
            'type'      => 'success'
        ]);
    }

    public function detail(Request $request)
    {
        if(!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = $request->input('id');
        $user               = User::where('id', $id)->first();
        echo json_encode([
            'status'    => true,
            'user'      => $user
        ]);
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = $request->input('id');
        $nombres            = trim($request->input('nombres'));
        $user               = trim($request->input('user'));
        $idcaja             = trim($request->input('idcaja'));
        $password           = trim($request->input('password'));

        if(empty($password))
        {
            User::where('id', $id)->update([
                'nombres'   => mb_strtoupper($nombres),
                'user'      => mb_strtolower($user),
                'idcaja'    => $idcaja
            ]);
        }
        else
        {
            User::where('id', $id)->update([
                'nombres'   => mb_strtoupper($nombres),
                'user'      => mb_strtolower($user),
                'idcaja'    => $idcaja,
                'password'  => bcrypt($password)
            ]);
        }
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
                'type'      => 'warning'
            ]);
            return;
        }

        $id          = $request->input('id');
        User::where('id', $id)->delete();

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado correctamente',
            'type'      => 'success'
        ]);
    }

    public function view_role(Request $request)
    {
        if(!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                     = $request->input('id');
        $data["user"]           = User::where('id', $id)->first();
        $data["roles"]          = Role::get();
        $data["selRoles"]       = $data["user"]->roles->pluck('id')->toArray();
        echo json_encode([
            'status'            => true,
            'data'              => $data
        ]);
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id         = $request->input('id');
        $user       = User::where('id', $id)->first();
        $user->roles()->sync($request->roles);
        echo json_encode([
            'status'    => false,
            'msg'       => 'Se asignaron los roles correctamente',
            'type'      => 'success'
        ]);
    }
}

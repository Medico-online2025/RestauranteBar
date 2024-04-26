<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\Serie;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use SebastianBergmann\Type\NullType;

class SerieController extends Controller
{
    public function index()
    {
        $data['type_documents']     = TypeDocument::where('estado', 1)->get();
        $data['cashes']             = Cash::all();
        return view('admin.series.list', $data);
    }

    public function get()
    {
        $series     = Serie::select('series.*', 'type_documents.descripcion as tipo_documento', 'cashes.descripcion as caja')
                            ->join('type_documents', 'series.idtipo_documento', 'type_documents.id')
                            ->join('cashes', 'series.idcaja', 'cashes.id')
                            ->orderBy('id', 'DESC')->get();
        return Datatables()
                    ->of($series)
                    ->addColumn('acciones', function($series){
                        $id     = $series->id;
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
                'type'      => 'warning'
            ]);
            return;
        }

        $serie                          = trim($request->input('serie'));
        $correlativo                    = trim($request->input('correlativo'));
        $tipo_documento                 = trim($request->input('tipo_documento'));
        $idcaja                         = $request->input('idcaja');
        $idtipo_documento_relacionado   = NULL;

        if((int) $tipo_documento == 6)
        {   
            $tipo_b_f                   = substr($serie, 0, 2);
            if($tipo_b_f == 'FC')
                $idtipo_documento_relacionado = 1;
            else
                $idtipo_documento_relacionado = 2;
        }
        else
        {
            $idtipo_documento_relacionado = NULL;
        }
        
        /* if($tipo_documento == 1)
        {
            $idtipo_documento_relacionado = 1;
        }
        elseif ($tipo_documento == 2)
        {
            $idtipo_documento_relacionado = 2;
        }
        else
        {
            $idtipo_documento_relacionado = NULL;
        } */

        if(strlen($serie) != 4)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Le serie debe contener 04 dígitos',
                'type'      => 'warning'
            ]);
            return;
        }

        if(strlen($correlativo) != 8)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'El correlativo debe contener 08 dígitos',
                'type'      => 'warning'
            ]);
            return;
        }

        $buscar_serie       = Serie::where('serie', mb_strtoupper($serie))->where('idtipo_documento', $tipo_documento)->where('idcaja', $idcaja)->get();
        if(count($buscar_serie) > 0)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Serie existente, intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        Serie::insert([
            'serie'                         => mb_strtoupper($serie),
            'correlativo'                   => str_pad($correlativo, STR_PAD_RIGHT),
            'idtipo_documento'              => $tipo_documento,
            'idtipo_documento_relacionado'  => $idtipo_documento_relacionado,
            'idcaja'                        => $idcaja
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro insertado con éxito',
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

        $id       = $request->input('id');
        $serie    = Serie::where('id', $id)->first();
        echo json_encode(['status'  => true, 'serie' => $serie]);
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

        $id                             = $request->input('id');
        $serie                          = trim($request->input('serie'));
        $correlativo                    = trim($request->input('correlativo'));
        $tipo_documento                 = trim($request->input('tipo_documento'));
        $idcaja                         = $request->input('idcaja');

        $idtipo_documento_relacionado   = NULL;
        
        if($tipo_documento == 1)
        {
            $idtipo_documento_relacionado = 1;
        }
        elseif ($tipo_documento == 2)
        {
            $idtipo_documento_relacionado = 2;
        }
        else
        {
            $idtipo_documento_relacionado = NULL;
        }

        if(strlen($serie) != 4)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Le serie debe contener 04 dígitos',
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }

        if(strlen($correlativo) != 8)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'El correlativo debe contener 08 dígitos',
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }

        Serie::where('id', $id)->update([
            'serie'                         => mb_strtoupper($serie),
            'correlativo'                   => str_pad($correlativo, STR_PAD_RIGHT),
            'idtipo_documento'              => $tipo_documento,
            'idtipo_documento_relacionado'  => $idtipo_documento_relacionado,
            'idcaja'                        => $idcaja
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
                'type'      => 'warning'
            ]);
            return;
        }

        $id            = $request->input('id');
        Serie::where('id', $id)->delete();

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado con éxito',
            'type'      => 'success'
        ]);
    }
}

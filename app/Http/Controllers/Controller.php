<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\District;
use App\Models\Product;
use App\Models\Province;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function verify__client($dni_ruc)
    {
        $token  = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMzMiLCJuYW1lIjoiS3Jvd2VkIiwiZW1haWwiOiJrcm93ZWQxN0BnbWFpbC5jb20iLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJjb25zdWx0b3IifQ.JIt5DAz_kQApUGphoh2Z_sggXVZBCPgxRkk_yNFSDOg';
        $data   = null;
        
        if(strlen($dni_ruc) == 8)
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.factiliza.com/pe/v1/dni/info/' . $dni_ruc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token
                ),
            ));

            $response   = curl_exec($curl);
            $data       = json_decode($response);
            curl_close($curl);
        }
        else
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.factiliza.com/pe/v1/ruc/info/' . $dni_ruc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token
                ),
            ));

            $response   = curl_exec($curl);
            $data       = json_decode($response);
            curl_close($curl);
        }

        return $data;
    }
    
    public function get_ubigeo($ubigeo)
    {       
        $data                   = [];
        $distrito               = District::where('codigo', $ubigeo)->first();
        $data['distrito']       = $distrito->descripcion;
        $provincia              = Province::where('codigo', $distrito->provincia_codigo)
                                    ->where('departamento_codigo', $distrito->departamento_codigo)
                                    ->first();
        $data['provincia']      = $provincia->descripcion;
        $departamento           = Department::where('codigo', $distrito->departamento_codigo)->first();
        $data['departamento']   = $departamento->descripcion;
        return $data;
    }

    public function redondeado($numero, $decimales = 2) 
    {
        $factor = pow(10, $decimales);
        return (round($numero*$factor)/$factor); 
    }

    public function send_msg_wpp($telefono, $header, $mensaje)
    {
        $curl           = curl_init();
        $token          =  'EAAUkSy7HuzEBO7SyCvzH7SmVsTpXk7aCRtqQWzI9trcdMZAxMfhqu582zUYDYInIb4Y0q30KVR5OlPhqAmu3uuchFtof0or0TTMncrBZCSQ9a8huQGKS1rZAdIVGVFie7HZAYtxh0DlVQcO07V1C8QYteS8zQVEamoDv49jWZBSHS4jYUdCV304OgPmSOh8so';

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.facebook.com/v18.0/162704683603332/messages',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "messaging_product": "whatsapp",
            "to": "'. $telefono .'",
            "type": "template",
            "template": {
                "name": "send_voucher",
                "language": {
                    "code": "es"
                },
                "components": [
                    {
                        "type": "header",
                        "parameters": [
                          {
                            "type": "text",
                            "text": "'. $header .'"
                          },
                        ]
                    },
                    {
                      "type": "body",
                      "parameters": [
                        {
                          "type": "text",
                          "text": "'. $mensaje .'"
                        },
                      ]
                    }
                ]
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}

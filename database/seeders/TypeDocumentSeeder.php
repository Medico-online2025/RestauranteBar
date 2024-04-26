<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type_documents =
        [
            [
                'codigo'        => '01',
                'descripcion'   => 'FACTURA ELECTRÓNICA',
                'estado'        => 1
            ],

            [
                'codigo'        => '03',
                'descripcion'   => 'BOLETA DE VENTA ELECTRÓNICA',
                'estado'        => 1
            ],
            
            [
                'codigo'        => '04',
                'descripcion'   => 'LIQUIDACIÓN DE COMPRA',
                'estado'        => 0
            ],

            [
                'codigo'        => '05',
                'descripcion'   => 'BOLETOS DE TRANSPORTE AÉREO QUE EMITEN LAS COMPAÑÍAS DE AVIACIÓN COMERCIAL POR EL SERVICIO DE TRANSPORTE AÉREO REGULAR DE PASAJEROS, EMITIDO DE MANERA MANUAL, MECANIZADA O POR MEDIOS ELECTRÓNICOS (BME)',
                'estado'        => 0
            ],

            [
                'codigo'        => '06',
                'descripcion'   => 'CARTA DE PORTE AÉREO',
                'estado'        => 0
            ],

            [
                'codigo'        => '07',
                'descripcion'   => 'NOTA DE CRÉDITO ELECTRÓNICA',
                'estado'        => 1
            ],

            [
                'codigo'        => '02',
                'descripcion'   => 'NOTA DE VENTA',
                'estado'        => 1
            ],

            [
                'codigo'        => '08',
                'descripcion'   => 'NOTA DE DÉBITO ELECTRÓNICA',
                'estado'        => 0
            ],

            [
                'codigo'        => '09',
                'descripcion'   => 'GUÍA DE REMISIÓN REMITENTE',
                'estado'        => 0
            ],

            [
                'codigo'        => '11',
                'descripcion'   => 'PÓLIZA EMITIDA POR LAS BOLSAS DE VALORES',
                'estado'        => 0
            ],

            [
                'codigo'        => '12',
                'descripcion'   => 'TICKET DE MÁQUINA REGISTRADORA',
                'estado'        => 0
            ],

            [
                'codigo'        => '13',
                'descripcion'   => 'DOCUMENTO EMITIDO POR BANCOS, INSTITUCIONES FINANCIERAS, CREDITICIAS Y DE SEGUROS QUE SE ENCUENTREN BAJO EL CONTROL DE LA SUPERINTENDENCIA DE BANCA Y SEGUROS',
                'estado'        => 0
            ],

            [
                'codigo'        => '14',
                'descripcion'   => 'RECIBO SERVICIOS PÚBLICOS',
                'estado'        => 0
            ],

            [
                'codigo'        => '15',
                'descripcion'   => 'BOLETOS EMITIDOS POR EL SERVICIO DE TRANSPORTE TERRESTRE REGULAR URBANO DE PASAJEROS Y EL FERROVIARIO PÚBLICO DE PASAJEROS PRESTADO EN VÍA FÉRREA LOCAL',
                'estado'        => 0
            ],

            [
                'codigo'        => '16',
                'descripcion'   => 'BOLETO DE VIAJE EMITIDO POR LAS EMPRESAS DE TRANSPORTE PÚBLICO INTERPROVINCIAL DE PASAJEROS',
                'estado'        => 0
            ],

            [
                'codigo'        => '18',
                'descripcion'   => 'DOCUMENTOS EMITIDOS POR LAS AFP',
                'estado'        => 0
            ],

            [
                'codigo'        => '19',
                'descripcion'   => 'BOLETO POR ATRACCIONES Y ESPECTÁCULOS PÚBLICOS',
                'estado'        => 0
            ],

            [
                'codigo'        => '20',
                'descripcion'   => 'COMPROBANTE DE RETENCIÓN',
                'estado'        => 0
            ],

            [
                'codigo'        => '21',
                'descripcion'   => 'CONOCIMIENTO DE EMBARQUE POR EL SERVICIO DE TRANSPORTE DE CARGA MARÍTIMA',
                'estado'        => 0
            ],

            [
                'codigo'        => '23',
                'descripcion'   => 'PÓLIZAS DE ADJUDICACIÓN POR REMATE O ADJUDICACIÓN DE BIENES',
                'estado'        => 0
            ],

            [
                'codigo'        => '24',
                'descripcion'   => 'CERTIFICADO DE PAGO DE REGALÍAS EMITIDAS POR PERUPETRO S.A.',
                'estado'        => 0
            ],

            [
                'codigo'        => '28',
                'descripcion'   => 'ETIQUETAS POR EL PAGO DE LA TARIFA UNIFICADA DE USO DE AEROPUERTO – TUUA',
                'estado'        => 0
            ],

            [
                'codigo'        => '29',
                'descripcion'   => 'DOCUMENTOS EMITIDOS POR LA COFOPRI',
                'estado'        => 0
            ],

            [
                'codigo'        => '30',
                'descripcion'   => 'DOCUMENTOS EMITIDOS POR LAS EMPRESAS QUE DESEMPEÑAN EL ROL ADQUIRENTE EN LOS SISTEMAS DE PAGO MEDIANTE TARJETAS DE CRÉDITO Y DÉBITO, EMITIDAS POR BANCOS E INSTITUCIONES FINANCIERAS O CREDITICIAS, DOMICILIADOS O NO EN EL PAÍS.',
                'estado'        => 0
            ],

            [
                'codigo'        => '31',
                'descripcion'   => 'GUÍA DE REMISIÓN TRANSPORTISTA',
                'estado'        => 0
            ],

            [
                'codigo'        => '31',
                'descripcion'   => 'DOCUMENTOS EMITIDOS POR RECAUDADORAS DE LA GARANTÍA DE RED PRINCIPAL',
                'estado'        => 0
            ],
        ];

        foreach($type_documents as $type_document)
        {
            $new_type_document  = new \App\Models\TypeDocument();
            foreach($type_document as $k => $value)
            {
                $new_type_document->{$k} = $value;
            }

            $new_type_document->save();
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ConsultoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function consultores()
    {
        $consultores = DB::table('cao_usuario')
                    ->select('cao_usuario.co_usuario')
                    ->join('permissao_sistema', 'cao_usuario.co_usuario', 'permissao_sistema.co_tipo_usuario')
                    ->get();

        return response()->json([
            'data' => $consultores
        ]);
    }

    /***
     * METHOD DATA CONSULTANT
     * @param array ...$parameters
     * @param consultante
     * @param fecha_inicio
     * @param fecha_fin
     * @return \Illuminate\Http\JsonResponse
     */
    public function consolidadoConsultor(...$parameters)
    {

        $usuarios = explode(",", $parameters[0]);

        $datosAgrupados = [];

        $fechaInicio = str_replace('-', '/', $parameters[1]);
        $fechaFin = str_replace('-', '/', $parameters[2]);

        for ($i=0; $i<count($usuarios); $i++) {

            $recetaLiquida = DB::select(
                "SELECT cu.no_usuario, cf.co_os as os,co.co_usuario, MONTH(cf.data_emissao) as mes, 
              (select brut_salario from cao_salario where co_usuario = co.co_usuario) as fijo, 
              sum(cf.valor - ((cf.valor * cf.total_imp_inc)) / 100) 
              as receta, sum(cf.valor - ((cf.total_imp_inc * cf.comissao_cn)) / 100) as comision,
              sum(cf.valor - ((cf.valor * cf.total_imp_inc)) / 100)- (select brut_salario from cao_salario 
              where co_usuario = co.co_usuario) + sum(cf.valor - ((cf.total_imp_inc * cf.comissao_cn)) / 100) as lucro              
              FROM cao_fatura as cf inner join cao_os as co 
              using (co_os) right join cao_usuario as cu 
              using (co_usuario) 
              where data_emissao between
              '$fechaInicio' and '$fechaFin' and co.co_usuario IN ('$usuarios[$i]')
              GROUP BY co.co_usuario, MONTH(cf.data_emissao) order by co.co_usuario, MONTH(cf.data_emissao)");

            array_push($datosAgrupados, $recetaLiquida);
        }
        return response()->json(['data'=>$datosAgrupados]);
    }

    public function consolidadoConsultorGraficas(...$parameters)
    {
        $usuarios = explode(",", $parameters[0]);

        $fechaInicio = str_replace('-', '/', $parameters[1]);
        $fechaFin = str_replace('-', '/', $parameters[2]);

        $datosAgrupados = [];

        for ($i=0; $i<count($usuarios); $i++) {

            $recetaLiquida = DB::select(
                "SELECT cu.no_usuario, cf.co_os as os,co.co_usuario, MONTH(cf.data_emissao) as mes, 
              (select brut_salario from cao_salario where co_usuario = co.co_usuario) as fijo, 
              sum(cf.valor - ((cf.valor * cf.total_imp_inc)) / 100) 
              as receta, sum(cf.valor - ((cf.total_imp_inc * cf.comissao_cn)) / 100) as comision,
              sum(cf.valor - ((cf.valor * cf.total_imp_inc)) / 100)- (select brut_salario from cao_salario 
              where co_usuario = co.co_usuario) + sum(cf.valor - ((cf.total_imp_inc * cf.comissao_cn)) / 100) as lucro              
              FROM cao_fatura as cf inner join cao_os as co 
              using (co_os) right join cao_usuario as cu 
              using (co_usuario) 
              where data_emissao between
              '$fechaInicio' and '$fechaFin' and co.co_usuario IN ('$usuarios[$i]')
              GROUP BY co.co_usuario, MONTH(cf.data_emissao) order by co.co_usuario, MONTH(cf.data_emissao)");

            array_push($datosAgrupados, $recetaLiquida);
        }
        return response()->json(['data'=>$datosAgrupados]);
    }

    public function nombremes($mes){
        setlocale(LC_TIME, 'es_ES');
        $nombre=strftime("%B",mktime(0, 0, 0, $mes));
        return $nombre;
    }
}

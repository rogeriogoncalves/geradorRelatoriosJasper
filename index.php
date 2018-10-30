 <!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    require "vendor/autoload.php";
    require_once "vendor/autoload.php";
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            use GuzzleHttp\Client;
            include 'interacaoJasperServer.php';
            ####################################################################################################################################################################
            # cliente jasper server
            $client = new Client(['proxy'=>'']);
            
            # Variáveis Auxiliares
            $interacaoJS = new interacaoJasperServer();
            $filename = 'mysqlMyFirstReport';
            
            # Criar Datas a partir do sistema
            date_default_timezone_set('America/Sao_Paulo');
            $date = date('Y-m-d H:i:s');
            
            ####################################################################################################################################################################
            # Variáveis de Login
            $urlLogin = 'http://localhost:8080/jasperserver/rest/login';
            $paramsLogin = array(
                'j_username'    => 'jasperadmin',
                'j_password'    => 'jasperadmin'
            );
            # Login
            $requestlogin = $interacaoJS->login($client, $urlLogin, $paramsLogin);
            
            ####################################################################################################################################################################
            # Variáveis de Requisição de Report
            $urlRequisicao = 'http://localhost:8080/jasperserver/rest_v2/reportExecutions';
            $paramsRequisicao = [
                'Name' => 'Cookie',
                'Value' => ' $Version=0; ' . $requestlogin->getHeaderLine('Set-Cookie'),
                'Domain' => 'localhost'
            ];
            # Requisita Report
            $cookieJar = $interacaoJS->geraCookieJar($paramsRequisicao);
            
            $requestReport = $interacaoJS->requisitarReport($client, $filename, $urlRequisicao, $cookieJar);
            ####################################################################################################################################################################
            # Variáveis de Pool de Reports
//            $urlPool = 'http://jasper-homo1.mpmg.mp.br:80/jasperserver/rest_v2/reportExecutions/'.$id->requestId.'/status/';
            # Verifica se o arquivo está pronto
//            do{
//                $value = $interacaoJS->poolingRequisicoes($client, $urlPool, $cookieJar);
//            }while($value->value != 'ready');
            
            ####################################################################################################################################################################
            # Variáveis de Download de Report
            $urlDownload = 'http://localhost:8080/jasperserver/rest_v2/reportExecutions/'.$requestReport->requestId.'/exports/'.$requestReport->exports['0']->id.'/outputResource';
            
            # Download do Report se estiver pronto
//            if($value->value == 'ready'){
               $interacaoJS->baixarReport($client, $urlDownload, $cookieJar, $date, $filename);
//            }
        ?>
    </body>
</html>


<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of interacaoJasperServer
 *
 * @author glmiranda
 */

class interacaoJasperServer {
    
    function login(GuzzleHttp\Client $client, $url, $params){
        return $client->post($url,['form_params' => $params]);
    }
    
    function geraCookieJar($params){
        $cookie = new GuzzleHttp\Cookie\SetCookie($params);
        $cookieArray = [$cookie];
        
        return new GuzzleHttp\Cookie\CookieJar(false, $cookieArray);
    }
    
    function requisitarReport(GuzzleHttp\Client $client, $filename, $url, $cookieJar){
        $post = array(
            'reportUnitUri' => '/reports/interactive/'.$filename,
            'async' => true,
            'freshData' => false,
            'saveDataSnapshot' => false,
//            'parameters' => array(
//                'reportParameter' => array(      
//                    ['name' => 'saltos', 'value' => array($salto)],
//                    ['name' => 'ArrayFeitos', 'value' => $arrayFeitos]
//                )
//            ),
            'outputFormat' => 'pdf',
            'interactive' => true
        );
        
        $request = $client->post($url, 
                    ['headers' => ['Content-Type' => 'application/json', 'accept' => 'application/json'],
                    'cookies' => $cookieJar, 'json' => $post]);
        return json_decode($request->getBody()->getContents());
    }
    
    function poolingRequisicoes(GuzzleHttp\Client $client, $url, $cookieJar){
        $status = array(
            'errorDescriptor' => array(
                'errorCode' => 'input.controls.validation.error',
                'message' => 'Input controls validation failure'
            ),
            'value' => 'failed'
        );
        
        $request = $client->get(
            $url, 
            ['headers' => ['Content-Type' => 'application/json', 'accept' => 'application/json'],
            'cookies' => $cookieJar, 
            'json' => $status]
        );

        $value = json_decode($request->getBody()->getContents());
        htmlspecialchars($value->value);
        
        return $value;
    }
    
    function baixarReport(GuzzleHttp\Client $client, $url, $cookieJar, $date, $filename){
        var_dump($url);
        $request = $client->get($url, ['cookies' => $cookieJar]);
        
        $text = $request->getBody();
        header('Content-Description: File Transfer');
        header("Content-type:application/pdf");
        header('Content-Disposition: inline; filename='.$filename.'.pdf');
        echo $text;
    }
}
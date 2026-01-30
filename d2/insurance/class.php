<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class InsuranseComponent extends CBitrixComponent
{
    /** 
     * Создаёт SoapClient для работы с сервисом
     */
    public function establishConnection()
    {
        $wsdl  = $this->arParams["ADRESS"] ?? '';
        $login = $this->arParams["LOGIN"] ?? '';
        $pass  = $this->arParams["PASSWORD"] ?? '';

        if (!$wsdl || !$login || !$pass) {
            $this->arResult['ERROR'] = "Не заданы параметры подключения к SOAP: ADRESS, LOGIN или PASSWORD";
            return false;
        }

        // Потоковый контекст для SSL (на dev-серверах с самоподписанным сертификатом)
        $context = stream_context_create([
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ]
        ]);

        // Проверяем, что WSDL реально отдаёт XML
        $raw = @file_get_contents($wsdl, false, $context);
        if ($raw === false) {
            $this->arResult['ERROR'] = "Не удалось загрузить WSDL. Проверьте URL: $wsdl";
            return false;
        }

        if (strpos($raw, '<definitions') === false) {
            $this->arResult['ERROR'] = "URL WSDL возвращает HTML вместо XML. Проверьте URL: $wsdl";
            return false;
        }
        
        $options = [
            'trace'          => true,
            'exceptions'     => true,
            'encoding'       => 'UTF-8',
            'login'          => $login,
            'password'       => $pass,
            'stream_context' => $context,
            'cache_wsdl'     => WSDL_CACHE_NONE,
            'user_agent'     => 'PHPSoapClient'
        ];

        $_SESSION["wsdl"] = $wsdl;
        $_SESSION["options"] = $options;
    }

    public function executeComponent()
    {
        // Пробуем подключиться к SOAP
        $client = $this->establishConnection();

        if (!$client) {
            // Если ошибка, просто выводим в шаблоне
            $this->includeComponentTemplate();
            return;
        }

        $this->arResult['SOAP_CLIENT'] = $client;
        $this->includeComponentTemplate();
    }
}

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мебельная компания");
?><?php

    $wsdl = "https://soapdev.d2insur.ru/pay/PolicyPay.wsdl";

    $options = [
        'trace'      => true, //отладка
        'exceptions' => true,
        'encoding'   => 'UTF-8',
        'login'    => 'testForUser',
        'password' => 'testUser520',
    ];

    try {
        $client = new SoapClient($wsdl, $options);
        //echo "SOAP client OK\n";
    } catch (SoapFault $e) {
        //echo 'Ошибка подключения: ' . $e->getMessage();
        exit;
    }

    $params = [
        'productId' => 3523309775,
        'person' => [
            'INSURER_FIRSTNAME' => 'Иван',
            'INSURER_SURNAME'  => 'Иванов',
            'INSURER_BIRTHDAY' => '04.06.1987',
            'INSURER_PHONE' => '+79237945860',
            'INSURER_ADDRESS' => 'Новосибирск',
            'PASSPORT_NUMBER' => '5747 373636',
        ],
    ];

    try {
        $response = $client->__soapCall(
            'obtainCertificate',
            [$params]
        );


    } catch (SoapFault $e) {
        AddMessage2Log($e->getMessage());
        AddMessage2Log($client->__getLastRequest());
        AddMessage2Log($client->__getLastResponse());

    }

    $base64 = $response->cert->certFile;

    // убираем переносы строк
    $base64 = preg_replace('/\s+/', '', $base64);
    
    $pdf = base64_decode($base64, true);
    if ($pdf === false) {
        die('Base64 decode error');
    }
    
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="policy.pdf"');
    header('Content-Length: ' . strlen($pdf));
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    header('Cache-Control: private');
    header('Pragma: public');
    
    echo $pdf;
    exit;
    
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$getPolicy = new GetPolicy();
$getPolicy->establishConnection($_SESSION);

$response = $getPolicy->obtainPolicy($_POST);
$getPolicy->downloadToUser($response);

class GetPolicy {

    private SoapClient $client;

    public function establishConnection(array $data): bool
    {
        try {
            $this->client = new SoapClient(
                $data["wsdl"],
                $data["options"]
            );
            return true;
        } catch (SoapFault $e) {
            AddMessage2Log("SOAP CONNECT ERROR: ".$e->getMessage());
            return false;
        }
    }

    // Шаг 1: создаём полис
    public function obtainPolicy(array $arItem)
    {
        if (!isset($this->client)) {
            throw new RuntimeException('SOAP client not initialized');
        }

        $params = [
            'productId' => (int)$arItem["productId"],
            'person' => [
                'INSURER_FIRSTNAME' => $arItem["name"],
                'INSURER_SURNAME'   => $arItem["surname"],
                'INSURER_BIRTHDAY'  => $arItem["birthday"],
                'INSURER_PHONE'     => $arItem["phone"],
                'INSURER_ADDRESS'   => $arItem["address"],
                'PASSPORT_NUMBER'   => $arItem["passport"],
            ],
        ];

        try {
            return $this->client->__soapCall('obtainCertificate', [$params]);
        } catch (SoapFault $e) {
            AddMessage2Log($e->getMessage());
            AddMessage2Log($this->client->__getLastRequest());
            AddMessage2Log($this->client->__getLastResponse());
            throw $e;
        }
    }

    public function downloadToUser($response)
    {
        if (!empty($response->error) || !empty($response->message)) {
            AddMessage2Log('SOAP ERROR: '.print_r($response, true));
            die;
        }
        $certFile = $response->cert->certFile;
        if (is_object($certFile) && isset($certFile->_)) {
            $base64 = $certFile->_;
        } else {
            $base64 = (string)$certFile;
        }
        $base64 = preg_replace('/\s+/', '', $base64);
    
        if (empty($base64)) {
            AddMessage2Log('PDF is empty!');
            AddMessage2Log(print_r($response->cert, true));
            die('PDF not found');
        }
        $pdf = base64_decode($base64, true);
        if ($pdf === false) {
            AddMessage2Log("Base64 decode error");
            die('Base64 decode error');
        }
        while (ob_get_level()) ob_end_clean();
    
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="policy.pdf"');
        header('Content-Length: ' . strlen($pdf));
        header('Cache-Control: private');
        header('Pragma: public');
    
        echo $pdf;
        exit;
    }
    
}


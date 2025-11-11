<?php

namespace App\Services\Afip;

use Exception;
use App\Models\SystemLog;

class WsaaClient
{
    private string $url;
    private string $cert;
    private string $key;
    private string $service = 'wsfe';

    public function __construct(bool $homologacion = true)
    {
        $this->url = $homologacion
            ? 'https://wswhomo.afip.gov.ar/ws/services/LoginCms'
            : 'https://wsaa.afip.gov.ar/ws/services/LoginCms';

        $this->cert = storage_path('app/afip/certs/homo/secar-homo.crt');
        $this->key  = storage_path('app/afip/certs/homo/secar-homo.key');
    }

    public function login()
    {
        $tra = $this->createTRA();
        $cms = $this->signTRA($tra);
        return $this->callWSAA($cms);
    }

    private function createTRA()
    {
        $uniqueId = time();
        $generationTime = gmdate('Y-m-d\TH:i:s\Z', time() - 60);
        $expirationTime = gmdate('Y-m-d\TH:i:s\Z', time() + 3600);

        $xml = <<<XML
<loginTicketRequest version="1.0">
  <header>
    <uniqueId>$uniqueId</uniqueId>
    <generationTime>$generationTime</generationTime>
    <expirationTime>$expirationTime</expirationTime>
  </header>
  <service>{$this->service}</service>
</loginTicketRequest>
XML;

        $path = storage_path('app/afip/tra.xml');
        file_put_contents($path, $xml);
        return $path;
    }

    private function signTRA($tra)
    {
        $output = [];
        $cmd = "openssl smime -sign -signer {$this->cert} -inkey {$this->key} -outform DER -nodetach -in $tra | base64";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0) {
            throw new Exception("Error al firmar TRA con OpenSSL");
        }

        return implode('', $output);
    }

    private function callWSAA($cms)
    {
        $wsdl = 'https://wswhomo.afip.gov.ar/wsaa/wsaa.wsdl';
        $client = new \SoapClient($wsdl, ['soap_version' => SOAP_1_2, 'trace' => 1]);

        try {
            $response = $client->loginCms(['in0' => $cms]);
            $xml = simplexml_load_string($response->loginCmsReturn);
            return [
                'token' => (string)$xml->credentials->token,
                'sign'  => (string)$xml->credentials->sign,
            ];
        } catch (Exception $e) {
            SystemLog::create([
                'type' => 'afip',
                'context' => 'wsaa',
                'message' => $e->getMessage(),
                'level' => 'error'
            ]);
            throw $e;
        }
    }
}

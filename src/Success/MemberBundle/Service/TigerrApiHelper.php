<?php

namespace Success\MemberBundle\Service;

use Guzzle\Http\Client;
use Guzzle\Http\QueryString;

class TigerrApiHelper
{
    const PARTNER_INFO_ENDPOINT = '/partner/info';
    
    private $client;
    private $apiBaseUrl;

    public function __construct($apiBaseUrl, $apiKey, $apiSignature)
    {
        $this->apiBaseUrl = $apiBaseUrl;
        $this->client = new Client();
        $this->client->setDefaultOption('auth', [$apiKey, $apiSignature]);
    }
    
    /**
     * @param string $endPoint
     * @param array $query
     */
    private function doGetApiRequest($endPoint, $query)
    {
        $qeryString = new QueryString($query);
        $request = $this->client->createRequest("GET", $this->apiBaseUrl.$endPoint.'?'.$qeryString->__toString());
        $result = $this->client->send($request)->json();
        return $result;
    }
    
    /**
     * @param string $memberEmail
     * @return string
     */
    public function getSponsorEmail($memberEmail)
    {
        $response = $this->doGetApiRequest(self::PARTNER_INFO_ENDPOINT, ['email' => $memberEmail]);
        if (isset($response['sponsor_email'])) {
            return $response['sponsor_email'];
        }
        return null;
    }
}

<?php

namespace App\Gateway;

use GuzzleHttp\Client;

class HttpRequestGateway
{
    /**
     * Get Access Token
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getAccessToken()
    {
        return HttpRequestGateway::httpRequest([
            'route'   => 'token',
            'app'     => 'OAUTH',  # DNS of analytics api;  should be presented in .env file
            'method'  => 'POST',
            'headers' => [
                'Accept' => 'application/json'
            ],
            'data' => [
                'grant_type'    => 'client_credentials',
                'client_id'     => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET')
            ]
        ]);
    }

    /**
     * Check Customer
     *
     * @param $email
     * @param string $language
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function checkCustomer($email, $language = 'ka')
    {
        $accessTokenResponse = static::getAccessToken();

        if ($accessTokenResponse['status_code'] !== 200) {
            return false;
        }

        $accessToken = json_decode($accessTokenResponse['body']);

        $response = HttpRequestGateway::httpRequest([
            'route'   => 'api/user/consent',
            'app'     => 'CONSENT',  # DNS of analytics api;  should be presented in .env file
            'method'  => 'GET',
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken->access_token,
                'Accept'        => 'application/json'
            ],
            'data' => [
                'email'    => $email,
                'language' => $language
            ]
        ]);

        return json_decode($response['body']);
    }

    /**
     * Agree On Terms And Conditions
     *
     * @param $email
     * @param int $agree
     * @return false
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function agree($email, $agree = 1)
    {
        $accessTokenResponse = static::getAccessToken();

        if ($accessTokenResponse['status_code'] !== 200) {
            return false;
        }

        $accessToken = json_decode($accessTokenResponse['body']);

        $response = HttpRequestGateway::httpRequest([
            'route'   => 'api/user/consent',
            'app'     => 'CONSENT',  # DNS of analytics api;  should be presented in .env file
            'method'  => 'POST',
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken->access_token,
                'Accept'        => 'application/json'
            ],
            'data' => [
                'email' => $email,
                'agree' => $agree
            ]
        ]);

        return json_decode($response['body']);
    }

    /**
     * Check Customer Status
     *
     * @param $email
     * @return false|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function checkCustomerStatus($email)
    {
        $accessTokenResponse = static::getAccessToken();

        if ($accessTokenResponse['status_code'] !== 200) {
            return false;
        }

        $accessToken = json_decode($accessTokenResponse['body']);

        $response = HttpRequestGateway::httpRequest([
            'route'   => 'api/user/consent/status',
            'app'     => 'CONSENT',  # DNS of analytics api;  should be presented in .env file
            'method'  => 'GET',
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken->access_token,
                'Accept'        => 'application/json'
            ],
            'data' => [
                'email' => $email
            ]
        ]);

        return json_decode($response['body']);
    }

    /**
     * Get Terms And Conditions
     *
     * @param string $language
     * @return false|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getTermsAndConditions($language = 'ka')
    {
        $accessTokenResponse = static::getAccessToken();

        if ($accessTokenResponse['status_code'] !== 200) {
            return false;
        }

        $accessToken = json_decode($accessTokenResponse['body']);

        $response = HttpRequestGateway::httpRequest([
            'route'   => 'api/terms_and_conditions',
            'app'     => 'CONSENT',  # DNS of analytics api;  should be presented in .env file
            'method'  => 'GET',
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken->access_token,
                'Accept'        => 'application/json'
            ],
            'data' => [
                'language' => $language
            ]
        ]);

        return json_decode($response['body']);
    }

    /**
     * Send Http Request
     *
     * @param array $params
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function httpRequest( array $params )
    {
        if (!isset($params['route'])) return 'Route not found';

        $method  = !isset($params['method'])  ? 'POST'  : $params['method'];
        $data    = !isset($params['data'])    ? []      : $params['data'];
        $headers = !isset($params['headers']) ? []      : $params['headers'];

        $apiEndpoint = !empty(env(strtoupper($params['app']) . '_API_ENDPOINT')) ? env(strtoupper($params['app']) . '_API_ENDPOINT').'/':'';

        $headers['x-api-key'] = env('X_API_KEY');

        $apiVersionTag = !empty(env(strtoupper($params['app']) . '_API_VERSION')) ? env(strtoupper($params['app']) . '_API_VERSION').'/':'';
        $destinationEndpoint =  $apiEndpoint.$apiVersionTag.$params['route'];

        $params = [
            'form_params' => $data,
            'headers'     => $headers
        ];

        if ($method === 'GET') {
            $params = [
                'query'   => $data,
                'headers' => $headers
            ];
        }

        try {

            $client  = new Client();

            if ($method === 'GET') {
                $request = $client->request($method, $destinationEndpoint, $params);
            } else {
                $request = $client->request($method, $destinationEndpoint, [
                    'form_params' => $data,
                    'headers'     => $headers
                ]);
            }

            $result                = [];
            $result['status_code'] = $request->getStatusCode();
            $result['headers']     = $request->getHeader('content-type');
            $result['body']        = $request->getBody();

            return $result;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $result                = [];
            $result['status_code'] = $e->getCode();
            $result['headers']     = $e->getResponse()->getHeader('content-type');
            $result['body']        = $e->getResponse()->getBody(true);

            return $result;
        }

    }
}

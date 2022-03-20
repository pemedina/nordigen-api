<?php

namespace Pemedina\Nordigen;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Nordigen
{
  const SUCCESS = 'success';
  const FAILURE = 'failure';
  private static string $host    = 'https://ob.nordigen.com/api/v2/';
  private static array  $headers = ['accept' => 'application/json', 'Content-Type' => 'application/json'];
  private string        $secret_id;
  private string        $secret_key;
  private ?string       $token   = null;
  private string        $refresh;
  /**
   * @var \GuzzleHttp\Client
   */
  private Client $client;

  public function __construct($secret_id, $secret_key)
  {

    $this->secret_id  = $secret_id;
    $this->secret_key = $secret_key;
    $this->client     = new Client([
      'http_errors'     => false,
      'allow_redirects' => [
        'max'             => 5,
        'strict'          => false,
        'referer'         => false,
        'protocols'       => ['http', 'https'],
        'track_redirects' => false
      ],
      'cookies'         => true,
      'debug'           => false,
      'headers'         => [
        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:59.0) Gecko/20100101 Firefox/59.0',
      ]
    ]);
  }


  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getInstitutions()
  {
    $this->login();
    $this->refreshToken();
    $response = $this->client->request('GET', sprintf('%s%s', self::$host, 'institutions/?country=ES'),
      ['headers' => $this->getAuthHeaders()]);

    return self::parseResponse($response);

  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function login()
  {
    if (is_null($this->token)) {
      $this->loadToken();
    }
  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  private function loadToken()
  {

    $response      = $this->client->request('POST', sprintf('%s%s', self::$host, 'token/new/'),
      ['json' => ['secret_id' => $this->secret_id, 'secret_key' => $this->secret_key]]);
    $response      = json_decode(self::parseResponse($response));
    $this->token   = $response->data->access;
    $this->refresh = $response->data->refresh;

  }

  private static function parseResponse(ResponseInterface $response)
  {
    $body = $response->getBody()->getContents();

    if ( ! self::is_json($body)) {
      return json_encode(['code' => $response->getStatusCode(), 'status' => self::FAILURE, 'data' => $body]);
    }

    return json_encode([
      'code'   => $response->getStatusCode(),
      'status' => (200 == $response->getStatusCode()) ? self::SUCCESS : self::FAILURE,
      'data'   => json_decode($body)
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_BIGINT_AS_STRING);

  }

  /**
   * @param  null  $value
   *
   * @return bool
   */
  private static function is_json($value = null): bool
  {
    if (is_array($value)) {
      return false;
    }
    if (empty($value)) {
      return false;
    }
    @json_decode($value);

    return (json_last_error() === JSON_ERROR_NONE);

  }


  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  private function refreshToken()
  {
    $response    = $this->client->request('POST', sprintf('%s%s', self::$host, 'token/refresh/'),
      ['json' => ['refresh' => $this->refresh]]);
    $response    = json_decode(self::parseResponse($response));
    $this->token = $response->data->access;

  }

  /**
   * @return string[]
   */
  private function getAuthHeaders(): array
  {
    return array_merge(self::$headers, ['Authorization' => "Bearer $this->token"]);
  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function createAgreement($institution_id, $attributes = [])
  {

    $this->login();

    $response = $this->client->request('POST', sprintf('%s%s', self::$host, 'agreements/enduser/'), [
      'headers' => $this->getAuthHeaders(),
      'json'    => array_merge_recursive($attributes, [
        "institution_id"        => $institution_id,
        "max_historical_days"   => "30",
        "access_valid_for_days" => "30",
        "access_scope"          => [
          "balances",
          "details",
          "transactions"
        ],
      ])
    ]);

    return self::parseResponse($response);

  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function createRequisition($attributes = [])
  {

    $this->login();
    $response = $this->client->request('POST', sprintf('%s%s', self::$host, 'requisitions/'), [
      'headers' => $this->getAuthHeaders(),
      'json'    => $attributes
    ]);

    return self::parseResponse($response);
  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getRequisitions($id = null)
  {

    $url = is_null($id) ? self::$host.'requisitions' : self::$host."requisitions/$id";
    $this->login();
    $response = $this->client->request('POST', $url, $this->getDefaultHeaders());

    return self::parseResponse($response);
  }

  /**
   * @return \string[][]
   */
  private function getDefaultHeaders(): array
  {
    return [
      'headers' => $this->getAuthHeaders(),
    ];
  }


  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getTransactions($id)
  {

    $this->login();
    $response = $this->client->request('GET', sprintf('%s%s/%s/transactions', self::$host, "accounts", $id), $this->getDefaultHeaders());

    return self::parseResponse($response);
  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getBalances($id)
  {

    $this->login();
    $response = $this->client->request('GET', sprintf('%s%s/%s/balances', self::$host, "accounts", $id), $this->getDefaultHeaders());

    return self::parseResponse($response);
  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getDetails($id)
  {

    $this->login();
    $response = $this->client->request('GET', sprintf('%s%s/%s/details', self::$host, "accounts", $id), $this->getDefaultHeaders());

    return self::parseResponse($response);
  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getAgreements($id = null)
  {

    $url = is_null($id) ? self::$host.'agreements/enduser' : self::$host."agreements/enduser/$id";
    $this->login();

    $response = $this->client->request('GET', $url, $this->getDefaultHeaders());

    return self::parseResponse($response);

  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function deleteAgreement($id)
  {
    $this->login();
    $response = $this->client->request('DELETE', sprintf('%s%s/%s/', self::$host, "agreements/enduser", $id), $this->getDefaultHeaders());

    return self::parseResponse($response);
  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function deleteRequisition($id)
  {
    $this->login();
    $response = $this->client->request('DELETE', sprintf('%s%s/%s/', self::$host, "requisitions", $id), $this->getDefaultHeaders());

    return self::parseResponse($response);
  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getAccounts($id, $action = null)
  {
    $url = is_null($action) ? self::$host."accounts/$id/" : self::$host."accounts/$id/$action/";
    $this->login();

    $response = $this->client->request('GET', $url, $this->getDefaultHeaders());

    return self::parseResponse($response);
  }
}

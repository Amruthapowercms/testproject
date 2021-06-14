<?php

namespace Drupal\custom_form\Form;

use GuzzleHttp\Client;

/**
 * Zoom api.
 */
class ZoomApi {
  /**
   * Using $httpClient.
   *
   * @var string
   */
  protected $httpClient;

  /**
   * Contain Client $http_client.
   */
  public function __construct(Client $http_client) {
    $this->httpClient = $http_client;

  }

  
  /**
   * For Creating an Issuer.
   *
   * @param string $accessToken
   *   Contains the accesstoke.
   * @return jsonobject
   *   returns the all issuer details in json form
   */
  public function zoomApi($accessToken) {
    $response = $this->httpClient->request(
          'GET', 'https://api.zoom.us/v2/users/amrutha.pv@powercms.in', [

            'headers' => ['Authorization' => 'Bearer ' . $accessToken],

          ]
      )->getBody()->getContents();

    return $response;

  }

  
}

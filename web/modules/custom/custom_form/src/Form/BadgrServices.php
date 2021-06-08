<?php

namespace Drupal\custom_form\Form;

use GuzzleHttp\Client;

/**
 * Badger api.
 */
class BadgrServices {
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
   * Get the Accesstoken and Refreshtoken .
   *
   * @param array $details
   *   Contain the user details.
   *
   * @return array
   *   returns the details in array form
   */
  public function badgrInitiate(array $details) {

    $response = $this->httpClient->request(
          'post', 'https://api.badgr.io/o/token',
          [
            'form_params' => $details,
          ]
      );

    $status_code = $response->getStatusCode();

    if ($status_code == '200') {
      // Get body and get both tokens.
      $body = $response->getBody()->getContents();
    }

    $accesstoken = json_decode($body)->access_token;
    $refreshtoken = json_decode($body)->refresh_token;
    $token = ['accesstoken' => $accesstoken, 'refreshtoken' => $refreshtoken];

    return $token;

  }

  /**
   * Refresh token can be used to automatically renew an access token .
   *
   * @param array $refresh_details
   *   Contains the refreshtoken.
   *
   * @return jsonobject
   *   returns the details in json form
   */
  public function badgrRefreshToken(array $refresh_details) {
    $refresh_token = $this->httpClient->request(
          'post', 'https://api.badgr.io/o/token',
          [
            'form_params' => $refresh_details,

          ]
      )->getBody()->getContents();

    return $refresh_token;
  }

  /**
   * Authenticate requests.
   *
   * @param string $accessToken
   *   Contains the accesstoke.
   *
   * @return jsonobject
   *   returns the details in json form
   */
  public function badgrUserAuthenticate($accessToken) {
    $response = $this->httpClient->request(
          'get', 'https://api.badgr.io/v2/users/self', [
            'headers' => ['Authorization' => 'Bearer ' . $accessToken],
          ]
      );
    $status_code = $response->getStatusCode();
    if ($status_code == '403') {
      $body = badgrRefreshToken($refreshtoken);
      $accessToken = json_decode($body)->access_token;
      // Return $accessToken.
    }
    // Check if token expired.
    $body = $response->getBody()->getContents();
    return $body;

  }

  /**
   * For Creating an Issuer.
   *
   * @param string $accessToken
   *   Contains the accesstoke.
   * @param array $post_details
   *   Contains the issuer details.
   *
   * @return jsonobject
   *   returns the all issuer details in json form
   */
  public function badgrCreateIssuer($accessToken, array $post_details) {
    $response = $this->httpClient->request(
          'post', 'https://api.badgr.io/v2/issuers', [

            'form_params' => $post_details,

            'headers' => ['Authorization' => 'Bearer ' . $accessToken],

          ]
      )->getBody()->getContents();

    return $response;

  }

  /**
   * For Getting all Issuer.
   *
   * @param string $accessToken
   *   Contains the accesstoken.
   *
   * @return jsonobject
   *   returns the all issuer details in json form
   */
  public function badgrGetIssuer($accessToken) {
    $response = $this->httpClient->request(
          'get', 'https://api.badgr.io/v2/issuers', [

            'headers' => ['Authorization' => 'Bearer ' . $accessToken],

          ]
      )->getBody()->getContents();

    return $response;

  }

  /**
   * Update an issuer on the badgr account.
   *
   * @param string $accessToken
   *   Contains the accesstoken.
   * @param array $update_issure
   *   Contains the updated details.
   * @param string $entityId
   *   Conatin the entityid of badger.
   *
   * @return jsonobject
   *   returns the details about updated issuer in json form
   */
  public function badgrUpdateIssuer($accessToken, array $update_issure, string $entityId) {
    $response = $this->httpClient->request(
          'put', 'https://api.badgr.io/v2/issuers/' . $entityId . '', [

            'form_params' => $update_issure,
            'headers' => ['Authorization' => 'Bearer ' . $accessToken],

          ]
      )->getBody()->getContents();

    return $response;

  }

  /**
   * Delete an issuer on the badgr account.
   *
   * @param string $accessToken
   *   Contains the accesstoken.
   * @param string $entityId
   *   Conatin the entityid of badger.
   */
  public function badgrDeleteIssuer($accessToken, string $entityId) {
    $response = $this->httpClient->request(
          'delete', 'https://api.badgr.io/v2/issuers/' . $entityId,
          [

            'headers' => ['Authorization' => 'Bearer ' . $accessToken],

          ],
      )->getBody()->getContents();

    return $response;

  }

  /**
   * To create an badges based to the issuer on the badgr account.
   *
   * @param string $accessToken
   *   Contains the accesstoken.
   * @param array $create_badge
   *   Contains the badger details.
   * @param string $entityId
   *   Conatin the entityid of badger.
   *
   * @return jsonobject
   *   returns the details about created badges in json form
   */
  public function badgrCreateIssuerBadges($accessToken, array $create_badge, string $entityId) {
    $response = $this->httpClient->request(
          'post',
          'https://api.badgr.io/v2/issuers/' . $entityId . '/badgeclasses',
          [
            'form_params' => $create_badge,
            'headers' => ['Authorization' => 'Bearer ' . $accessToken],
          ]
      )->getBody()->getContents();

    return $response;

  }

  /**
   * To get list of badges on the badgr account.
   *
   * @param string $accessToken
   *   Contains the accesstoken.
   *
   * @return jsonobject
   *   returns the details about all created badges in json form
   */
  public function badgrListAllBadges($accessToken) {
    $response = $this->httpClient->request(
      'get',
      'https://api.badgr.io/v2/badgeclasses', [

        'headers' => ['Authorization' => 'Bearer ' . $accessToken],

      ]
    )->getBody()->getContents();

    return $response;

  }

  /**
   * To update an badges.
   *
   * @param string $accessToken
   *   Contains the accesstoken.
   * @param array $update_badge
   *   Body contains the updated badge details.
   *
   * @return jsonobject
   *   returns the updated details in json form
   */
  public function badgrUpdateBadges($accessToken, array $update_badge) {
    $response = $this->httpClient->request(
          'put',
          'https://api.badgr.io/v2/badgeclasses/' . $entityId, [
            'form_params' => $update_badge,
            'headers' => ['Authorization' => 'Bearer ' . $accessToken],

          ]
      )->getBody()->getContents();

    return $response;

  }

  /**
   * To deleting badgr account.
   *
   * @param string $accessToken
   *   Contains the accesstoken.
   */
  public function badgrDeleteBadges($accessToken) {
    $response = $this->httpClient->request(
          'delete', 'https://api.badgr.io/v2/badgeclasses/gA2kCP1gQxCXQj1BsoC1fg', [

            'headers' => ['Authorization' => 'Bearer ' . $accessToken],

          ]
      )->getBody()->getContents();

    return $response;
  }

  /**
   * Award badges on the badgr account    .
   *
   * @param string $accessToken
   *   Contains the accesstoken.
   * @param string $award_details
   *   Body which include recipient details.
   * @param string $entityId
   *   Conatin the entityid of badger.
   *
   * @return jsonobject
   *   returns the award details in json form
   */
  public function badgrAwardBadges($accessToken, $award_details, $entityId) {

    $response = $this->httpClient->request(
        'post',
        'https://api.badgr.io/v2/badgeclasses/' . $entityId . '/assertions', [
          'form_params' => $award_details,

          'headers' => ['Authorization' => 'Bearer ' . $accessToken],

        ]
    )->getBody()->getContents();

    return $response;
  }

}

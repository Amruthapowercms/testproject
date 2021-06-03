<?php

namespace Drupal\custom_form\Form;

use GuzzleHttp\Client;

/**
 *
 */
class BadgrServices {
  protected $httpClient;

  /**
   *
   */
  public function __construct(Client $http_client) {
    $this->httpClient = $http_client;

  }

  /**
   * Get the Accesstoken and Refreshtoken .
   *
   * @param $details
   *
   * @return array
   */
  public function badgr_initiate(array $details) {

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
   * @param $refreshtoken
   *
   * @return json object
   */
  public function badgr_refresh_token(array $refresh_details) {
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
   * @param $accessToken
   *
   * @return json object
   */
  public function badgr_user_authenticate($accessToken) {
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
   * @param $accessToken
   * @param $post_details
   *
   * @return json object
   */
  public function badgr_create_issuer($accessToken, array $post_details) {
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
   * @param $accessToken
   *
   * @return json object
   */
  public function badgr_get_issuer($accessToken) {
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
   * @param $accessToken
   * @param $update_issure
   * @param $entityId
   *
   * @return json object
   */
  public function badgr_update_issuer($accessToken, array $update_issure, string $entityId) {
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
   * @param $accessToken
   *
   * @return json object
   */
  public function badgr_delete_issuer($accessToken, string $entityId) {
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
   * @param $accessToken
   *
   * @return json object
   */
  public function badgr_create_issuer_badges($accessToken, array $create_badge, string $entityId) {
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
   * @param $accessToken
   *
   * @return json object
   */
  public function badgr_list_all_badges($accessToken) {
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
   * @param $accessToken
   *
   * @return json is_object(var)
   */
  public function badgr_update_badges($accessToken, array $update_badge) {
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
   * @param $accessToken
   *
   * @return json object
   */
  public function badgr_delete_badges($accessToken) {
    $response = $this->httpClient->request(
          'delete', 'https://api.badgr.io/v2/badgeclasses/gA2kCP1gQxCXQj1BsoC1fg', [

            'headers' => ['Authorization' => 'Bearer ' . $accessToken],

          ]
      )->getBody()->getContents();

    return $response;
  }

  /**
   * Award badges on the badgr account     *
   *
   * @param $accessToken
   * @param $award_details
   * @param $entityId
   *
   * @return 
   * json object
   */
  public function badgr_award_badges($accessToken, $award_details, $entityId) {

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

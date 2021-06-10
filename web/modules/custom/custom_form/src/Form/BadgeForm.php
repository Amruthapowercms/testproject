<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 */
class BadgeForm extends FormBase {

  /**
   *
   */
  public function getFormId() {
    return 'badge_form2';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['action'] = [
      '#type' => 'select',
      '#title' => t('Choose an option'),
      '#default_value' => ['value_1'],
      '#options' => [
        'update' => t('Update'),
        'create' => t('Create'),
        'get' => t('Get'),
      ],
      '#required' => TRUE,

    ];
    $form['issure_name'] = [
      '#type' => 'textfield',
      '#title' => t('Issure Name:'),
      '#states' => [
        'optional' => [
          'select[name="action"]' => ['value' => 'get'],
        ],

        'invisible' => [
          'select[name="action"]' => ['value' => 'get'],
        ],
      ],
    ];

    $form['issure_mail'] = [
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#states' => [
        'optional' => [
          // 'select[name="action"]' => ['value' => 'update'],
          'select[name="action"]' => ['value' => 'get'],
        ],
        'invisible' => [
          'select[name="action"]' => ['value' => 'get'],
        ],
      ],
    ];
    $form['issure_description'] = [
      '#type' => 'textfield',
      '#title' => t('Description'),
      '#states' => [
        'optional' => [
          // 'select[name="action"]' => ['value' => 'update'],
          ['select[name="action"]' => ['value' => 'get']],
          ['select[name="action"]' => ['value' => 'update']],
          ['select[name="action"]' => ['value' => 'create']],
        ],
        'invisible' => [
          'select[name="action"]' => ['value' => 'get'],
        ],
      ],
    ];
    $form['issure_url'] = [
      '#type' => 'url',
      '#title' => t('Issure Url'),
      '#states' => [
        'optional' => [
         // 'select[name="action"]' => ['value' => 'update'],
          'select[name="action"]' => ['value' => 'get'],
        ],
        'invisible' => [
          'select[name="action"]' => ['value' => 'get'],
        ],
      ],
    ];
    $form['entityId'] = [
      '#type' => 'textfield',
      '#title' => t('EntityId'),
      '#states' => [
        'optional' => [
          ['select[name="action"]' => ['value' => 'get']],
          ['select[name="action"]' => ['value' => 'create']],
        ],
        'invisible' => [
          ['select[name="action"]' => ['value' => 'get']],
          ['select[name="action"]' => ['value' => 'create']],
        ],
      ],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   *
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // $issureemail = $form_state->getvalue('issure_mail');
    // $config = $this->config('custom_form.settings');
    // $username = $config->get('username');
    // if ($issureemail !== $username) {
    //   $form_state->setErrorByName('issure_mail', 'Email id is not matching');
    // }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Foreach ($form_state->getValues() as $key => $value) {
    //   drupal_set_message($key . ': ' . $value);
    // }.
    $issurename = $form_state->getvalue('issure_name');
    $issureemail = $form_state->getvalue('issure_mail');
    $issuredescription = $form_state->getvalue('issure_description');
    $issureurl = $form_state->getvalue('issure_url');
    $badgeentityId = $form_state->getvalue('entityId');
    $action = $form_state->getvalue('action');
    // Echo "$test";.
    $service = \Drupal::service('custom_form_client');
    $config = $this->config('custom_form.settings');
    $username = $config->get('username');
    $password = $config->get('password');

    // $user_details = ['username' => 'amrutha.pv@powercms.in', 'password' => 'Amrupv@96'];
    $user_details = ['username' => $username, 'password' => $password];

    // Fetch auth tokens.
    $tokens = $service->badgrInitiate($user_details);
    $refreshToken = $tokens['refreshtoken'];
    $accessToken = $tokens['accesstoken'];
    // if($issureemail!=$username){
    //   $form_state->setErrorByName('issure_mail', 'Email id is not matching');
    // }.
    $issuer_details = ['name' => $issurename, 'email' => $issureemail, 'description' => $issuredescription, 'url' => $issureurl];

    if ($action == 'update') {
      $service->badgrUpdateIssuer($accessToken, $issuer_details, $badgeentityId);
    }
    elseif ($action == 'create') {
      $service->badgrCreateIssuer($accessToken, $issuer_details);
    }
    elseif ($action == 'get') {
      $response = $service->badgrGetIssuer($accessToken);

      drupal_set_message(json_decode($response)->result[0]->entityId);
    }

    drupal_set_message("sucess");

  }

}

<?php

namespace Drupal\custom_form\Form;

use Drupal\file\Entity\File;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 */
class CreateBadge extends FormBase {

  /**
   *
   */
  public function getFormId() {
    return 'create_badge';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['badge_name'] = [
      '#type' => 'textfield',
      '#title' => t('Name:'),
      '#required' => TRUE,
    ];
    $form['badge_image'] = [
      '#type' => 'managed_file',
      '#title' => t('Image'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png'],
       // 'file_validate_size' => array(25600000),
      ],

      '#upload_location' => 'public://badge_image/',
      '#enctype' => 'multipart/form-data',
      '#required' => TRUE,
    ];

    $form['badge_description'] = [
      '#type' => 'textfield',
      '#title' => t('Description'),
      '#required' => TRUE,
    ];
    $form['badge_criteriaUrl'] = [
      '#type' => 'url',
      '#title' => t('CriteriaUrl'),
      '#required' => TRUE,
    ];
    $form['entityId'] = [
      '#type' => 'textfield',
      '#title' => t('EntityId'),
     // '#required' => TRUE,
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
    //    $config = $this->config('custom_form.settings');
    //    $username = $config->get('username');
    //     if($issureemail !== $username){
    //       $form_state->setErrorByName('issure_mail', 'Email id is not matching');
    //     }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Foreach ($form_state->getValues() as $key => $value) {
    //   drupal_set_message($key . ': ' . $value);
    // }.
    $badgename = $form_state->getvalue('badge_name');

    $badgeimage = $form_state->getvalue('badge_image');
    foreach ($badgeimage as $value) {

    }
    // The file ID.
    $fid = $value;

    $file = File::load($fid);
    $path = $file->getFileUri();

    $badgedescription = $form_state->getvalue('badge_description');
    $badgeurl = $form_state->getvalue('badge_criteriaUrl');
    $badgeentityId = $form_state->getvalue('entityId');

    $img_file = file_get_contents($path);
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $base64img = base64_encode($img_file);
    $base64img = "data:image/{$ext};base64,{$base64img}";

    $service = \Drupal::service('custom_form_client');
    $config = $this->config('custom_form.settings');
    $username = $config->get('username');
    $password = $config->get('password');

    // $user_details = ['username' => 'amrutha.pv@powercms.in', 'password' => 'Amrupv@96'];
    $user_details = ['username' => $username, 'password' => $password];

    // Fetch auth tokens.
    $tokens = $service->badgrInitiate($user_details);

    $accessToken = $tokens['accesstoken'];
    $create_badge = ['image' => $base64img, 'description' => $badgedescription, 'criteriaUrl' => $badgeurl, 'name' => $badgename];
    $service->badgrCreateIssuerBadges($accessToken, $create_badge, $badgeentityId);
    drupal_set_message("success");
  }

}

<?php

/**
*@file
*contains\Drupal\custom_form\Form;
**/

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
//use custom_form\Form\BadgrServices;


class BadgeForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  
 //public $badgrdetails;
  //protected $catapultservice;

  /**
   * 
   * @param ContainerInterface $container
   * 
   * @param CatapultPIPRequest $pipUser
   */
  // public function __construct(BadgrServices $badgrdetails)
  // {
  //   $this->badgrdetails = $badgrdetails;
   
  // }

    /**
   * setup container object.
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
    $my_service = $container->get('custom_form_client')
  );
    // $activeRole = $container->get('catapult.pip_user');
    // return new static(
    //   $activeRole,
    //   $container->get('catapult.department_list')
    // );
  }

  public function getFormId() {
    return 'badge_form2';
  }

/**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['issure_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Issure Name:'),
      '#required' => true,
    );
    $form['issure_mail'] = array(
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => true,
    );
    $form['issure_description'] = array(
    	'#type' => 'textfield',
    	'#title' => t('Description'),
    	'#required' => true,
	);
	$form['issure_url'] = array(
		'#type' => 'url',
		'#title' => t('Issure Url'),
		'#required' => true,
	);

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }

/**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
   // drupal_set_message($this->t('@can_name ,Your application is being submitted!', array('@can_name' => $form_state->getValue('candidate_name'))));
    // foreach ($form_state->getValues() as $key => $value) {
    //   drupal_set_message($key . ': ' . $value);
    // }
    $issurename = $form_state->getvalue('issure_name');
    $issureemail = $form_state->getvalue('issure_mail');
    $issuredescription = $form_state->getvalue('issure_description');
    $issureurl = $form_state->getvalue('issure_url');
    $service = \Drupal::service('custom_form_client');
    //$service = $this->badgrdetails;
    $user_details = array('username' => 'amrutha.pv@powercms.in', 'password'=> 'Amrupv@96');

    //Fetch auth tokens
    $tokens = $service->badgr_initiate($user_details);
    $refreshToken = $tokens['refreshtoken'];
    $accessToken = $tokens['accesstoken'];
    $issuer_details = array('name'=> $issurename, 'email'=> $issureemail,'description'=> $issuredescription, 'url'=>$issureurl);
    $service->badgr_create_issuer($accessToken, $issuer_details);
    drupal_set_message("success");
   }

}
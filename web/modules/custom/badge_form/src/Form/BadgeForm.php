<?php

/**
*@file
*contains\Drupal\badge_form\Form;
**/

namespace Drupal\badge_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;


class BadgeForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'badge_form';
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
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
    }
   }

}
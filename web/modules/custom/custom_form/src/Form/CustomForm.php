<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides route responses for the Example module.
 */
class CustomForm extends FormBase {

  /**
   * @return string
   */
  public function getFormId() {
    return 'custom_form_api';
  }
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['fullname'] = array(
      '#type' => 'textfield',
      '#title' => t('Full Name:'),
      '#required' => TRUE,
    );
    $form['gmail'] = array(
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
    );
    $form['phone_number'] = array (
      '#type' => 'tel',
      '#title' => t('Mobile Number'),
      '#required' => TRUE,
    );
    
    // $form['candidate_address'] = array(
    //   '#type' => 'textfield',
    //   '#title' => t('Candidate Address:'),
    //   '#required' => TRUE,
    // );
    
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',

    );

    return $form;
  }
  

public function validateForm(array &$form, FormStateInterface $form_state) {
//     $name = $form_state->getvalue('fullname');
//     $mail = $form_state->getvalue('gmail');
//     $db = \Drupal::database();
//     $resultname = $db->select('customform','cf')
//     ->fields('cf',['full_name'])
//     ->condition('cf.full_name', $name, '=')
//     ->execute()
//     ->fetchAll();

//     foreach ($resultname as $value) {
//       $ename = $value->full_name;
//     }

// if($name=$ename){
// 	$form_state->setErrorByName('fullname', 'Name already exists!');
// }


//  $resultname = $db->select('customform','cf')
//     ->fields('cf',['gmail'])
//     ->condition('cf.gmail', $mail, '=')
//     ->execute()
//     ->fetchAll();

//     foreach ($resultname as $value) {
//       $email = $value->gmail;
//     }

// if($mail=$email){
// 	$form_state->setErrorByName('gmail', 'Email already exists!');
// }


    $name = $form_state->getvalue('fullname');
    $mail = $form_state->getvalue('gmail');
    $db = \Drupal::database();
    $resultname = $db->select('customform','cf')
    ->fields('cf',['full_name'])
    ->fields('cf',['gmail'])
    ->condition(
        db_or()
    ->condition('cf.full_name', $name, '=')
    ->condition('cf.gmail', $mail, '=')
)
    ->execute()
    ->fetchAll();
    foreach ($resultname as $value) {
      $ename = $value->full_name;
      $email = $value->gmail;
      ddl($email);
    }
    
if($mail==$email && $name==$ename){
  //$form_state->setErrorByName('gmail,fullname', 'Email and name already exists!');
   $form_state->setErrorByName('fullname', 'Name already exists!');
   $form_state->setErrorByName('gmail', 'Email already exists!');
}
elseif($name==$ename){
	$form_state->setErrorByName('fullname', 'Name already exists!');
}

elseif($mail==$email){
	$form_state->setErrorByName('gmail', 'Email already exists!');
}



   
   
    // if(($name=$ename)||($email=$mail)){
    // 	if(($name=$ename)||($mail!=$email)){
    // 	  $form_state->setErrorByName('fullname', 'Name already exists!');
    // 	}
    //   elseif(($name!=$ename) && ($mail=$email)){
    //     $form_state->setErrorByName('gmail', 'Email already exists!');
    // }
    //   //}
    //    else {
    //     $form_state->setErrorByName('fullname', 'Name already exists!');
    //    $form_state->setErrorByName('gmail', 'Email already exists!');
    //   }
    // }


// if ($name==$ename) {
//    $form_state->setErrorByName('fullname', 'Name already exists!');
// } elseif ($email==$mail) {
//   $form_state->setErrorByName('gmail', 'Email already exists!');
// } else {
//   $form_state->setErrorByName('fullname', 'Name already exists!');
//   $form_state->setErrorByName('gmail', 'Email already exists!');
// }



 }



    

public function submitForm(array &$form, FormStateInterface $form_state) {
  	$db = \Drupal::database();
    $query = $db->insert('customform');
    $query->fields([
      'full_name' => $form_state->getValue('fullname'),
      'gmail' => $form_state->getValue('gmail'),
      'phone_number' => $form_state->getValue('phone_number'),
  
      ])
    ->execute();

    } 


}



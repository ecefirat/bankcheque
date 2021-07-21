<?php

namespace Drupal\cheque_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\DrupalDateTime;

class bankchequeform extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'cheque_form';
    }

    /**
     * {@inheritdoc}
     */

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['first_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First Name'),
            '#maxlength' => 20,
            '#required' => TRUE,
        ];

        $form['last_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
            '#maxlength' => 20,
            '#required' => TRUE,
        ];

        $form['payee_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Payee Name'),
            '#maxlength' => 40,
            '#required' => TRUE,
        ];

        $form['sum'] = [
            '#type' => 'number',
            '#title' => $this->t('The Sum'),
            '#required' => TRUE,
       ];

       $form['date'] = [
        '#type' => 'hidden',
        '#title' => t('Date'),
        '#date_format' =>'d-m-Y',
        '#default_value' =>date('d-m-Y'),
        '#disabled' => TRUE,
       ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('submit')
        ];

        

        return $form;
    }

    /**
 * {@inheritdoc}
 */
public function validateForm(array &$form, FormStateInterface $form_state) {
    if(is_numeric($form_state->getValue('first_name'))) {
        $form_state->setErrorByName('first_name', $this->t.('Error, The first name must be a string.'));
    }




    // if(strlen($form_state->getValue('first_name')) < 1) {
    //     $form_state->setErrorByName('first_name', $this->t('The fname is too short.'));
    // }
    // if(strlen($form_state->getValue('last_name')) < 1) {
    //     $form_state->setErrorByName('last_name', $this->t('The lname is too short.'));
    // }
    // if(strlen($form_state->getValue('payee_name')) < 1) {
    //     $form_state->setErrorByName('payee_name', $this->t('The pname is too short.'));
    // }
    if (strlen($form_state->getValue('sum')) < 3) {
        $form_state->setErrorByName('sum', $this->t('The sum is must be more than $99.'));
      }
  
  }
  

    /**
     * {@inheritdoc}
     */

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $this->messenger()->addStatus($this->t('Pay @payee', ['@payee' => $form_state->getValue('payee_name')]));
        $this->messenger()->addStatus($this->t('The sum of @sum', ['@sum' => $form_state->getValue('sum')]));
  
        drupal_set_message($this->t('From @name @last', array('@name' => $form_state->getValue('first_name'), '@last' => $form_state->getValue('last_name'))));

            drupal_set_message($form_state->getValue('date'));
}
}
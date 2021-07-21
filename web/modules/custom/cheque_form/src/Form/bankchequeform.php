<?php

namespace Drupal\cheque_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

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
        ];

        $form['last_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
        ];

        $form['payee_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Payee Name'),
        ];

        $form['sum'] = [
            '#type' => 'number',
            '#title' => $this->t('The Sum'),
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
    if(strlen($form_state->getValue('first_name')) < 1) {
        $form_state->setErrorByName('first_name', $this->t('The fname is too short.'));
    }
    if(strlen($form_state->getValue('last_name')) < 1) {
        $form_state->setErrorByName('last_name', $this->t('The lname is too short.'));
    }
    if(strlen($form_state->getValue('payee_name')) < 1) {
        $form_state->setErrorByName('payee_name', $this->t('The pname is too short.'));
    }
    if (strlen($form_state->getValue('sum')) < 1) {
        $form_state->setErrorByName('sum', $this->t('The sum is too short.'));
      }
  
  }

    /**
     * {@inheritdoc}
     */

    public function submitForm(array &$form, FormStateInterface $form_state) {
        drupal_set_message($form_state->getValue('payee_name'));
        drupal_set_message($form_state->getValue('first_name'));
        drupal_set_message($form_state->getValue('last_name'));
        $this->messenger()->addStatus($this->t('Your phone number is @number', ['@number' => $form_state->getValue('sum')]));
    }
}
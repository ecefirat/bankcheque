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

// Not very happy with the coding and way of validation. 
// I am sure there must be a better way without the repetitions and to check the character input.
// For some reason !is_string didn't work.
// preg_match is kind of a hacky way but I wanted to put some validation other than cheking the numeric values.

    if(is_numeric($form_state->getValue('first_name'))) {
        $form_state->setErrorByName('first_name', $this->t.('Error, The first name must be a string.'));
    }
     if(is_numeric($form_state->getValue('last_name'))) {
        $form_state->setErrorByName('last_name', $this->t.('Error, The last name must be a string.'));
    } 
     if(is_numeric($form_state->getValue('payee_name'))) {
        $form_state->setErrorByName('payee_name', $this->t.('Error, The payee name must be a string.'));
    }
    if (($form_state->getValue('sum')) < 9) {
        $form_state->setErrorByName('sum', $this->t('The sum must be more than $9.'));
      }

      $value = [$form_state->getValue('first_name'), $form_state->getValue('last_name'), $form_state->getValue('payee_name')];
      $pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
      if(preg_match($pattern, $value[0])) {
          $form_state->setErrorByName('first_name', $this->t('Names consist of letters only. @name', array('@name' => $value)));
      } 
      if(preg_match($pattern, $value[1])) {
        $form_state->setErrorByName('last_name', $this->t('Names consist of letters only. @name', array('@name' => $value)));
    } 
    if(preg_match($pattern, $value[2])) {
        $form_state->setErrorByName('payee_name', $this->t('Names consist of letters only. @name', array('@name' => $value)));
    } 
  
  }
  

    /**
     * {@inheritdoc}
     */

    public function submitForm(array &$form, FormStateInterface $form_state) {

        $this->messenger()->addStatus($this->t('@payee', ['@payee' => $form_state->getValue('payee_name')]));
        $this->messenger()->addStatus($this->t('@sum', ['@sum' => ($form_state->getValue('sum'))]));
        drupal_set_message($this->t('@name @last', array('@name' => $form_state->getValue('first_name'), '@last' => $form_state->getValue('last_name'))));
        drupal_set_message($form_state->getValue('date'));


    // Couldn't install the intl hence the number formatter. Hoping to find a way to convert numbers to words through this.

        // $value = $form_state->getValue('sum');
        // $thesum = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        // drupal_set_message($form_state->getValue($thesum->format($value)));

}
}
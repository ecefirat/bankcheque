<?php

// First of all, I would like to thank you for this challenge and the opportuniy. 
// Thank you because I was so excited these last two days and learned a lot.

// And challenge because almost everything I did in this project, I learned them in the last two days. 
// My previous experience with Drupal was only through the UI and without deployment, so everything in this project was new to me.
// For that reason, I am not very satisfied with the outcome and I wanted to let you know about it.


// First, I started playing to undertsand which files interact with the page and do what and was playing with the Status/Error messages after building the form. 
// That's why I kept on going from there and in the end,
// instead of redirecting the user to a new page after form submission, I kind of ended up with changing the styling of the status message to display the bank cheque. 

// Finally, I really couldn't find enough information on the internet for deployment, hence the project is unfortunately undeployed. 
// The good thing is most of the functionality works :)

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
// preg_match is kind of a hacky way but I wanted to put some validation other than just checking is_numeric.

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


    // Couldn't install the intl hence the number formatter. Was hoping to find a way to convert numbers to words through this to start with.

        // $value = $form_state->getValue('sum');
        // $thesum = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        // drupal_set_message($form_state->getValue($thesum->format($value)));

}
}
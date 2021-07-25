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
            // '#required' => TRUE,
        ];

        $form['last_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
            '#maxlength' => 20,
            // '#required' => TRUE,
        ];

        $form['payee_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Payee Name'),
            '#maxlength' => 40,
            // '#required' => TRUE,
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
     if(is_numeric($form_state->getValue('last_name'))) {
        $form_state->setErrorByName('last_name', $this->t.('Error, The last name must be a string.'));
    } 
     if(is_numeric($form_state->getValue('payee_name'))) {
        $form_state->setErrorByName('payee_name', $this->t.('Error, The payee name must be a string.'));
    }
    if (($form_state->getValue('sum')) < 10) {
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
        $this->messenger()->addStatus($this->t('@sum', ['@sum' => number_format($form_state->getValue('sum'))]));

        $ones = array(
            0 =>"ZERO",
            1 => "ONE",
            2 => "TWO",
            3 => "THREE",
            4 => "FOUR",
            5 => "FIVE",
            6 => "SIX",
            7 => "SEVEN",
            8 => "EIGHT",
            9 => "NINE",
            10 => "TEN",
            11 => "ELEVEN",
            12 => "TWELVE",
            13 => "THIRTEEN",
            14 => "FOURTEEN",
            15 => "FIFTEEN",
            16 => "SIXTEEN",
            17 => "SEVENTEEN",
            18 => "EIGHTEEN",
            19 => "NINETEEN",
            );
            $tens = array( 
            0 => "ZERO",
            1 => "TEN",
            2 => "TWENTY",
            3 => "THIRTY", 
            4 => "FORTY", 
            5 => "FIFTY", 
            6 => "SIXTY", 
            7 => "SEVENTY", 
            8 => "EIGHTY", 
            9 => "NINETY" 
            ); 
            $hundreds = array( 
            "HUNDRED", 
            "THOUSAND", 
            "MILLION", 
            "BILLION", 
            "TRILLION", 
            "QUARDRILLION" 
            );

        $sum = $form_state->getValue('sum');
        $num = number_format($sum);
        $num_arr = array_reverse(explode(",", $num));
        krsort($num_arr, 1);
        $rettxt = "";
        foreach($num_arr as $key => $i) {
            while(substr($i, 0, 1) == "0")
                $i = substr($i, 1, 5);
                if($i < 20) {
                    $rettxt .= " ".$ones[$i];
                } 
                elseif($i < 100) {
                    if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
                    if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
                } 
                else {
                    if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
                    if(substr($i,1,1)!="0")$rettxt .= " and ".$tens[substr($i,1,1)]; 
                    if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
                } if($key > 0){ 
                    $rettxt .= " ".$hundreds[$key]." ";
                }
                $text = strtolower($rettxt);
                $text = ucwords($text);
        }
            
        

        $this->messenger()->addStatus($this->t('@sum', ['@sum' => ($text)]));
        
        drupal_set_message($this->t('@name @last', array('@name' => $form_state->getValue('first_name'), '@last' => $form_state->getValue('last_name'))));
        drupal_set_message($form_state->getValue('date'));
}
}
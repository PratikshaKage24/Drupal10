<?php

namespace Drupal\registration_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\registration_form\Service\RegistrationService;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

class RegistrationForm extends FormBase {

  protected $registrationService;

  /**
   * Logger service.
   * 
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $logger;

  public function __construct(RegistrationService $registration_service, LoggerChannelFactoryInterface $logger_factory) {
    $this->registrationService = $registration_service;
    $this->logger = $logger_factory->get('registration_form');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('registration_form.registration_service'),
      $container->get('logger.factory') // Getting logger service
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Step 1
    if ($form_state->get('step') === NULL) {
      $form_state->set('step', 1);
    }
    
    $step = $form_state->get('step');
  
    if ($step === 1) {
      $form['first_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('First Name'),
        '#required' => TRUE,
        '#default_value' => $form_state->getValue('first_name', ''),
      ];
  
      $form['last_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Last Name'),
        '#required' => TRUE,
        '#default_value' => $form_state->getValue('last_name', ''),
      ];
  
      $form['actions']['next'] = [
        '#type' => 'submit',
        '#value' => $this->t('Next'),
        '#button_type' => 'primary',
      ];
    }
    //Step 2
    elseif ($step === 2) {
      $form['mobile'] = [
        '#type' => 'tel',
        '#title' => $this->t('Mobile'),
        '#required' => TRUE,
        '#default_value' => $form_state->getValue('mobile', ''),
      ];
  
      $form['email'] = [
        '#type' => 'email',
        '#title' => $this->t('Email'),
        '#required' => TRUE,
        '#default_value' => $form_state->getValue('email', ''),
      ];
  
      $form['actions']['previous'] = [
        '#type' => 'submit',
        '#value' => $this->t('Previous'),
        '#submit' => ['::previousStep'],
      ];
  
      $form['actions']['next'] = [
        '#type' => 'submit',
        '#value' => $this->t('Next'),
      ];
    }
    elseif ($step === 3) {
      $form['bio'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Bio'),
        '#default_value' => $form_state->getValue('bio', ''),
      ];
  
      $form['actions']['previous'] = [
        '#type' => 'submit',
        '#value' => $this->t('Previous'),
        '#submit' => ['::previousStep'],
      ];
  
      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ];
    }
  
    return $form;
  }
  
  public function previousStep(array &$form, FormStateInterface $form_state) {
    $step = $form_state->get('step');
    $form_state->set('step', $step - 1);
    $form_state->setRebuild(TRUE);
  }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $step = $form_state->get('step');
    $data = $form_state->get('stored_data') ?? [];
  
    if ($step === 1) {
      $data['first_name'] = $form_state->getValue('first_name');
      $data['last_name'] = $form_state->getValue('last_name');
    }
    elseif ($step === 2) {
    
      $data['mobile'] = $form_state->getValue('mobile');
      $data['email'] = $form_state->getValue('email');
    }
    elseif ($step === 3) {

      $data['bio'] = $form_state->getValue('bio');
      
      \Drupal::messenger()->addMessage($this->t('Registration data: ') . print_r($data, TRUE));
      
      $this->registrationService->save($data);
  
      \Drupal::messenger()->addMessage($this->t('Thank you for your registration.'));
    }

    $form_state->set('stored_data', $data);
    if ($step < 3) {
      $form_state->set('step', $step + 1);
      $form_state->setRebuild(TRUE);
    }
  }
}
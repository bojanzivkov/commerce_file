<?php

namespace Drupal\commerce_file\Plugin\Commerce\LicenseType;

use Drupal\Core\Form\FormStateInterface;
use Drupal\entity\BundleFieldDefinition;
use Drupal\commerce_license\Entity\LicenseInterface;
use Drupal\commerce_license\Plugin\Commerce\LicenseType\LicenseTypeBase;


/**
 * Provides a license type which grants access to an file.
 *
 * @CommerceLicenseType(
 *   id = "file",
 *   label = @Translation("File"),
 * )
 */
class File extends LicenseTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildLabel(LicenseInterface $license) {
    return 'Premium file license';
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'license_image' => '',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function grantLicense(LicenseInterface $license) {
    $fid = $license->get('license_image')->target_id;
    $file = \Drupal\file\Entity\File::load($fid);
    $downloadlink = file_create_url($file->getFileUri());

    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = "commerce_file";
    $key = 'file_download_link';
    $to = \Drupal::currentUser()->getEmail();
    $params['message'] = $downloadlink;
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    if ($result['result'] !== true) {
      drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
    }
    else {
      drupal_set_message(t('File url has been sent to your email.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function revokeLicense(LicenseInterface $license) {
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
   
    $form['license_image'] = [
            '#type' => 'managed_file',
            '#title' => t('Choose Image'),
            '#upload_location' => 'private://',
            '#default_value' => $this->configuration['license_image'],
            '#description' => t('Upload image'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValue($form['#parents']);

    $this->configuration['license_image'] = $values['license_image'];
 
     /* Load the object of the file by it's fid */
     $file = \Drupal\file\Entity\File::load( $this->configuration['license_image'][0] );
 
     /* Set the status flag permanent of the file object */
     $file->setPermanent();
 
     /* Save the file in database */
     $file->save();
  }

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    $fields['license_image'] = BundleFieldDefinition::create('image')
      ->setLabel(t('Image'))
      ->setDescription(t('The Image this product grants access to.'))
      ->setCardinality(1)
      ->setRequired(TRUE)
      ->setSettings([
          "uri_scheme" => "private",
          'target_type' => 'file',
        ]);
    return $fields;
  }

}

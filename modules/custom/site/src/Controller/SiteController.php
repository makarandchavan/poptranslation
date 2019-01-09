<?php

namespace Drupal\site\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;

/**
 * Controller routines for site example routes.
  */
class SiteController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'site';
  }
  
  /**
   * Quote form handle.
   */
  public function handleQuote() {
    // Insert a record.
    // Create file object from remote URL.
    global $base_url;
    $data = file_get_contents($base_url . '/sites/default/files/' . $_POST['uploadedfile']);
    $file = file_save_data($data, 'public://' . $_POST['uploadedfile'], FILE_EXISTS_REPLACE);

    // Create node object with attached file.
    $node = Node::create([
      'type'        => 'quote_enquiry',
      'title'       => 'Quote request received',
      'field_display_title' => $_POST['email'],
      'field_entreprise' => $_POST['entreprise'],
      'body' => $_POST['information'],
      'field_attached_files' => ['target_id' => $file->id()],
    ]);
    $node->save();

    // Send mails.
    $mailManager = \Drupal::service('plugin.manager.mail');
    // send mail to user.
    $module = 'site';
    $key = 'quote_mail';
    $to = $_POST['email'];
    $params['subject'] = t('Thank you');
    $params['message'] = t('Dear user,<br><br><br>Thank you for the mail. We have received your request. Will contact you shortly.<br><br><br>Regards,<br>Poptranslation.');
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

    // send mail to admin.
    $module = 'site';
    $key = 'quote_mail_admin';
    $to = 'isarkar77@gmail.com';
    $params['subject'] = t('New Quote received');
    $params['message'] = t('Dear admin,<br><br><br>Please find below details<br><br> Email: @usrmail<br>Entreprise: @usrent<br>Information: @usrinfo<br><br><br>Regards,<br>Poptranslation.', array('@usrmail' => $_POST['email'], '@usrent' => $_POST['entreprise'], '@usrinfo' => $_POST['information']));
    $attachment = array(
      'filepath' => $filepath, // or $uri
    );
    $params['attachment'] = $attachment;
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    if ($result['result'] !== true) {
      drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
      return [
        '#markup' => '',
      ];
    }
    else {
      return [
        '#markup' => $this->t('Thank You. Your message has been sent successfully.'),
      ];
    }
  }

  /**
   * Freelance form handle.
   */
  public function handleFreelance() {
    echo "<pre>";print_r($_POST);exit;
    // Insert a record.
    // Create file object from remote URL.
    global $base_url;
    $data = file_get_contents($base_url . '/sites/default/files/' . $_POST['uploadedfile']);
    $file = file_save_data($data, 'public://' . $_POST['uploadedfile'], FILE_EXISTS_REPLACE);
    $field_address = '';
    $body = '';
    $field_city = '';
    $field_company_1 = '';
    $field_company_2 = '';
    $field_contact_1 = '';
    $field_contact_2 = '';
    $field_country = '';
    $field_date_of_birth = '';
    $field_email_1 = '';
    $field_email_3 = '';
    $field_email_address = '';
    $field_first_name = '';
    $field_gender = '';
    $field_i_accept = '';
    $field_i_am_able_to_support_the_f = '';
    $field_i_consider_and_apply_the_f = '';
    $field_i_define_the_concept_of_qu = '';
    $field_i_have_been_working_as_fre = '';
    $field_involvement = '';
    $field_i_provide_the_following_se = '';
    $field_my_working_hours = '';
    $field_name = '';
    $field_please_mark_only_10_boxes = '';
    $field_please_mark_only_10_boxes_ = '';
    $field_select_known_languages = '';
    $field_short_test_translation_fre = '';
    $field_display_title = '';
    $field_work_phone = '';
    $field_your_proz_profile = '';
    $field_zip_code = '';

    //Gather all main form data.
    foreach($_POST['mainFormData'] as $mainform) {
      if($mainform['name'] == 'firstname') {
        $field_first_name = $mainform['value'];
      }
      elseif($mainform['name'] == 'name') {
        $field_name = $mainform['value'];
      }
      elseif($mainform['name'] == 'email') {
        $field_email_address = $mainform['value'];
      }
    }
    // Gather all wizard form data.
    foreach($_POST['wizardFormData'] as $wizardform) {
      if($wizardform['name'] == 'gender') {
        $field_gender = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'name') {
        $field_name = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'email') {
        $field_email_address = $wizardform['value'];
      }
    }

    // Create node object with attached file.
    $node = Node::create([
      'type'        => 'freelancers',
      'title'       => 'Freelancers received',
      'field_first_name' => $field_first_name,
      'field_name' => $field_name,
      'field_email_address' => $field_email_address,
      'field_gender' => $field_gender,
      'field_attached_files' => ['target_id' => $file->id()],
    ]);
    $node->save();

    // Send mails.
    $mailManager = \Drupal::service('plugin.manager.mail');
    // send mail to user.
    $module = 'site';
    $key = 'free_mail';
    $to = $field_email_address;
    $params['subject'] = t('Thank you');
    $params['message'] = t('Dear user,<br><br><br>Thank you for the mail. We have received your request. Will contact you shortly.<br><br><br>Regards,<br>Poptranslation.');
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

    // send mail to admin.
    $module = 'site';
    $key = 'free_mail_admin';
    $to = 'isarkar77@gmail.com';
    $params['subject'] = t('New Freelancer received');
    $params['message'] = t('Dear admin,<br><br><br>Please find below details<br><br> First Name: @fname<br>Name: @name<br>Email: @email<br><br><br>Regards,<br>Poptranslation.', array('@fname' => $field_first_name, '@name' => $field_name, '@email' => $field_email_address));
    $attachment = array(
      'filepath' => $filepath, // or $uri
    );
    $params['attachment'] = $attachment;
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    if ($result['result'] !== true) {
      drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
      return [
        '#markup' => '',
      ];
    }
    else {
      return [
        '#markup' => '',
      ];
    }
  }

  /**
   * Quote file handle.
   */
  public function uploadFile() {
    $ds = DIRECTORY_SEPARATOR;  //1 
    $storeFolder = '../../../../../sites/default/files';   //2
    if (!empty($_FILES)) {   
      $tempFile = $_FILES['file']['tmp_name'];          //3             
      $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
      $targetFile =  $targetPath. $_FILES['file']['name'];  //5
      if(move_uploaded_file($tempFile,$targetFile)) { //6
        return [
          '#markup' => $targetFile,
        ];
      }
      else {
        return [
          '#markup' => '',
        ];
      }
    }
  }

  /**
   * Quote file handle.
   */
  public function freelancers() {
    return [
      '#theme' => 'freelancers',
      '#site' => $this->t('site'),
    ];
  }
}

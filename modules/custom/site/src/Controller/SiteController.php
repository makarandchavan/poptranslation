<?php

namespace Drupal\site\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;
use \Drupal\field_collection\Entity\FieldCollectionItem;

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
    $to = 'cmak2007@gmail.com'; //\Drupal::config('system.site')->get('mail');
    $params['subject'] = t('New Quote received');
    $params['message'] = t('Dear admin,<br><br><br>Please find below details<br><br> Email: @usrmail<br>Entreprise: @usrent<br>Information: @usrinfo<br><br><br>Regards,<br>Poptranslation.', array('@usrmail' => $_POST['email'], '@usrent' => $_POST['entreprise'], '@usrinfo' => $_POST['information']));
    $attachment = array(
      'filepath' => $file->uri, // or $uri
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
  public function validateFreelance() {
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

    if(!empty($field_email_address)) {
      // Check if already submitted.
      $query = \Drupal::entityQuery('node')
        ->condition('type', 'freelancers')
        ->condition('field_email_address', $field_email_address);
      $result = $query->execute();
      if (!empty($result)) {
        echo '0';
        exit;
      }
      else {
        echo '1';
        exit;
      }
    }
  }

  /**
   * Freelance form handle.
   */
  public function handleFreelance() {
    // Insert a record.
    // Create file object from remote URL.
    global $base_url;
    if(!empty($_POST['uploadedfile'])) {
      $data = file_get_contents($base_url . '/sites/default/files/' . $_POST['uploadedfile']);
      $file = file_save_data($data, 'public://' . $_POST['uploadedfile'], FILE_EXISTS_REPLACE);
    }
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
    $field_select_known_languages = '';
    $field_short_test_translation_fre = '';
    $field_display_title = '';
    $field_work_phone = '';
    $field_your_proz_profile = '';
    $field_zip_code = '';
    $field_collection_item = FieldCollectionItem::create(['field_name' => 'field_please_mark_only_10_boxes']);

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

    if(!empty($field_email_address)) {
      // Check if already submitted.
      $query = \Drupal::entityQuery('node')
        ->condition('type', 'freelancers')
        ->condition('field_email_address', $field_email_address);
      $result = $query->execute();
      if (!empty($result)) {
        echo '0';
        exit;
      }
    }

    // Gather all wizard form data.
    foreach($_POST['wizardFormData'] as $wizardform) {
      if($wizardform['name'] == 'gender') {
        $field_gender = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'dob') {
        $field_date_of_birth = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'address') {
        $field_address = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'city') {
        $field_city = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'zip') {
        $field_zip_code = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'country') {
        $field_country = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'work') {
        $field_work_phone = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'services[]') {
        $field_i_provide_the_following_se .= $wizardform['value'] . ', ';
      }
      elseif($wizardform['name'] == 'support[]') {
        $field_i_am_able_to_support_the_f .= $wizardform['value'] . ', ';
      }
      elseif($wizardform['name'] == 'language1') {
        $field_select_known_languages .= $wizardform['value'] . ', ';
      }
      elseif($wizardform['name'] == 'language2') {
        $field_select_known_languages .= $wizardform['value'] . ', ';
      }
      elseif($wizardform['name'] == 'language3') {
        $field_select_known_languages .= $wizardform['value'] . ', ';
      }
      elseif($wizardform['name'] == 'language4') {
        $field_select_known_languages .= $wizardform['value'];
      }
      elseif($wizardform['name'] == 'translator[]') {
        $field_i_have_been_working_as_fre = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'working[]') {
        $field_my_working_hours .= $wizardform['value'] . ', ';
      }
      elseif($wizardform['name'] == 'quality[]') {
        $field_i_define_the_concept_of_qu .= $wizardform['value'] . ', ';
      }
      elseif($wizardform['name'] == 'procedures[]') {
        $field_i_consider_and_apply_the_f .= $wizardform['value'] . ', ';
      }
      elseif($wizardform['name'] == 'involvement[]') {
        $field_involvement = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'professional[]') {
        $field_collection_item->field_please_mark_only_10_boxes_->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating1') {
        $field_collection_item->field_please_mark_only_10_boxes1->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating2') {
        $field_collection_item->field_please_mark_only_10_boxes2->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating3') {
        $field_collection_item->field_please_mark_only_10_boxes3->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating4') {
        $field_collection_item->field_please_mark_only_10_boxes4->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating5') {
        $field_collection_item->field_please_mark_only_10_boxes5->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating6') {
        $field_collection_item->field_please_mark_only_10_boxes6->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating7') {
        $field_collection_item->field_please_mark_only_10_boxes7->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating8') {
        $field_collection_item->field_please_mark_only_10_boxes8->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating9') {
        $field_collection_item->field_please_mark_only_10_boxes9->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating10') {
        $field_collection_item->field_please_mark_only_10boxes10->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating11') {
        $field_collection_item->field_please_mark_only_10boxes11->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating12') {
        $field_collection_item->field_please_mark_only_10boxes12->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating13') {
        $field_collection_item->field_please_mark_only_10boxes13->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating14') {
        $field_collection_item->field_please_mark_only_10boxes14->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating15') {
        $field_collection_item->field_please_mark_only_10boxes15->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating16') {
        $field_collection_item->field_please_mark_only_10boxes16->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating17') {
        $field_collection_item->field_please_mark_only_10boxes17->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating18') {
        $field_collection_item->field_please_mark_only_10boxes18->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating19') {
        $field_collection_item->field_please_mark_only_10boxes19->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating20') {
        $field_collection_item->field_please_mark_only_10boxes20->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating21') {
        $field_collection_item->field_please_mark_only_10boxes21->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating22') {
        $field_collection_item->field_please_mark_only_10boxes22->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating23') {
        $field_collection_item->field_please_mark_only_10boxes23->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'rating24') {
        $field_collection_item->field_please_mark_only_10boxes24->setValue($wizardform['value']);
      }
      elseif($wizardform['name'] == 'free') {
        $field_short_test_translation_fre = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'transfer[]') {
        $field_i_accept .= $wizardform['value'] . ', ';
      }
      elseif($wizardform['name'] == 'profile') {
        $field_your_proz_profile = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'company1') {
        $field_company_1 = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'contact1') {
        $field_contact_1 = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'email1') {
        $field_email_1 = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'company2') {
        $field_company_2 = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'contact2') {
        $field_contact_2 = $wizardform['value'];
      }
      elseif($wizardform['name'] == 'email2') {
        $field_email_3 = $wizardform['value'];
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
      'field_date_of_birth' => $field_date_of_birth,
      'field_address' => $field_address,
      'field_city' => $field_city,
      'field_zip_code' => $field_zip_code,
      'field_country' => $field_country,
      'field_work_phone' => $field_work_phone,
      'field_i_provide_the_following_se' => $field_i_provide_the_following_se,
      'field_i_am_able_to_support_the_f' => $field_i_am_able_to_support_the_f,
      'field_select_known_languages' => $field_select_known_languages,
      'field_i_have_been_working_as_fre' => $field_i_have_been_working_as_fre,
      'field_my_working_hours' => $field_my_working_hours,
      'field_i_define_the_concept_of_qu' => $field_i_define_the_concept_of_qu,
      'field_i_consider_and_apply_the_f' => $field_i_consider_and_apply_the_f,
      'field_involvement' => $field_involvement,
      'field_involvement' => $field_involvement,
      'field_attached_files' => ['target_id' => (!empty($file) ? $file->id() : '')],
    ]);
    $node->save();
    $field_collection_item->setHostEntity($node);
    $field_collection_item->save();
    $node->field_please_mark_only_10_boxes->appendItem($field_collection_item);

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
    $to = 'cmak2007@gmail.com'; //\Drupal::config('system.site')->get('mail');
    $params['subject'] = t('New Freelancer received');
    $params['message'] = t('Dear admin,<br><br><br>Please find below details<br><br> First Name: @fname<br>Name: @name<br>Email: @email<br><br><br>Regards,<br>Poptranslation.', array('@fname' => $field_first_name, '@name' => $field_name, '@email' => $field_email_address));
    $attachment = array(
      'filepath' => $file->uri, // or $uri
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

  /**
   * Quote form handle.
   */
  public function handleContact() {
    // Insert a record.
    // Create file object from remote URL.
    global $base_url;

    //Gather all main form data.
    foreach($_POST['contactFormData'] as $mainform) {
      if($mainform['name'] == 'name') {
        $name = $mainform['value'];
      }
      elseif($mainform['name'] == 'email') {
        $email = $mainform['value'];
      }
      elseif($mainform['name'] == 'entreprise') {
        $enterprise = $mainform['value'];
      }
      elseif($mainform['name'] == 'message') {
        $cmessage = $mainform['value'];
      }
    }

    // Send mails.
    $mailManager = \Drupal::service('plugin.manager.mail');
    // send mail to user.
    $module = 'site';
    $key = 'contact_mail';
    $to = $email;
    $params['subject'] = t('Thank you');
    $params['message'] = t('Dear user,<br><br><br>Thank you for the mail. We have received your request. Will contact you shortly.<br><br><br>Regards,<br>Poptranslation.');
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

    // send mail to admin.
    $module = 'site';
    $key = 'contact_mail_admin';
    $to = 'cmak2007@gmail.com'; //\Drupal::config('system.site')->get('mail');
    $params['subject'] = t('New Contact received');
    $params['message'] = t('Dear admin,<br><br><br>Please find below details<br><br>Name: @usrname<br>Email: @usrmail<br>Entreprise: @usrent<br>Message: @usrinfo<br><br><br>Regards,<br>Poptranslation.', array('@usrname' => $name, '@usrmail' => $email, '@usrent' => $enterprise, '@usrinfo' => $cmessage));
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
}

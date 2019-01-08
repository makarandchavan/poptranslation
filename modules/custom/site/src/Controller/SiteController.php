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

<?php

namespace Drupal\site\Controller;

use Drupal\Core\Controller\ControllerBase;

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
    $to = 'cmak2008@gmail.com';
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
}

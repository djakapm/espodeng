<?
/*
The reCaptcha server keys and API locations

Obtain your own keys from:
http://www.recaptcha.net
*/
$config['recaptcha'] = array(
  'public'=>'6LcmmckSAAAAAEuMXGJXYaFISSWLA_Zwlqaoe81U',
  'private'=>'6LcmmckSAAAAAKejt2-DXH9pWPSrqGv7-fhvA1QG',
  'RECAPTCHA_API_SERVER' =>'http://www.google.com/recaptcha/api',
  'RECAPTCHA_API_SECURE_SERVER'=>'https://www.google.com/recaptcha/api',
  'RECAPTCHA_VERIFY_SERVER' =>'www.google.com',
  'RECAPTCHA_SIGNUP_URL' => 'https://www.google.com/recaptcha/admin/create',
  'theme' => 'red'
);

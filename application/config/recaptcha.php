<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// To use reCAPTCHA, you need to sign up for an API key pair for your site.
// link: http://www.google.com/recaptcha/admin

/* For http://192.168.0.70 Localserver START*/
$config['recaptcha_site_key'] = '6Lcgwi4UAAAAAMT-7gVti_TJb40gEY7Y_4SIwBIn';
$config['recaptcha_secret_key'] = '6Lcgwi4UAAAAAHxkrUC7x_qp2C-WPuIAGVUREz4h';
/* For 192.168.0.70 Localserver END*/

/* For http://184.154.53.181 testserver START*/
/* $config['recaptcha_site_key'] = '6LfZvC4UAAAAAOVahQt-QLevV8j2Nm4P7x-X23Pi';
$config['recaptcha_secret_key'] = '6LfZvC4UAAAAAHq8dgSFJrmbBnCqy2zIVBKcSwGu'; */
/* For 192.168.0.70 Localserver END*/

/* For This Site START*/
/* $config['recaptcha_site_key'] = '';
$config['recaptcha_secret_key'] = ''; */
/* For This Site END*/

// reCAPTCHA supported 40+ languages listed here:
// https://developers.google.com/recaptcha/docs/language
$config['recaptcha_lang'] = 'en';

/* End of file recaptcha.php */
/* Location: ./application/config/recaptcha.php */

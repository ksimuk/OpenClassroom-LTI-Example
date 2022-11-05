<?php

# insert your keys here
$oauth_consumer_key = 'CONSUMER_KEY';
$oauth_secret_key   = 'CONSUMER_SECRET';

# Codio assignment URL 
$codio_assignement_url = 'CODIO_ASSIGNMENT_LTI_URL';

$context_title = 'My Super Course'; # name of the course
$roles = 'Student'; # or Instructor 
$lis_person_contact_email_primary='some@openclassrooms.com';
$lis_person_name_full = 'Full Name';
$url="/"; # redirect to itself by default
$oauth_signature="";
$oauth_nonce = mt_rand();
$oauth_timestamp = time();

if ($_POST) {
  unset($_POST["oauth_signature"]);
  $context_title = $_POST['context_title'];
  $oauth_nonce = $_POST['oauth_nonce'];
  $oauth_timestamp = $_POST['oauth_timestamp'];
  $roles = $_POST['roles']; # or Instructor 
  $lis_person_contact_email_primary=$_POST['lis_person_contact_email_primary'];
  $user_id = hash('crc32', $lis_person_contact_email_primary); # should be key for the user in your system for the cases of the emal has changed.
  $_POST['user_id'] = $user_id;
  $lis_person_name_full = $_POST['lis_person_name_full'];
  $url = $codio_assignement_url; #codio LTI URL 
  $oauth_signature=make_signature();
}

$context_id = hash('crc32', $context_title); # uniq id of the course
$lis_person_sourcedid = 'school.edu,user';
$lti_message_type = 'basic-lti-launch-request';
$lti_version = 'LTI-1p0';
$oauth_callback = 'about:blank';
$oauth_signature_method = 'HMAC-SHA1';
$oauth_version = '1.0 ';
$resource_link_id = '10293847564738910'; #resource uniq Id
$user_id = hash('crc32', $lis_person_contact_email_primary); # should be key for the user in your system for the cases of the emal has changed.





function make_signature() {
  $parametersFormatted = array();
  foreach ($_POST as $key => $value) {
    $parametersFormatted[] = $key . "=" . rawurlencode($value);
  }

  sort($parametersFormatted);
  $parametersJoined = implode("&", $parametersFormatted);
  $paramsEncoded = rawurlencode($parametersJoined);
  $urlEncoded = rawurlencode($GLOBALS['url']);
  $forSign = "POST&".$urlEncoded."&".$paramsEncoded;
  print("<b>String for signature:</b> " . $forSign);
  $digest = base64_encode(hash_hmac('sha1', $forSign, $GLOBALS['oauth_secret_key'] . "&", true));
  return $digest;
}

 ?>
<html>
 <head>
  <title></title>
 </head>
 <body>
  <pre><?php print_r($_POST); ?></pre
 Open As:<br/>
 <form method="POST" id="form" action="<?php echo $url ?>" target="_blank">
  <input name="context_id" value="<?php echo $context_id  ?>" hidden>
  <input name="context_title" value="<?php echo $context_title ?>" <?php echo ($url == '/')? '' : 'readonly' ?>><br/><br/>
  <input name="lis_person_contact_email_primary" value="<?php echo $lis_person_contact_email_primary ?>" <?php echo ($url == '/')? '' : 'readonly' ?>><br/><br/>
  <input name="lis_person_name_full" value="<?php echo $lis_person_name_full ?>" <?php echo ($url == '/')? '' : 'readonly' ?>><br/><br/>
  <input name="lis_person_sourcedid" value="<?php echo $lis_person_sourcedid ?>" hidden>
  <input name="lti_version" value="<?php echo $lti_version ?>" hidden>
  <input name="lti_message_type" value="basic-lti-launch-request" hidden>
  <input name="oauth_consumer_key" value="<?php echo $oauth_consumer_key ?>" hidden>
  <input name="oauth_nonce" value="<?php echo $oauth_nonce ?>" hidden>
  <input name="oauth_callback" value="<?php echo $oauth_callback ?>" hidden>
  <input name="oauth_signature_method" value="<?php echo $oauth_signature_method ?>" hidden>
  <input name="oauth_version" value="<?php echo $oauth_version ?>" hidden>
  <input name="resource_link_id" value="<?php echo $resource_link_id ?>" hidden>
  <select name="roles" >
    <option value="Student" <?php echo ($roles == 'Student')? 'selected':''; ?>>Student</option>
    <option value="Teacher" <?php echo ($roles == 'Teacher')? 'selected':''; ?>>Teacher</option>
  </select><br/><br/>
  <input name="user_id" value="<?php echo $user_id ?>" hidden>
  <input name="oauth_timestamp" value="<?php echo $oauth_timestamp?>" hidden>
  <input name="oauth_signature" value="<?php echo $oauth_signature ?>" readonly><br/><br/>
  <input type="submit" value="<?php echo ($url == '/')? 'Generate' : 'Open Assignment' ?>"> 
</form>
 </body>
</html>

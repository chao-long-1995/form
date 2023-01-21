<?php

if (isset($_POST['type']) && isset($_POST['email'])) {
  $redirectUrls = array(
    "https://example.com/url1",
    "https://example.com/url2"
  );

  $type = $_POST['type'];
  $redirectUrl = $redirectUrls[--$type];

  $email_to = "info@example.com";
  $email_subject = "Website Contact Form";
  function died($error)
  {
    echo "We are very sorry, but there were error(s) found with the form you submitted. ";
    echo "These errors appear below.<br /><br />";
    echo $error . "<br /><br />";
    echo "Please go back and fix these errors.<br /><br />";
    die();
  }
  if (
    !isset($_POST['name']) ||
    !isset($_POST['email'])
  ) {
    died('We are sorry, but there appears to be a problem with the form you submitted.');
  }
  $name = $_POST['name'];
  $email = $_POST['email'];
  $error_message = "";
  $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
  if (!preg_match($email_exp, $email)) {
    $error_message .= 'The Email Address you entered does not appear to be valid.<br />';
  }
  $string_exp = "/^[A-Za-z .'-]+$/";
  if (!preg_match($string_exp, $name)) {
    $error_message .= 'The First Name you entered does not appear to be valid.<br />';
  }
  if (strlen($error_message) > 0) {
    died($error_message);
  }
  $email_message = "Form details below.\n\n";
  function clean_string($string)
  {
    $bad = array("content-type", "bcc:", "to:", "cc:", "href");
    return str_replace($bad, "", $string);
  }
  $email_message .= "Name: " . clean_string($name) . "\n";
  $email_message .= "Email: " . clean_string($email) . "\n";
  
  // create email headers
  $headers = 'From: ' . $email . "\r\n" .
    'Reply-To: ' . $email . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
  ini_set("SMTP", "smtp-relay.sendinblue.com");
  ini_set("smtp_port", "587");
  ini_set("sendmail_from", "info@example.com");
  ini_set("username", "example@gmail.com");
  ini_set("password", "xxxxxxxxxxxxxxx");
  $result = mail($email_to, $email_subject, $email_message, $headers);
  if ($result) {
    header("Location: ".$redirectUrl);
  } else {
    echo "Sorry, your message couldn't be sent. Please try again.";
  }
}
?>
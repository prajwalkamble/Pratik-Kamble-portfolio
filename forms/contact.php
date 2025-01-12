<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate the input fields
  $name = strip_tags(trim($_POST["name"]));
  $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
  $subject = strip_tags(trim($_POST["subject"]));
  $message = trim($_POST["message"]);

  // Check that data was sent to the mailer
  if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo "Please complete the form and try again.";
    exit;
  }

  // Set the recipient email address
  $recipient = "your-email@example.com";

  // Set the email subject
  $email_subject = "New contact from $name: $subject";

  // Build the email content
  $email_content .= "Message:\n$message\n";

  // Build the email headers
  $email_headers = "From: $name <$email>";

  // Send the email
  if (mail($recipient, $email_subject, $email_content, $email_headers)) {
    http_response_code(200);
    echo "Thank you! Your message has been sent.";
  } else {
    http_response_code(500);
    echo "Oops! Something went wrong, and we couldn't send your message.";
  }
} else {
  http_response_code(403);
  echo "There was a problem with your submission, please try again.";
}

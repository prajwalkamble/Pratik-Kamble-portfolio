<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  $allowedExtensions = [
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'pdf' => 'application/pdf',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'ppt' => 'application/vnd.ms-powerpoint',
    'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'txt' => 'text/plain'
  ];
  $maxFileSize = 50 * 1024 * 1024; // 50 MB

  // Handle file upload
  if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['fileInput']['tmp_name'];
    $fileName = $_FILES['fileInput']['name'];
    $fileSize = $_FILES['fileInput']['size'];
    $fileType = $_FILES['fileInput']['type'];
    $fileType = mime_content_type($fileTmpPath);
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Verify file extension and MIME type
    if (!array_key_exists($fileExtension, $allowedExtensions) || $allowedExtensions[$fileExtension] !== $fileType) {
      die("Error: Invalid file type.");
    }

    // Define upload directory
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    // Generate a unique file name to prevent overwriting
    $newFileName = uniqid() . '.' . $fileExtension;
    $destPath = "{$uploadDir}{$newFileName}";

    // Move the file to the upload directory
    if (move_uploaded_file($fileTmpPath, $destPath)) {
      echo "File uploaded successfully.";
    } else {
      echo "Error: There was a problem uploading your file.";
    }
  } else {
    echo "Error: " . $_FILES['fileInput']['error'];
  }

  $mail = new PHPMailer(true);
  try {
    $mail->setFrom($email, $name);
    $mail->addAddress('kamblepk9688@gmail.com', 'Recipient Name');
    $mail->Subject = $subject;
    $mail->Body = "Name: $name\nEmail: $email\nMessage: $message";
    $mail->send();
    echo 'Message has been sent';
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}

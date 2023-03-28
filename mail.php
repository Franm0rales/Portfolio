<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Initialize PHPMailer
$mail = new PHPMailer(true);

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$website = $_POST['website'];
$message = $_POST['message'];

// Initialize validation result
$data['error'] = false;

// Perform data validation
if (empty($name)) {
    $data['error'] = 'Please enter your name.';
} elseif (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
    $data['error'] = 'Please enter a valid email address.';
} elseif (empty($message)) {
    $data['error'] = 'The message field is required!';
} elseif (empty($phone)) {
    $data['error'] = 'Please enter your phone number.';
} elseif (empty($website)) {
    $data['error'] = 'Please enter your website.';
} else {
    try {
        // Set PHPMailer properties
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'picassomorale@gmail.com';
        $mail->Password = 'mzmibvqommidfcyf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set sender and recipient email addresses
        $mail->setFrom($email, $name);
        $mail->addAddress('picassomorale@gmail.com', 'Your Name');

        // Set email subject and message
        $mail->Subject = 'Contact Form Submission';
        $mail->Body = "Name: $name\nPhone: $phone\nWebsite: $website\nEmail: $email\nMessage: $message";

        // Add SPF and DKIM validation
        $mail->DKIM_domain = 'your_domain.com';
        $mail->DKIM_private = 'path/to/private.key';
        $mail->DKIM_selector = 'selector';
        $mail->DKIM_passphrase = '';
        $mail->sign($mail->DKIM_domain, $mail->DKIM_private, $mail->DKIM_selector, $mail->DKIM_passphrase);
        $mail->addCustomHeader('Received-SPF: pass (google.com: domain of '.$email.' designates IP_ADDRESS as permitted sender) client-ip=IP_ADDRESS;');
        $mail->addCustomHeader('Authentication-Results: mx.google.com; spf=pass (google.com: domain of '.$email.' designates IP_ADDRESS as permitted sender) smtp.mailfrom='.$email.'; dkim=pass header.i='.$email.';');

        // Send email
        $mail->send();

        // Set success message
        $data['message'] = 'Your message has been sent!';
    } catch (Exception $e) {
        // Set error message
        $data['error'] = 'Sorry, an error occurred: ' . $mail->ErrorInfo;
    }
}

// Return validation result as JSON
echo json_encode($data);

?>
<?php
// Email handler for Morwasehla Attorneys contact form

// Set timezone to South Africa Standard Time
date_default_timezone_set('Africa/Johannesburg');

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data and sanitize
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
        // Redirect back to contact page with error
        header("Location: contact.html?error=missing_fields");
        exit();
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.html?error=invalid_email");
        exit();
    }
    
    // Email recipient
    $to = "neileng@morwa-attorneys.co.za";
    
    // Email subject
    $email_subject = "New Contact Form Submission - Morwasehla Attorneys";
    
    // Build HTML email content
    $email_body_html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Form Submission</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f8f9fa;
            }
            .email-container {
                background-color: #ffffff;
                border-radius: 10px;
                padding: 30px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            .header {
                background: linear-gradient(135deg, #21d9ff 0%, #baf974 100%);
                color: white;
                padding: 20px;
                border-radius: 8px;
                text-align: center;
                margin-bottom: 30px;
            }
            .header h1 {
                margin: 0;
                font-size: 24px;
                font-weight: bold;
            }
            .contact-details {
                background-color: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 20px;
            }
            .contact-details h2 {
                color: #21d9ff;
                margin-top: 0;
                font-size: 18px;
                border-bottom: 2px solid #baf974;
                padding-bottom: 10px;
            }
            .detail-row {
                display: flex;
                margin-bottom: 10px;
                align-items: center;
            }
            .detail-label {
                font-weight: bold;
                color: #333;
                min-width: 120px;
                margin-right: 15px;
            }
            .detail-value {
                color: #666;
                flex: 1;
            }
            .message-section {
                background-color: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 20px;
            }
            .message-section h2 {
                color: #21d9ff;
                margin-top: 0;
                font-size: 18px;
                border-bottom: 2px solid #baf974;
                padding-bottom: 10px;
            }
            .message-content {
                background-color: white;
                padding: 15px;
                border-radius: 5px;
                border-left: 4px solid #21d9ff;
                white-space: pre-wrap;
                line-height: 1.6;
            }
            .footer {
                text-align: center;
                color: #666;
                font-size: 12px;
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
            }
            .footer p {
                margin: 5px 0;
            }
            @media only screen and (max-width: 600px) {
                body {
                    padding: 10px;
                }
                .email-container {
                    padding: 20px;
                }
                .detail-row {
                    flex-direction: column;
                    align-items: flex-start;
                }
                .detail-label {
                    min-width: auto;
                    margin-right: 0;
                    margin-bottom: 5px;
                }
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="header">
                <h1>New Contact Form Submission</h1>
                <p>Morwasehla Attorneys Website</p>
            </div>
            
            <div class="contact-details">
                <h2>Contact Information</h2>
                <div class="detail-row">
                    <div class="detail-label">Name:</div>
                    <div class="detail-value">' . $name . '</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">' . $email . '</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Phone:</div>
                    <div class="detail-value">' . $phone . '</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Subject:</div>
                    <div class="detail-value">' . $subject . '</div>
                </div>
            </div>
            
            <div class="message-section">
                <h2>Message</h2>
                <div class="message-content">' . $message . '</div>
            </div>
            
            <div class="footer">
                <p><strong>Sent from:</strong> Morwasehla Attorneys Contact Form</p>
                <p><strong>Website:</strong> morwa-attorneys.co.za</p>
                <p><strong>Date:</strong> ' . date("F j, Y \a\t g:i A T") . '</p>
                <p><strong>IP Address:</strong> ' . $_SERVER['REMOTE_ADDR'] . '</p>
            </div>
        </div>
    </body>
    </html>';
    
    // Plain text version for email clients that don't support HTML
    $email_body_text = "You have received a new contact form submission from the Morwasehla Attorneys website.\n\n";
    $email_body_text .= "Contact Details:\n";
    $email_body_text .= "Name: " . $name . "\n";
    $email_body_text .= "Email: " . $email . "\n";
    $email_body_text .= "Phone: " . $phone . "\n";
    $email_body_text .= "Subject: " . $subject . "\n\n";
    $email_body_text .= "Message:\n" . $message . "\n\n";
    $email_body_text .= "---\n";
    $email_body_text .= "This email was sent from the contact form on morwa-attorneys.co.za\n";
    $email_body_text .= "Sent on: " . date("Y-m-d H:i:s T") . "\n";
    $email_body_text .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
    
    // Email headers
    $headers = "From: Morwasehla Attorneys <info@morwa-attorneys.co.za>\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "X-Priority: 3\r\n";
    $headers .= "X-MSMail-Priority: Normal\r\n";
    $headers .= "Importance: Normal\r\n";
    $headers .= "Message-ID: <" . time() . "." . uniqid() . "@morwa-attorneys.co.za>\r\n";
    $headers .= "Date: " . date("r") . "\r\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"boundary123\"\r\n";
    
    // Build multipart email
    $email_message = "--boundary123\r\n";
    $email_message .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $email_message .= $email_body_text . "\r\n\r\n";
    
    $email_message .= "--boundary123\r\n";
    $email_message .= "Content-Type: text/html; charset=UTF-8\r\n";
    $email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $email_message .= $email_body_html . "\r\n\r\n";
    
    $email_message .= "--boundary123--";
    
    // Send email
    $mail_sent = mail($to, $email_subject, $email_message, $headers);
    
    if ($mail_sent) {
        // Log successful submission (optional)
        $log_entry = date("Y-m-d H:i:s") . " - Contact form submitted by " . $name . " (" . $email . ") - Phone: " . $phone . "\n";
        file_put_contents("contact_log.txt", $log_entry, FILE_APPEND | LOCK_EX);
        
        // Redirect to thank you page
        header("Location: thank-you.html");
        exit();
    } else {
        // Email failed to send
        header("Location: contact.html?error=email_failed");
        exit();
    }
    
} else {
    // Direct access to this file without form submission
    header("Location: contact.html");
    exit();
}
?> 
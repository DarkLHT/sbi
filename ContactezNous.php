<?php
$formData = $_POST;
sendMailAsAttachment( $formData );


function prepareEmail( $formData ) {
    
    // email fields: to, from, subject, and so on
    $to = "oussama.beddi@gmail.com ";
    $from = $formData['email']; 
    $subject = $formData['sujet']; 
    $message .= "Nom et Prénom :". $formData['name']."\n";
    $message .= "Adresse Courriel :". $formData['email']."\n";
    $message .= "Téléphone :". $formData['phone']."\n";
    $message .= "Message :". $formData['message']."\n";
    $headers = "From: $from";
 
    // boundary 
    $semi_rand = md5(time()); 
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
 
    // headers for attachment 
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
    // multipart boundary 
    $message .= "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
    $message .= "--{$mime_boundary}\n";
    
    $emailData = array (
        'to' => $to,
        'from' => $from,
        'subject' => $subject,
        'headers' => $headers,
        'message' => $message
    );
    
    return $emailData;
    
}

function sendMailAsAttachment( $formData ) {
    
    $emailData = prepareEmail( $formData );
    $message = $emailData['message'];
    $ok = @mail($emailData['to'], $emailData['subject'], $message, $emailData['headers']); 
    if ($ok) { 
            echo "<script type='text/javascript'>alert('Message Envoyer avec Succés Merci!')</script>";
            header( "refresh:1; url=contact.html" );

    } else { 
            echo "<script type='text/javascript'>alert('Message Non Envoyer Veuillez Réessayer Merci!)</script>";
            header( "refresh:1; url=contact.html" );
    } 
}

?> 
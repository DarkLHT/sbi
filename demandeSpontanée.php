<?php
$name_of_uploaded_file =basename($_FILES['uploaded_file']['name']);
$formData = $_POST;
getFile( $name_of_uploaded_file, $formData );
function getFile( $filename , $formData ) {
    
    $allowedExts = array("csv","pdf");
    $temp = explode(".", $_FILES["uploaded_file"]["name"]);
    $extension = end($temp);
    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
 
   sendMailAsAttachment($_FILES["uploaded_file"]["tmp_name"],$_FILES["uploaded_file"]["name"],$formData);    
}

function prepareEmail( $formData ) {
    
    // email fields: to, from, subject, and so on
    $to = "hamidhamza18@gmail.com";
    $from = $formData['email']; 
    $subject =""; 
    $message = "Ci-joint la demande de :\n";
    $message .= "Nom et Prénom :". $formData['name']."\n";
    $message .= "Adresse Courriel :". $formData['email']."\n";
    $message .= "Niveau d'étude :". $formData['niveau']."\n";
    $message .= "Demande de candidature :". $formData['demande']."\n";
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

function prepareAttachment( $filename ,$fileorgname) {
    $attachContent = '';
    $file = fopen($filename,"rb");
    $data = fread($file,filesize($filename));
    fclose($file);
    $cvData = chunk_split(base64_encode($data));
    $attachContent .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$fileorgname\"\n" . 
    "Content-Disposition: attachment;\n" . " filename=\"$fileorgname\"\n" . 
    "Content-Transfer-Encoding: base64\n\n" . $cvData . "\n\n";
    $attachContent .= "--{$mime_boundary}\n"; 
    return $attachContent;
    
}

function sendMailAsAttachment( $filename, $fileorgname, $formData ) {
    
    $emailData = prepareEmail( $formData );
    $attachContent = prepareAttachment( $filename,$fileorgname );
    $message = $emailData['message'].$attachContent;
    $ok = @mail($emailData['to'], $emailData['subject'], $message, $emailData['headers']); 
    if ($ok) { 
        echo "<script type='text/javascript'>alert('Message Envoyer avec Succés Merci!')</script>";
        header( "refresh:1; url=Carrieres.html" );

    } else { 
            echo "<script type='text/javascript'>alert('Message Non Envoyer Veuillez Réessayer Merci!)</script>";
            header( "refresh:1; url=Carrieres.html" );
    } 
}

?> 
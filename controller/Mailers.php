<?php

class Mailers extends Controller
{
    public function prepareShooting()
    {
        $this->loadModel('Shooter');
        $this->loadModel('People');
        print_r($this->People);
        print_r($this->Shooter);

        $people = $this->People->getToShoot();
        print_r($people);
        $shooter = $this->Shooter->getShooters();
        print_r($shooter);
    }

    public function test()
    {
        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'user@example.com';                 // SMTP username
        $mail->Password = 'secret';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        $mail->addAddress('ellen@example.com');               // Name is optional
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');

        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
        /*        $transport = Swift_SmtpTransport::newInstance('SSL0.OVH.NET', 587)
                   ->setUsername('05@nouveaumessage21.ovh')
                   ->setPassword('tomylyjon')
                   ;

        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance('Cool email')
                 ->setSubject('Your subject')
                 ->setFrom(['kevinpiac@gmail.com' => 'kevin de sym'])
                 ->setTo(array('kevinpiac@gmail.com'))
                 ->setBody('Ceci est le body normal.')
                 ->addPart('<p>Ceci est le body en HTML</p>', 'text/html')
                 ;
        $result = $mailer->send($message);
        print_r($result);*/
    }
}
<?php

define('MAILER_PASS', 'tomylyjon');

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
        die();
    }

    public function prepareSmtp($params)
    {
        $from="kevinpiac@gmail.com"; $sender_line=__LINE__;
        $to="kevinpiac@gmail.com"; $recipient_line=__LINE__;

        if(strlen($from)==0)
            die("Please set the messages sender address in line ".$sender_line." of the script ".basename(__FILE__)."\n");
        if(strlen($to)==0)
            die("Please set the messages recipient address in line ".$recipient_line." of the script ".basename(__FILE__)."\n");

        $smtp=new smtp_class;

        $smtp->host_name="SSL0.OVH.NET"; /* Change this variable to the address of the SMTP server to relay, like "smtp.myisp.com" */
        $smtp->host_port=587; /* Change this variable to the port of the SMTP server to use, like 465 */
        $smtp->ssl=0; /* Change this variable if the SMTP server requires an secure connection using SSL */

        $smtp->http_proxy_host_name='163.172.247.174'; /* Change this variable if you need to connect to SMTP server via an HTTP proxy */
        $smtp->http_proxy_host_port=80; /* Change this variable if you need to connect to SMTP server via an HTTP proxy */

        $smtp->socks_host_name = ''; /* Change this variable if you need to connect to SMTP server via an SOCKS server */
        $smtp->socks_host_port = 1080; /* Change this variable if you need to connect to SMTP server via an SOCKS server */
        $smtp->socks_version = '5'; /* Change this variable if you need to connect to SMTP server via an SOCKS server */

        $smtp->start_tls=0; /* Change this variable if the SMTP server requires security by starting TLS during the connection */
        $smtp->localhost="0.0.0.0"; /* Your computer address */
        $smtp->direct_delivery=0; /* Set to 1 to deliver directly to the recepient SMTP server */
        $smtp->timeout=10; /* Set to the number of seconds wait for a successful connection to the SMTP server */
        $smtp->data_timeout=0; /* Set to the number seconds wait for sending or retrieving data from the SMTP server.
                                  Set to 0 to use the same defined in the timeout variable */
        $smtp->debug=1; /* Set to 1 to output the communication with the SMTP server */
        $smtp->html_debug=0; /* Set to 1 to format the debug output as HTML */
        $smtp->pop3_auth_host=""; /* Set to the POP3 authentication host if your SMTP server requires prior POP3 authentication */
        $smtp->user="05@nouveaumessage21.ovh"; /* Set to the user name if the server requires authetication */
        $smtp->realm=""; /* Set to the authetication realm, usually the authentication user e-mail domain */
        $smtp->password="tomylyjon"; /* Set to the authetication password */
        $smtp->workstation=""; /* Workstation name for NTLM authentication */
        $smtp->authentication_mechanism=""; /* Specify a SASL authentication method like LOGIN, PLAIN, CRAM-MD5, NTLM, etc..
                                               Leave it empty to make the class negotiate if necessary */

        if($smtp->SendMessage(
            $from,
            array(
            $to
            ),
            array(
                "From: $from",
                "To: $to",
                "Subject: Testing Manuel Lemos' SMTP class",
                "Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")
            ),
            "Hello $to,\n\nIt is just to let you know that your SMTP class is working just fine.\n\nBye.\n"))
            echo "Message sent to $to OK.\n";
        else
            echo "Could not send the message to $to.\nError: ".$smtp->error."\n";
    }
}
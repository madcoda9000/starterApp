<?php
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\SMTP;
 use PHPMailer\PHPMailer\Exception;

class mail{
    public $t_subject;
    public $t_body;
    public $m_server;
    public $m_port;
    public $m_user;
    public $m_pass;
    public $m_encryption;
    public $m_fromAddress;
    public $m_toAddress;
    public $m_smtpAuth;

    // constructor
    public function __construct($server,$port,$user,$pass,$encryption,$from,$to,$smtpAuth){
        $this->m_server = $server;
        $this->m_port = $port;
        $this->m_encryption = $encryption;
        $this->m_user = $user;
        $this->m_pass = $pass;
        $this->m_fromAddress = $from;
        $this->m_toAddress = $to;
        $this->m_smtpAuth = $smtpAuth;
    }

    public function sendAdminMail() {
        try {

            if(isset($this->t_body) && isset($this->t_subject)) {
                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);

                //Server settings
                $mail->isSMTP();                                        //Send using SMTP
                $mail->Host       = $this->m_server;                    //Set the SMTP server to send through
                $mail->SMTPAuth   = $this->m_smtpAuth;                  //Enable SMTP authentication
                $mail->Username   = $this->m_user;                      //SMTP username
                $mail->Password   = $this->m_pass;                      //SMTP password
                $mail->SMTPSecure = $this->m_encryption;                //Enable implicit TLS encryption
                $mail->Port       = $this->m_port;                      //TCP port to connect to; use 587 if you have  set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                
                //Recipients
                $mail->setFrom($this->m_fromAddress);                   //Add from address
                $mail->addAddress($this->m_toAddress);                  //Add a recipient. Name is optional     
                $mail->addReplyTo($this->m_fromAddress);                //Add a reply address
                //$mail->addCC('cc@example.com');                       //Add a cc address
                //$mail->addBCC('bcc@example.com');                     //Add a bcc address

                //Attachments
                //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                
                //Content
                $mail->isHTML(true);                                    //Set email format to HTML
                $mail->Subject = $this->t_subject;                     //the subject
                $mail->Body    = $this->t_body;                        //the mail html body
                //$mail->AltBody = $_POST['body'];                      // This is the body in plain text for non-HTML mail clients

                //send the mail
                $mail->send();

                return "success";
            } else {
                return "missing parameters";
            }
        }
        catch (Exception $e) {

            return $e->getMessage();
        }
    }
}
?>
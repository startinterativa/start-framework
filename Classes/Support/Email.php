<?php
    namespace vitormarcelino\StartFramework\Support;

    class Email {

        var $helper;
        var $mail;

        function __construct($helper) {
            $this->helper = $helper;

            $this->mail = new \PHPMailer;
            $this->mail->isSMTP();
            $this->mail->SMTPDebug = 0;
            $this->mail->SMTPAuth = true;
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->Port = 587;
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Username = $GLOBALS['mail']['email'];
            $this->mail->Password = $GLOBALS['mail']['password'];
            $this->mail->setFrom($GLOBALS['mail']['email'], 'Start Interativa');
            $this->mail->addReplyTo($GLOBALS['mail']['email'], 'Start Interativa');
        }

        public function sendPlainMessage($subject, $message, $to, $name) {
            $this->mail->ClearAllRecipients();
            $this->mail->addAddress($to, $name);
            $this->mail->Subject = $subject;
            $this->mail->Body = $message;


            $this->mail->send();
        }

        public function sendEmail($subject, $content, $template, $to, $name) {
            $this->mail->ClearAllRecipients();
            $this->mail->addAddress($to, $name);

            $body['html'] = self::loadTemplate($template, $content);
            $body['plainText'] = self::loadTemplate($template. "Plain", $content);

            $this->mail->Subject = $subject;
            $this->mail->msgHTML($body['html']);
            $this->mail->AltBody = $body['plainText'];

            return $this->mail->send();
        }

        private function loadTemplate($template, $content) {
            return $this->helper->twig->render('mail/'.$template.'.twig', $content);
        }


        public static function sendConvite($cliente) {
            $to = $cliente->getEmail();
            $id = $cliente->getId();
            $empresa = $cliente->getEmpresa();
            $hash = \Controller\Helper::newHashCadastro($id);

            $boundary = uniqid('np');

            $from = "atendimento@startinterativa.com";

            $headers ="From: Atendimento Start Interativa <$from>\r\n";
            $headers.="MIME-Version: 1.0\r\n";
            $headers.="Reply-To: $from" . "\r\n";
            $headers.="Return-Path: $from" . "\r\n";
            $headers.="Content-Type: multipart/alternative;boundary=" . $boundary . "\r\n";

            $subject = "Social - Convite " . $empresa;

            $message = "Start Post Social - Convite " . $empresa;
            $message .= "\r\n\r\n--" . $boundary . "\r\n";
            $message .= "Content-type: text/plain;charset=utf-8\r\n\r\n";

            $message .= "Você foi convidado a entrar no sistema Social com a empresa ".$empresa." através do link: https://social.startcriativa.com.br/cadastro.php?hash=".$hash['valor'];
            $message .= "\r\n\r\n--" . $boundary . "\r\n";
            $message .= "Content-type: text/html;charset=utf-8\r\n\r\n";

            //Html body
            $message .= "<html><body><h2>Você foi convidado a entrar no sistema Social com a empresa ".$empresa."</h2>Através do link: https://social.startcriativa.com.br/cadastro.php?hash=".$hash['valor']."</body></html>";
            $message .= "\r\n\r\n--" . $boundary . "--";

            return intval(mail($to,$subject,$message,$headers,"-f $from"));

            // $myfile = fopen("email.txt", "w") or die("Unable to open file!");
            // fwrite($myfile, $message);
            // fclose($myfile);
        }
    }
?>

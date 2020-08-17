<?php

    require "./libs/PHPMailer/SMTP.php";
    require "./libs/PHPMailer/PHPMailer.php";
    require "./libs/PHPMailer/Exception.php";
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;



    class Mensagem {
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = array('codigo' => null, 'desc_status' => '');

        public function __get($attr) {
            return $this->$attr;
        }
        public function __set($attr, $valor) {
            $this->$attr = $valor;
        }
        public function mensagemValida() {
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
                return false;
            }
                return true;
        }
    }

    $mensagem = new Mensagem ();
    
    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);

    if (!$mensagem->mensagemValida()) {

        header('Location: index.php');
    }

    $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                     // Enable verbose debug output
            $mail->isSMTP();                                          // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                           // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = '';              // SMTP username
            $mail->Password = '';                         // SMTP password
            $mail->SMTPSecure = 'tls';                                // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                        // TCP port to connect to

            //Recipients
            $mail->setFrom('joczgabriel90@gmail.com', 'PHPMailer Test');
            $mail->addAddress($mensagem->__get('para'));              // Add a recipient
            // $mail->addAddress('ellen@example.com');                // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');          // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');     // Optional name

            //Content
            $mail->isHTML(true);                                      // Set email format to HTML
            $mail->Subject = $mensagem->__get('assunto');
            $mail->Body    = $mensagem->__get('mensagem');
            // $mail->AltBody = 'Teste do appSendMail';

            $mail->send();
            $mensagem->status['codigo'] = 1;
            $mensagem->status['desc_status'] = 'E-mail enviado com sucesso';
            
            } catch (Exception $e) {

            $mensagem->status['codigo'] = 2;
            $mensagem->status['desc_status'] = 'Não foi possível enviar a mensagem, detalhes do erro: '.$mail->ErrorInfo;

        }
?>

<html>
	<head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>

	<body>
        <div class="container">
                <div class="py-3 text-center">
                    <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
                    <h2>Send Mail</h2>
                    <p class="lead">Seu app de envio de e-mails particular!</p>
                </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    
                    <? if($mensagem->status['codigo'] == 1) { ?>
                        <div class="container mt-5">
                            <div class="text-center">
                                <h1 class="display-4 text-success">Sucesso!</h1>
                                <p> <?= $mensagem->status['desc_status'] ?> </p>
                                <a href="index.php" class="btn btn-success btn-lg mb-5 text-white">Voltar</a>
                            </div>
                        </div>
                    <? } ?>

                    <? if($mensagem->status['codigo'] == 2) { ?>
                        <div class="container mt-5">
                            <div class="text-center">
                                <h1 class="display-4 text-danger">Oops! Houve um erro!</h1>
                                <p> <?= $mensagem->status['desc_status'] ?> </p>
                                <a href="index.php" class="btn btn-danger btn-lg mb-5 text-white">Voltar</a>
                            </div>
                        </div>
                    <? } ?>

                </div>
            </div>
        </div>
    </body>
</html>

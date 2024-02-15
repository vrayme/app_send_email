<?php 

/*
echo  '<pre>';

print_r($_POST);

echo  '<pre>';
*/


//importando a biblioteca PHPMailer, para envio de emails

require "./bibliotecas/PHPMailer/Exception.php";
require "./bibliotecas/PHPMailer/OAuth.php";
require "./bibliotecas/PHPMailer/PHPMailer.php";
require "./bibliotecas/PHPMailer/POP3.php";
require "./bibliotecas/PHPMailer/SMTP.php";



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



class Mensagem {
    
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    public $status = array ('codigo_status' => null, 'descricao_status' => ''); 

    public function __set ($atributo, $valor) {
        $this->$atributo = $valor;
    }
    public function __get ($atributo) {
        return $this->$atributo;
    }

    public function validaMensagem () {
        //função vai testar se os campos do formulario de envio do email estao preenchidos
        if (empty ($this->para) || empty ($this->assunto) || empty ($this->mensagem)) {  // empty (varivael) = determina se uma varivael esta vazia 
            return false;
        }
            return true;
    }

}


$mensagem = new Mensagem ();
$mensagem->__set ('para', $_POST ['para']); // recuperando o valor contido nos campos, atraves da super global $_POST ['indice'];
$mensagem->__set ('assunto', $_POST ['assunto']);
$mensagem->__set ('mensagem', $_POST ['mensagem']);


/*

if ($mensagem->validaMensagem()) {
    echo 'Essa mensagem é valida';
} else {
    echo 'Essa mensagem não é valida';
}

print_r($mensagem);

*/

if (!$mensagem->validaMensagem()) {
    echo 'Essa mensagem não é valida';
    //
    header('Location: index.php');
}

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = false;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'victorraymesn@gmail.com';                     //SMTP username
    $mail->Password   = 'iyyo rpat cguo anqx';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('victorraymesn@gmail.com', 'Victor Rayme'); // remetente
    $mail->addAddress($mensagem->__get('para'));     //Add a recipient  // destinatario
    //$mail->addAddress('ellen@example.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
   // $mail->addBCC('bcc@example.com');

    //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mensagem->__get ('assunto'); // assunto
    $mail->Body    = $mensagem->__get ('mensagem'); // mensagem
    $mail->AltBody = 'É necessario um client que suporte HTML, para ter acesso ao conteudo total dessa mensagem'; // mensagem

    $mail->send();
    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso!';
     // mensagem de sucesso
} catch (Exception $e) {
    $mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = "Não foi possivel enviar esse E-mail, tente novamente mais tarde! Detalhes do erro: {$mail->ErrorInfo}";
     // mensagem de erro
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
            <div class="row">
                <div class="col-md-12">

                <? if ($mensagem->status['codigo_status'] == 1) { ?>

                    <div class="container">

                    <h1 class="display-4 text-sucess">Sucesso!</h1>
                    <p><?= $mensagem->status['descricao_status'] ?></p>
                    <a href="index.php" class="btn btn-success btn-lg mt-5 text-black">Voltar</a>

                    </div>

                <? } ?>

                <? if ($mensagem->status['codigo_status'] == 2) { ?>

                    <div class="container">

                    <h1 class="display-4 text-danger">Ops!</h1>
                    <p><?= $mensagem->status['descricao_status'] ?></p>
                    <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>

                    </div>

                <? } ?>

                </div>
            </div>
    </div>
</body>
</html>
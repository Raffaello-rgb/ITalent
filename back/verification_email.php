<?php
include('../includes/bd.php');

include('../includes/phpmailer.php');

    $rand_verification_email = rand(100000, 999999);
    $q = 'UPDATE USERS
    SET email_number = :email_number
    WHERE email = \''. htmlspecialchars($_GET['message']).'\'';
    $req=$bdd->prepare($q);
    $result=$req->execute([
        'email_number' => $rand_verification_email
        ]);


    //Recipients
    $mail->setFrom('italent.contact.site@gmail.com', 'Italent');
    $mail->addAddress(htmlspecialchars($_GET['message'])); // Destinataire

    $body = '<p>Bonjour, nous vous remercions de faire confiance à Italent pour la recherche de votre prochain emploi ! <br><br>
    Nous avons juste besoin d\'une petite vérification de votre part pour que vous puissiez vous connecter. <br>
    Copiez ce code pour vérifier votre identité et retournez sur Italent !<br>Votre code de vérification : <b>' . $rand_verification_email . '</b></p>';


    //Attachments :
    $mail->addAttachment('../assets/LOGO_version_complète.png', "LOGO_version_complète.png");

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Confirmation de votre inscription';
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    try {
    $mail->send();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    } 
?>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center text-white mb-4">Code de vérification envoyé, consultez votre boite mail !</h2>
        <div class="row">
        <div class="col-md-6 mx-auto">


<!-- form card login -->
    <div class="card rounded-0">
        <div class="card-header">
        <h3 class="mb-0">Validation du code</h3>
            </div>
                <div class="card-body">
                    <form id="form_code" action="codes_verification.php" method="POST">
                        <div class="form-group">
                            <label for="uname1">email</label>
                            <input type="email" class="form-control form-control-lg rounded-0" id="code_email" name="email" value="<?= htmlspecialchars($_GET['message'])?>" onFocus="this.value='';">
                        </div>
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" class="form-control form-control-lg rounded-0" name="code">
                        </div>
                        <input type="submit" class="btn btn-success btn-lg float-right my-1" value="Vérifier mon code">
                    </form>
                </div>
            </div>
    </div>
    </div>
    </div>
    </div>
    </div>
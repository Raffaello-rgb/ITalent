<?php
session_start();
$_SESSION['code'] = '1';
include('../includes/bd.php');
include('../includes/phpmailer.php');
include('../includes/head.php');
include('../includes/header.php');


    $rand_verification_email = rand(1000000, 9999999); // Genère une valeur à 7 chiffres
    $select_id = 'SELECT user_id FROM USERS WHERE email = :email';
    $req = $bdd->prepare($select_id);
    $result = $req->execute([
        'email' => htmlspecialchars($_GET['message'])
    ]);
    $result = $req->fetch(PDO::FETCH_ASSOC);

    $id_user = $result['user_id'];
    
    $date = date('Y-m-d H:i:s', strtotime('1 hour')); // Rajoute une heure pour stocker l'expiration

    $token = 'INSERT INTO TOKEN (value, date, user_id) values (:value, :date, :user_id)';
    $req=$bdd->prepare($token);
    $result=$req->execute([
        'value' => $rand_verification_email,
        'date' => $date,
        'user_id' => $id_user
        ]);


    //Recipients
    $mail->setFrom('italent.contact.site@gmail.com', 'Italent');
    $mail->addAddress(htmlspecialchars($_GET['message'])); // Destinataire

    $body = '<p>Bonjour, nous vous remercions de faire confiance à Italent pour la recherche de votre prochain emploi ! <br><br>
    Nous avons juste besoin d\'une petite vérification de votre part pour que vous puissiez vous connecter. <br>
    Copiez ce code : <br></p>
    <h3>' . $rand_verification_email . '</h3>
    <p>Et cliquez sur ce lien pour vérifier votre identitée : <a href="https://italent.site/back/codes_verification.php?id=' . $id_user . '&token=' . $rand_verification_email . '&check=0">Clique vite !</a><br>
    <b>Attention !</b> Ce lien n\'est valable que pendant 1h! </p>';


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


    // Code pour le reload de la page

    if(isset($_GET['reload'])){
        $check_token = 'SELECT email_check FROM USERS WHERE email = :email';
        $req = $bdd->prepare($check_token);
        $result = $req->execute([
            'email'=> htmlspecialchars($_GET['message'])
        ]);
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if($result['email_check'] = 1){
            header('location: ../connexion.php?messageSuccess=Votre email a été vérifié, veuillez vous connecter');
        }
    }


?>
<script>
    var timer = null;
    function auto_reload()
    {
        window.location = 'https://italent.site/back/verification_email.php?reload=1';  //your page location
    }

</script> 


<!-- Recharge la page toutes les 10 seconds. -->
<body onload="timer = setTimeout('auto_reload()',10000);">
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center text-white mb-4">Code de vérification envoyé, consultez votre boite mail ! Lorsque votre email aura été vérifié, revenez sur cette page...</h2>
        </div>
    </div>
</div>
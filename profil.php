<?php
session_start(); 

include('includes/bd.php'); 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion.php'); 
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM USERS WHERE user_id = ?";
$stmt = $bdd->prepare($query);
$stmt->execute([$user_id]);
$userInfo = $stmt->fetch();

$queryPublications = "SELECT * FROM PUBLICATIONS WHERE user_id = ? ORDER BY publi_id DESC";
$stmtPublications = $bdd->prepare($queryPublications);
$stmtPublications->execute([$user_id]);
$publications = $stmtPublications->fetchAll();

$queryStorys = "SELECT * FROM STORYS WHERE user_id = ? AND expiration > NOW() ORDER BY story_id DESC";
$stmtStorys = $bdd->prepare($queryStorys);
$stmtStorys->execute([$user_id]);
$storys = $stmtStorys->fetchAll();

// LISTE DE COMPÉTENCES
$q='SELECT name FROM COMPETENCES ;';
$req=$bdd->prepare($q);
$req->execute(); 

$url = 'profil.php'; //Permet de revenir sur cette page en cas d'erreurs dans les pages newsletter
?>

<!DOCTYPE html>
<html lang="fr">
    <?php
    $title='Profil';
    $url = 'profil.php';
    include('includes/head.php');
    ?>
    <body class="bg-light">
        <?php include('includes/header.php'); ?>

        <main class="bg-light pt-5">

        

            <div class="container mt-5">
            <?php 
            if(isset($_GET['messageFailure'])){
                echo '<div class="alert alert-danger" role="alert">'.htmlspecialchars($_GET['messageFailure']).'</div>'; 
            }
            if(isset($_GET['messageSuccess'])){
                echo '<div class="alert alert-success" role="alert">'.htmlspecialchars($_GET['messageSuccess']).'</div>'; 
            }
    ?>
                <div class="row">

                    <div class="col-12 mb-5">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h2 class="card-title">Profil de <?php echo htmlspecialchars($userInfo['firstname']) . ' ' . htmlspecialchars($userInfo['lastname']); ?></h2>
                                <form action="back/modif_profil.php" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="firstname" class="form-label">Prénom</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($userInfo['firstname']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="lastname" class="form-label">Nom</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($userInfo['lastname']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userInfo['email']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tel" class="form-label">Téléphone</label>
                                            <input type="tel" class="form-control" id="tel" name="tel" value="<?php echo htmlspecialchars($userInfo['tel']); ?>" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
                                </form>
                                <a href="back/supp_compte.php" class="btn btn-danger mt-3" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">Supprimer mon compte</a>
                            </div>
                        </div>
                    </div>


                    <div class="container mt-5">
                        <h3 class="section-title">Storys</h3>
                        <div class="story-bar">
                            <div class="mb-3">
                                <h4>Ajouter une Story</h4>
                                <form action="back/ajout_story.php" method="POST" enctype="multipart/form-data">
                                    <input type="file" name="image_story" accept="image/*" required>
                                    <button type="submit" class="btn btn-primary">Publier</button>
                                </form>
                            </div>
                            <?php foreach ($storys as $story): ?>
                                <div class="story-circle me-3 position-relative">
                                    <img src="<?php echo htmlspecialchars('uploads/storys/' . $story['image']); ?>" alt="Image de story" style="width: 100px; height: 100px;"> 
                                    <a href="back/supp_story.php?story_id=<?php echo $story['story_id']; ?>" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette story ?');">X</a>
                                </div>
                            <?php endforeach; ?>


                        </div>
                    </div>

                    <div class="col-12 mb-4 mt-5">
                        <h3>Publications</h3>
                        <div class="row">
                            <div class="mb-4">
                                <h4>Ajouter une Publication</h4>
                                <form action="back/ajout_publi.php" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <input type="file" name="image_publication" accept="image/*" required>
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="description" class="form-control" placeholder="Description"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Publier</button>
                                </form>
                            </div>

                            <?php foreach ($publications as $publication): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 position-relative">
                                        <img src="<?php echo htmlspecialchars('uploads/publications/' . $publication['image']); ?>" class="card-img-top" alt="Image de publication">
                                        <div class="card-body">
                                            <p class="card-text"><?php echo htmlspecialchars($publication['description']); ?></p>
                                            <a href="back/supp_publi.php?publi_id=<?php echo $publication['publi_id']; ?>" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');">X</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>


                </div>

                <h3 class="text-center" >Ajouter une compétence à votre CV</h3>

                <form method="post" action="exam.php">
                    <label class="form-label" for="selectCompetence">Compétence que vous voulez tester:</label>
                    <select id="selectCompetence" class="form-select" name="competenceTest">
                        <option selected>Sélectionner une compétence</option>
                        <?php 
                        while($result=$req->fetch(PDO::FETCH_ASSOC)){
                            foreach($result as $index=>$value){
                                echo '<option value="'. $value .'">'. $value.'</option>';                   
                            }
                        }

                        ?>
                    </select>
                    <button type="submit" class="btn btn-primary my-4">Envoyer</button>
                </form>


            </div>
        </main>

        <?php include('includes/footer.php'); ?>
    </body>
</html>
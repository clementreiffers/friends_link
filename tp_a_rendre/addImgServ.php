<?php include "ban.php"; //Ajout de la bannière sur la page

//Si utilisateur connecté, affichage de la page
if (isset($_SESSION["email"]) and $_SESSION["email"] != NULL) {
?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>Modification image</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css">
        <?php
        $css = pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME) == "index.php" ? "indexBan.css" : "ban.css";
        echo "<link rel='stylesheet' href='$css'>";
        ?>
    </head>

    <body>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="fileToUpload">Sélectionnez une image depuis votre appareil :</label></br>
            <input type="file" name="fileToUpload" accept="image/*"></br>
            <input type="submit" value="Envoyer"></br>
        </form>

        <?php
        /*Si le form ci-dessus est envoyé alors l'image envoyé en post va être ajouté à la BDD et au stockage du serveur 
         *Après vérification du fichier envoyé, s'il s'agit bien d'une image et si elle n'est pas trop lourde.
        */
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $imageActuelle = "images/profiles/" . selectNomImageFromEmail($_SESSION['email'])['nomImage'];

            $imageName = $_SESSION['email'] . date("Y-m-d") . time();

            if (mime_content_type($_FILES['fileToUpload']['tmp_name']) == 'image/png' || mime_content_type($_FILES['fileToUpload']['tmp_name']) == 'image/jpeg' || mime_content_type($_FILES['fileToUpload']['tmp_name']) == 'image/gif') {

                if ($_FILES['fileToUpload']['size'] < 500000) {
                    updateImageMembre($_SESSION['email'], $imageName);
                    move_uploaded_file($_FILES['fileToUpload']['tmp_name'], "images/profiles/$imageName");
                    if ($imageActuelle != NULL && file_exists($imageActuelle)) {
                        unlink($imageActuelle);
                    }
                    header("Location: addImgServ.php");
                } else {
                    echo "<p>Fichier trop lourd, veuillez sélectionner une image de moins de 500ko</p>";
                }
            } else {
                echo "<p>Fichier invalide</p>";
            }
        }
        ?>

        <a href="mon_profil.php">Retour sur mon profil</a>
    </body>

    </html>

<?php
} else {
    header("Location: login.php"); //Retour à la page de connexion, si l'utilisateur n'est pas connecté
}
?>
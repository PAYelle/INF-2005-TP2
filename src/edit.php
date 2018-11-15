<?php
session_start();
require 'ouvrages.php';
require 'utils.php';
use function Ouvrages\{findById};
use function Utils\{showCheckBox, showError, showValue};

/*Tableau contenant les messages d'erreurs*/
$errorMessages = [
    'titre' => 'Titre obligatoire',
    'auteurs' => 'Auteur obligatoire',
    'editeur' => 'Éditeur obligatoire',
    'edition' => 'L\'édition doit être plus grande que 0',
    'anneeParution' => 'L\'année de parution doit être plus grande que 0',
    'isbn' => 'Doit être numérique et contenir 10 ou 13 chiffres',
    'supports' => 'Sélectionner au moins un support'
];

/**
 * Fonction permettant d'afficher les erreurs en cas de modification de l'ouvrage.
 * @return string: un message d'erreur s'il y a erreur, sinon retourne une chaîne vide.
 */
function showErrorUpdateMsg() {
    return isset($_GET['error']) ? "<h2 id='msgErreur'>Oops! Des erreurs empêchent la sauvegarde des modifications</h2>" : '';
}

//MAIN
$id = $_GET['id'];
$dataSource = isset($_GET['error']) ? $_SESSION['donnees'] : findById($id);
$dataError = isset($_GET['error']) ? $_SESSION['erreurs'] : [];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Modification d'un ouvrage</title>
    <style>
        header h1 {
            margin-top: 0;
        }

        label span {
            color: red;
            font-weight: bold;
        }

        input {
            border-radius: 6px;
            border: 1px solid dodgerblue;
            padding: 6px 10px;
            margin-bottom: 15px;
        }

        input[type=checkbox] {
            margin-bottom: 5px;
        }

        button {
            padding: 10px 20px;
            margin: 10px 0;
            background-color: transparent;
            color: dodgerblue;
            border: 1px solid dodgerblue;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            opacity: 0.7;
        }

        #msgErreur{
            color: red;
            background-color: lightgray;
            border-radius: 6px;
        }

        #btnAnnuler {
            background-color: dodgerblue;
            color: white;
        }
    </style>
</head>
<body class="container">
    <header>
        <h1>Modification d'un ouvrage</h1>
        <?php echo showErrorUpdateMsg(); ?>
    </header>
    <main>
        <form action="update.php?id=<?php echo $id ?>" method="post">

            <label for="titre">Titre <span>*</span></label><br>
            <input id="titre" type="text" name="donnees[titre]"
                   value="<?php echo showValue('titre', $dataSource); ?>"><br>
            <?php echo showError('titre', $dataError, $errorMessages); ?>

            <label for="sousTitre">Sous-titre</label><br>
            <input id="sousTitre" type="text" name="donnees[sousTitre]"
                   value="<?php echo showValue('sousTitre', $dataSource); ?>"><br>
            <?php echo showError('sousTitre', $dataError, $errorMessages); ?>

            <label for="auteurs">Auteurs <span>*</span></label><br>
            <input id="auteurs" type="text" name="donnees[auteurs]"
                   value="<?php echo showValue('auteurs', $dataSource); ?>"><br>
            <?php echo showError('auteurs', $dataError, $errorMessages); ?>

            <label for="editeur">Éditeur <span>*</span></label><br>
            <input id="editeur" type="text" name="donnees[editeur]"
                   value="<?php echo showValue('editeur', $dataSource); ?>"><br>
            <?php echo showError('editeur', $dataError, $errorMessages); ?>

            <label for="edition">Édition</label><br>
            <input id="edition" type="text" name="donnees[edition]"
                   value="<?php echo showValue('edition', $dataSource); ?>"><br>
            <?php echo showError('edition', $dataError, $errorMessages); ?>

            <label for="anneeParution">Année de parution <span>*</span></label><br>
            <input id="anneeParution" type="text" name="donnees[anneeParution]"
                   value="<?php echo showValue('anneeParution', $dataSource); ?>"><br>
            <?php echo showError('anneeParution', $dataError, $errorMessages); ?>

            <label for="isbn">ISBN</label><br>
            <input id="isbn" type="text" name="donnees[isbn]" value="<?php echo showValue('isbn', $dataSource); ?>"><br>
            <?php echo showError('isbn', $dataError, $errorMessages); ?>

            <label>Supports <span>*</span></label><br>
            <?php
            $tabSupports = ['kindle' => 'Kindle', 'epub' => 'EPUB', 'papier' => 'Papier', 'pdf' => 'PDF'];
            foreach ($tabSupports as $key => $value) {
                echo showCheckBox($key, $value, $dataSource);
            }
            echo showError('supports', $dataError, $errorMessages);
            ?>

            <button id="btnAnnuler" type="submit" formaction="index.php">Annuler</button>
            <button type="submit" formmethod="post">Enregistrer</button>
        </form>
    </main>
    <footer></footer>
</body>
</html>
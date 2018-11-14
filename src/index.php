<?php
require 'ouvrages.php';
require 'utils.php';
use function Ouvrages\{findAll};
use function Utils\{getAttributes, showSupports};

/**
 * Fonction permettant de trouver tous les livres dans la base de données et de les
 * afficher à l'écran. S'il n'y a aucun livre alors affiche un message en conséquence.
 */
function showBookIndex() {
    $tabEdition = [1 => '1st ed.', 2 => '2nd ed.', 3 => '3rd ed.'];
    $ouvrages = findAll();
    if ($ouvrages) {
        foreach ($ouvrages as $ouvrage) {
            $datas = getAttributes($ouvrage);
            $datas['titreEdition'] = titleEditionFormatting($datas['titre'], $datas['edition'], $tabEdition);
            $datas['actions'] = "<a href='show.php?id={$datas['id']}'>Afficher</a>";
            showTableRowIndex($datas);
        }
    }
}

/**
 * Fonction permettant d'afficher à l'écran les 'rows' de la table html.
 * @param $datas : l'information que contient un ouvrage.
 */
function showTableRowIndex($datas) {
    $supports = showSupports($datas['supports']);
    echo "<tr>";
    echo "<td>{$datas['id']}</td>";
    echo "<td>{$datas['titreEdition']}</td>";
    echo "<td>{$datas['auteurs']}</td>";
    echo "<td>{$supports}</td>";
    echo "<td>{$datas['actions']}</td>";
    echo "</tr>";
}

/**
 * Fonction permettant de formatter le titre et l'édition ensemble si les deux champs
 * sont remplis.
 * @param $title : le titre de l'ouvrage.
 * @param $edition : l'édition de l'ouvrage.
 * @param $tabEdition : un tableau contenant le formattage de l'édition pour la
 * première, deuxième et troisième édition puisqu'il est différent.
 * @return string : retourne le titre formatter avec l'édition, sinon le titre.
 */
function titleEditionFormatting($title, $edition, $tabEdition) {
    if ($edition != '') {
        return $title . ', ' . ($tabEdition[$edition] ?? $edition . 'th ed.');
    } else {
        return $title;
    }
}

/**
 * Fonction permettant d'afficher que le livre a été supprimé avec succès.
 * @return string : retourne un message de suppression avec succès si la suppression
 * a eu lieu, sinon retourne une chaîne vide.
 */
function showSuccessfulDeletedBookMsg() {
    return isset($_GET['delete']) ? "<h2 id='msgSuppression'>Ouvrage supprimé avec succès.</h2>" : '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Bibliographie</title>
    <style>
        tbody td {
            margin: 0 5px;
            padding: 20px;
        }

        tr:nth-child(even) {
            background-color: white;
        }

        #msgSuppression {
            border: 1px solid lightgreen;
            background-color: lightgreen;
            border-radius: 6px;
            color: forestgreen;
            font-size: 16px;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>
<body class="container">
    <header>
        <h1>Bibliographie</h1>
        <?php echo showSuccessfulDeletedBookMsg(); ?>
    </header>
    <main>
        <section>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titre et édition</th>
                        <th>Auteurs</th>
                        <th>Supports</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php showBookIndex(); ?>
                </tbody>
                <tfoot></tfoot>
            </table>
        </section>
        <section>
            <form>
                <button type="submit" formaction="create.php">Ajouter un nouvel ouvrage</button>
            </form>
        </section>
    </main>
    <footer></footer>
</body>
</html>
<?php
require 'ouvrages.php';
require 'utils.php';
use function Ouvrages\{findById};
use function Utils\{getSupports, showSupports};

/**
 * Fonction permettant d'afficher le livre dans la table html.
 * @param $ouvrage : l'ouvrage contenant les informations a afficher,
 * affiche rien s'il n'y a pas d'ouvrage.
 */
function showBook($ouvrage) {
    if ($ouvrage) {
        $datas = getAttributes($ouvrage);
        showTableRow($datas);
    } else {
        echo "";
    }
}

/**
 * Fonction permettant de créer et d'afficher une 'row' pour la table html.
 * @param $datas : les informations sur l'ouvrage a afficher.
 */
function showTableRow($datas) {
    $supports = showSupports($datas['supports']);
    echo "<tr>";
    echo "<td>{$datas['titre']}</td>";
    echo "<td>{$datas['sousTitre']}</td>";
    echo "<td>{$datas['auteurs']}</td>";
    echo "<td>{$datas['editeur']}</td>";
    echo "<td>{$datas['edition']}</td>";
    echo "<td>{$datas['anneeParution']}</td>";
    echo "<td>{$datas['isbn']}</td>";
    echo "<td>{$supports}</td>";
    echo "<td>{$datas['action1']}</td>";
    echo "<td>{$datas['action2']}</td>";
    echo "</tr>";
}

/**
 * Fonction permettant d'aller chercher les attributs sur l'ouvrage et de les
 * mettre dans un tableau.
 * @param $ouvrage : l'ouvrage contenant les informations dans la bd.
 * @return mixed : le tableau contenant les données sur l'ouvrage et le data
 * qui sera affiché à l'écran dans la table html.
 */
function getAttributes($ouvrage) {
    $id = getAttribute('id', $ouvrage);
    $datas['titre'] = getAttribute('titre', $ouvrage);
    $datas['sousTitre'] = getAttribute('sousTitre', $ouvrage);
    $datas['auteurs'] = getAttribute('auteurs', $ouvrage);
    $datas['editeur'] = getAttribute('editeur', $ouvrage);
    $datas['edition'] = getAttribute('edition', $ouvrage);
    $datas['anneeParution'] = getAttribute('anneeParution', $ouvrage);
    $datas['isbn'] = getAttribute('isbn', $ouvrage);
    $datas['supports'] = getSupports(getAttribute('supports', $ouvrage));
    $datas['action1'] = "<a href='edit.php?id=$id'>Modifier</a>";
    $datas['action2'] = "<form action='delete.php?id=$id' method='post'><button id='btnSupprimer' type='submit'>Supprimer</button></form>";
    return $datas;
}

/**
 * Fonction permettant d'allé chercher un attribut spécifique dans un
 * ouvrage.
 * @param $attribute : l'attribut de l'ouvrage que l'on veut.
 * @param $ouvrage : l'ouvrage d'où on prend la donnée.
 * @return string|null : retourne l'attribut s'il est existant dans l'ouvrage,
 * sinon retourne null.
 */
function getAttribute($attribute, $ouvrage) {
    return $ouvrage[$attribute] ?? null;
}

/**
 * Fonction permettant de bâtir un message montrant que l'ouvrage a été modifié avec succès.
 * @return string : un message de succès de la modification, sinon retourne une chaîne vide.
 */
function showSuccessfulModifiedBookMsg() {
    return isset($_GET['modif']) ? "<h2 id='msgModification'>Ouvrage modifié avec succès.</h2>" : '';
}

//MAIN
$id = $_GET['id'];
$ouvrage = findById($id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Détails ouvrage</title>
    <style>
        #btnSupprimer {
            background-color: transparent;
            color: dodgerblue;
            border: none;

        }

        #msgModification {
            border: 1px solid lightgreen;
            background-color: lightgreen;
            border-radius: 6px;
            color: forestgreen;
            font-size: 16px;
            text-align: center;
            padding: 10px 0;
        }

        body table {
            font-size: 14px;
        }
    </style>
</head>
<body class="container">
    <header>
        <h1><?php echo getAttribute('titre', $ouvrage) ?? 'Aucun livre ne correspond à votre recherche' ?></h1>
        <?php echo showSuccessfulModifiedBookMsg(); ?>
    </header>
    <main>
        <section>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Sous Titre</th>
                        <th>Auteurs</th>
                        <th>Éditeurs</th>
                        <th>Édition</th>
                        <th>Année de publication</th>
                        <th>ISBN</th>
                        <th>Supports</th>
                        <th>Action</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php showBook($ouvrage); ?>
                </tbody>
                <tfoot></tfoot>
            </table>
        </section>
        <section>
            <form>
                <button formaction="index.php">Retourner à la bibliographie</button>
            </form>
        </section>
    </main>
    <footer></footer>
</body>
</html>
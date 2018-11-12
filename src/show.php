<?php
require 'ouvrages.php';
require 'utils.php';
use function Ouvrages\{findById};
use function Utils\{getSupports, showSupports};

function showBook($ouvrage) {
    if ($ouvrage) {
        $datas = getAttributes($ouvrage);
        showTableRow($datas);
    } else {
        echo "";
    }
}

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

function getAttribute($attribute, $ouvrage) {
    return $ouvrage[$attribute] ?? null;
}

function hideButton($ouvrage) {
    echo $ouvrage === null ? 'hidden' : '';
}

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
                <button formaction="index.php" <?php hideButton($ouvrage); ?>>Retourner à la bibliographie</button>
            </form>
        </section>
    </main>
    <footer></footer>
</body>
</html>
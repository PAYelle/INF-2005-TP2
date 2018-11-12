<?php
require 'ouvrages.php';
require 'utils.php';
use function Ouvrages\{findAll};
use function Utils\{getAttributes, showSupports};


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
    } else {
        echo "";
    }
}

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

function titleEditionFormatting($title, $edition, $tabEdition) {
    if ($edition != '') {
        return $title . ', ' . ($tabEdition[$edition] ?? $edition . 'th ed.');
    } else {
        return $title;
    }
}

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
        tbody td{
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
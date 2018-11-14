<?php
session_start();
require 'ouvrages.php';
require 'utils.php';
use function Ouvrages\{replace};
use function Utils\{numberFormatting, textFormatting, checkBoxFormatting, validateForm};

/**
 * Fonction permettant de modifier l'ouvrage dans la bd, de nettoyer
 * et vider les variables et de faire la redirection vers la page show.php.
 * @param $dataSource : l'information sur l'ouvrage a modifier.
 */
function validProtocol($dataSource) {
    $id = editBookDB($dataSource);
    unset($_SESSION['donnees']);
    unset($_SESSION['erreurs']);
    header("Location: show.php?id=$id&modif=success");
}

/**
 * Fonction permettant d'ajouter les erreurs dans la session courante et de
 * faire la redirection vers la page edit.php avec un attribut erreur à true.
 * @param $dataSource : les données entrées par l'utilisateur dans le formulaire d'update de l'ouvrage.
 * @param $errorSource : un tableau contenant les erreurs s'il y a lieu.
 */
function errorProtocol($dataSource, $errorSource) {
    $id = $_GET['id'];
    $_SESSION['donnees'] = $dataSource;
    $_SESSION['erreurs'] = $errorSource;
    header("Location: edit.php?id=$id&error=true");
}

/**
 * Fonction permettant d'éditer l'ouvrage dans la bd.
 * @param $dataSource : l'information utilisées pour modifier l'ouvrage.
 * @return mixed : retourne l'id de l'ouvrage.
 */
function editBookDB($dataSource) {
    $ouvrage['anneeParution'] = numberFormatting('anneeParution', $dataSource);
    $ouvrage['auteurs'] = textFormatting('auteurs', $dataSource);
    $ouvrage['editeur'] = textFormatting('editeur', $dataSource);
    $ouvrage['edition'] = numberFormatting('edition', $dataSource);
    $ouvrage['id'] = numberFormatting('id', $dataSource);
    $ouvrage['isbn'] = numberFormatting('isbn', $dataSource);
    $ouvrage['sousTitre'] = textFormatting('sousTitre', $dataSource);
    $ouvrage['supports'] = checkBoxFormatting($dataSource['supports']);
    $ouvrage['titre'] = textFormatting('titre', $dataSource);
    replace($dataSource['id'], $ouvrage);
    return $ouvrage['id'];
}

/**
 * Fonction permettant d'updater l'ouvrage dans la base de données ou
 * de renvoyer les erreurs du formulaire dans la session courante pour que
 * l'utilisateur puisse les corrigés.
 */
function update() {
    $dataSource = $_POST['donnees'];
    $errorTab = validateForm($dataSource);
    $dataSource['id'] = numberFormatting('id',$_REQUEST);
    empty($errorTab) ? validProtocol($dataSource) : errorProtocol($dataSource, $errorTab);
}

update();

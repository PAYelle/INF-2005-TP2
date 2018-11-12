<?php
session_start();
require 'ouvrages.php';
require 'utils.php';
use function Ouvrages\{create};
use function Utils\{numberFormatting, textFormatting, checkBoxFormatting, validateForm};

/**
 * Fonction permettant d'ajouter l'ouvrage dans la bd, de nettoyer, vider les variables
 * et de faire la redirection vers la page show.php si le formulaire est valide.
 */
function validProtocol() {
    $id = addBookDB($_SESSION['donnees']);
    unset($_SESSION['donnees']);
    unset($_SESSION['erreurs']);
    header("Location: show.php?id=$id");
}

/**
 * Fonction permettant d'ajouter les erreurs dans la session courante et de
 * faire la redirection vers la page create.php avec un attribut erreur à true.
 * @param $errorTab : tableau contenant les erreurs dans le formulaire.
 */
function errorProtocol($errorTab) {
    $_SESSION['erreurs'] = $errorTab;
    header("Location: create.php?error=true");
}

/**
 * Fonction permettant d'ajouter l'ouvrage dans la base de données en y formattant
 * les champs avant de les insérer dans la bd.
 * @param $dataSource : l'information sur l'ouvrage a ajouté.
 * @return mixed : retourne l'ouvrage ajouté dans la base de données.
 */
function addBookDB($dataSource) {
    $ouvrage = create([
                          'anneeParution' => numberFormatting('anneeParution', $dataSource),
                          'auteurs' => textFormatting('auteurs', $dataSource),
                          'editeur' => textFormatting('editeur', $dataSource),
                          'edition' => numberFormatting('edition', $dataSource),
                          'id' => '',
                          'isbn' => numberFormatting('isbn', $dataSource),
                          'sousTitre' => textFormatting('sousTitre', $dataSource),
                          'supports' => checkBoxFormatting($dataSource['supports']),
                          'titre' => textFormatting('titre', $dataSource)
                      ]);
    return $ouvrage;
}

/**
 * Fonction permettant de sauvegarder l'ouvrage dans la base de données ou
 * de renvoyer les erreurs du formulaire dans la session courante pour que
 * l'utilisateur puisse les corrigés.
 */
function save() {
    $_SESSION['donnees'] = $_POST['donnees'];
    $_SESSION['donnees']['supports'] = $_POST['donnees']['supports'] ?? '';
    $errorTab = validateForm($_SESSION['donnees']);
    empty($errorTab) ? validProtocol() : errorProtocol($errorTab);
}

save();

<?php
session_start();
require 'ouvrages.php';
require 'utils.php';
use function Ouvrages\{create};
use function Utils\{numberFormatting, textFormatting, checkBoxFormatting};

function validAttr($attribute, $value) {
    $validationRegex = function ($pattern, $value) {
        return preg_match($pattern, $value);
    };
    $supportsSelected = function ($supports) {
        return $supports != '' ? 1 : 0;
    };
    if ($attribute == 'titre' || $attribute == 'auteurs' || $attribute == 'editeur') {
        return $validationRegex('/^.+$/', $value);
    } else if ($attribute == 'sousTitre') {
        return $validationRegex('/^.*$/', $value);
    } else if ($attribute == 'edition') {
        return $validationRegex('/^[0-9]*$/', $value);
    } else if ($attribute == 'anneeParution') {
        return $validationRegex('/^[1-9][0-9]*$/', $value);
    } else if ($attribute == 'isbn') {
        return $validationRegex('/^(|[0-9]{10}$|^[0-9]{13})$/', $value);
    } else if ($attribute == 'supports') {
        return $supportsSelected($value);
    }
}

function validateForm($datas) {
    foreach ($datas as $attribute => $value) {
        if(!validAttr($attribute, $value))
        $errorTab[] = $attribute;
    }
    return $errorTab ?? [];
}

function validProtocol() {
    $id = addBookDB($_SESSION['donnees']);
    unset($_SESSION['donnees']);
    unset($_SESSION['erreurs']);
    header("Location: show.php?id=$id");
}

function errorProtocol($errorSource) {
    $_SESSION['erreurs'] = $errorSource;
    header("Location: create.php?error=true");
}

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

function save() {
    $_SESSION['donnees'] = $_POST['donnees'];
    $_SESSION['donnees']['supports'] = $_POST['donnees']['supports'] ?? '';
    $errorTab = validateForm($_SESSION['donnees']);
    empty($errorTab) ? validProtocol() : errorProtocol($errorTab);
}

save();

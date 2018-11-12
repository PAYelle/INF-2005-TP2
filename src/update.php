<?php
session_start();
require 'ouvrages.php';
require 'utils.php';
use function Ouvrages\{replace};
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
        if (!validAttr($attribute, $value))
            $errorTab[] = $attribute;
    }
    return $errorTab ?? [];
}

function validProtocol($dataSource) {
    $id = editBookDB($dataSource);
    unset($_SESSION['donnees']);
    unset($_SESSION['erreurs']);
    header("Location: show.php?id=$id&modif=success");
}

function errorProtocol($dataSource, $errorSource) {
    $id = $_GET['id'];
    $_SESSION['donnees'] = $dataSource;
    $_SESSION['erreurs'] = $errorSource;
    header("Location: edit.php?id=$id&error=true");
}

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

function update() {
    $dataSource = $_POST['donnees'];
    $errorTab = validateForm($dataSource);
    $dataSource['id'] = numberFormatting('id',$_REQUEST);
    empty($errorTab) ? validProtocol($dataSource) : errorProtocol($dataSource, $errorTab);
}

update();

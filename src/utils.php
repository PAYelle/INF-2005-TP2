<?php
namespace Utils;

function showValue($attribute, $dataSource) {
    return $dataSource[$attribute] ?? '';
}

function showError($attribute, $dataSource, $errorMessages) {
    return in_array($attribute, $dataSource) ? "<span class='error'>$errorMessages[$attribute]</span><br><br>" : '';
}

function showCheckBox($key, $value, $dataSource) {
    $supportExists = false;
    if (isset($dataSource['supports']) && !empty($dataSource['supports'])) {
        foreach ($dataSource['supports'] as $keySupport => $valueSupport) {
            if (preg_match("/^$key$/i", $keySupport) || preg_match("/^$key$/i", $valueSupport)) {
                $supportExists = true;
            }
        }
    }
    $checked = $supportExists ? 'checked' : '';
    return "<input id='supports' type='checkbox' name='donnees[supports][$value]' $checked>$value<br>";
}

function numberFormatting($attribute, $dataSource) {
    $data = sanitizeSting($attribute, $dataSource);
    return $data ?? null;
}

function textFormatting($attribute, $dataSource) {
    $data = sanitizeSting($attribute, $dataSource);
    return ucwords(strtolower($data)) ?? '';
}

function checkBoxFormatting($dataSource) {
    $keys = array_keys($dataSource);
    return array_map('strtolower', $keys);
}

function getAttributes($ouvrage) {
    $datas['id'] = getAttribute('id', $ouvrage);
    $datas['titre'] = getAttribute('titre', $ouvrage);
    $datas['edition'] = getAttribute('edition', $ouvrage);
    $datas['auteurs'] = getAttribute('auteurs', $ouvrage);
    $datas['supports'] = getSupports(getAttribute('supports', $ouvrage));
    return $datas;
}

function getAttribute($attribute, $ouvrage) {
    return $ouvrage[$attribute] ?? '';
}

function getSupports($tab) {
    $tabSupports = ['kindle' => 'Kindle', 'epub' => 'EPUB', 'papier' => 'Papier', 'pdf' => 'PDF'];
    foreach ($tab as $key => $value) {
        $support[] = $tabSupports[$value];
    }
    return $support ?? [];
}

function showSupports($supports) {
    $string = '';
    foreach ($supports as $key => $value) {
        $string = $string . $value . ', ';
    }
    return substr($string, 0, strlen($string) - 2);
}

function sanitizeSting($attribute, $dataSource) {
    return filter_var($dataSource[$attribute], FILTER_SANITIZE_STRING);
}
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

/**
 * Fonction permettant de valider si les champs du formulaire de l'ouvrage
 * sont valides. La validation se fait avec des expressions régulières.
 * @param $attribute : la clé contenant la valeur de chaque attribut.
 * @param $value : la valeur de l'attribut.
 * @return boolean|int : retourne vrai si la valeur de l'attribut est valide, sinon false
 * ou retourne 1 s'il y a des supports, sinon 0.
 */
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

/**
 * Fonction permettant de valider si le formulaire est valide.
 * @param $datas : l'information entrée par l'utilisateur dans le formulaire.
 * @return array : S'il y a des erreurs, retourne un tableau contenant les erreurs,
 * sinon retourne un tableau vide.
 */
function validateForm($datas) {
    foreach ($datas as $attribute => $value) {
        if(!validAttr($attribute, $value))
            $errorTab[] = $attribute;
    }
    return $errorTab ?? [];
}
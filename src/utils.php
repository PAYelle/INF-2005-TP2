<?php
namespace Utils;

/**
 * Fonction permettant de retourner l'attribut à afficher ou une chaîne vide s'il
 * n'y a rien à afficher.
 * @param $attribute : L'attribut à afficher.
 * @param $dataSource : La source de données d'où provient l'attribut.
 * @return string : retourne l'attribut, sinon une chaîne vide.
 */
function showValue($attribute, $dataSource) {
    return $dataSource[$attribute] ?? '';
}

/**Fonction permettant d'afficher les erreurs dans le formulaire.
 * @param $attribute : l'attribut que l'on veut savoir s'il est en erreur.
 * @param $dataSource : la source de données où est l'information sur l'attribut.
 * @param $errorMessages : Le tableau de messages d'erreur.
 * @return string : retourne le message d'erreur dans le formulaire s'il y a erreur,
 * sinon retourne une chaîne vide.
 */
function showError($attribute, $dataSource, $errorMessages) {
    return in_array($attribute, $dataSource) ? "<span class='error'>$errorMessages[$attribute]</span><br><br>" : '';
}

/**
 * Fonction permettant d'afficher les checkbox pour les supports.
 * @param $key : la clé du tableau de supports.
 * @param $value : la valeur relié à la clé du tableau de supports.
 * @param $dataSource : la source de données on se retrouve l'information
 * @return string : retourne les checkbox cochés s'il y a lieu.
 */
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
    return "<input class='supports' type='checkbox' name='donnees[supports][$value]' $checked>$value<br>";
}

/**
 * Fonction permettant de formatter toutes données numériques.
 * @param $attribute : l'attribut que l'on veut formatter.
 * @param $dataSource : la source de données d'où provient l'attribut.
 * @return mixed|null : retourne l'attribut formatté, sinon null.
 */
function numberFormatting($attribute, $dataSource) {
    $data = sanitizeSting($attribute, $dataSource);
    return $data ?? null;
}

/**
 * Fonction permettant de formatter du texte.
 * @param $attribute : l'attribut que l'on veut formatter.
 * @param $dataSource : la source de données d'où provient l'attribut.
 * @return string : retourne l'attribut formatté, sinon une chaîne vide.
 */
function textFormatting($attribute, $dataSource) {
    $data = sanitizeSting($attribute, $dataSource);
    return ucwords(strtolower($data)) ?? '';
}

/**
 * Fonction permettant de formatter le nom des checkbox.
 * @param $dataSource : la source d'où provient les supports.
 * @return array : retourne un tableau contenant les supports formattés.
 */
function checkBoxFormatting($dataSource) {
    $keys = array_keys($dataSource);
    return array_map('strtolower', $keys);
}

/**
 * Fonction permettant d'allé chercher tous les attributs de l'ouvrage.
 * @param $ouvrage : l'ouvrage d'où on veut allé chercher l'information.
 * @return mixed : retourne un tableau avec les attributs.
 */
function getAttributes($ouvrage) {
    $datas['id'] = getAttribute('id', $ouvrage);
    $datas['titre'] = getAttribute('titre', $ouvrage);
    $datas['edition'] = getAttribute('edition', $ouvrage);
    $datas['auteurs'] = getAttribute('auteurs', $ouvrage);
    $datas['supports'] = getSupports(getAttribute('supports', $ouvrage));
    return $datas;
}

/**
 * Fonction permettant d'allé chercher un attribut spécifique.
 * @param $attribute : l'attribut que l'on veut.
 * @param $ouvrage : l'ouvrage d'où est pris l'information sur l'attribut.
 * @return string : retoune l'attribut, sinon une chaîne vide.
 */
function getAttribute($attribute, $ouvrage) {
    return $ouvrage[$attribute] ?? '';
}

/**
 * Fonction permettant d'allé chercher les supports.
 * @param $tab : le tableau contenant les données sur les supports sélectionnés par l'utilisateur.
 * @return array : retourne un tableau contenant les supports, sinon un tableau vide.
 */
function getSupports($tab) {
    $tabSupports = ['kindle' => 'Kindle', 'epub' => 'EPUB', 'papier' => 'Papier', 'pdf' => 'PDF'];
    foreach ($tab as $key => $value) {
        $support[] = $tabSupports[$value];
    }
    return $support ?? [];
}

/**
 * Fonction permettant d'afficher les supports.
 * @param $supports : le tableau contenant les supports.
 * @return string : retourne une string formatté pour afficher les supports
 * dans la bibliographie (index.html).
 */
function showSupports($supports) {
    $string = '';
    foreach ($supports as $key => $value) {
        $string = $string . $value . ', ';
    }
    return substr($string, 0, strlen($string) - 2);
}

/**
 * Fonction permettant de nettoyer les strings.
 * @param $attribute : l'attribut à nettoyer.
 * @param $dataSource : la source de données d'où provient l'attribut.
 * @return mixed : retourne la string nettoyée.
 */
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
        return preg_match($pattern, trim($value));
    };
    $supportsSelected = function ($supports) {
        return $supports != '' ? 1 : 0;
    };
    if ($attribute == 'titre' || $attribute == 'auteurs' || $attribute == 'editeur') {
        return $validationRegex('/^.+$/', $value);
    } else if ($attribute == 'sousTitre') {
        return $validationRegex('/^.*$/', $value);
    } else if ($attribute == 'edition') {
        return $validationRegex('/^(1|[2-9][0-9]*)*$/', $value);
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
        if (!validAttr($attribute, $value))
            $errorTab[] = $attribute;
    }
    return $errorTab ?? [];
}
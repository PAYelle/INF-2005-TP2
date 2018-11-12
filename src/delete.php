<?php
require 'ouvrages.php';
use function Ouvrages\{removeById};

/**
 * Fonction permettant de supprimer un ouvrage de la base de données.
 */
function delete() {
    removeById($_REQUEST['id']);
    header('Location: index.php?delete=success');
}

delete();
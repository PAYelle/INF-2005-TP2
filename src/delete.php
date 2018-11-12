<?php
require 'ouvrages.php';
use function Ouvrages\{removeById};

function delete() {
    print_r($_REQUEST['id']);
    removeById($_REQUEST['id']);
    header('Location: index.php?delete=success');
}

delete();
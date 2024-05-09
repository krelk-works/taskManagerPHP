<?php
// YAML -----------------------------------
require_once 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
use Symfony\Component\Yaml\Yaml;
// ----------------------------------------

$errors_directory = 'data';
$errors_file = 'data'.DIRECTORY_SEPARATOR.'errors.yaml';
$errors = array();

// Comprovem si existeix la carpeta DATA sino la creem.
if (!file_exists($errors_directory)) {
    mkdir($errors_directory, 0777, true);
}

// Comprovem si existeix l'arixu de errors sino el creem.
if (!file_exists($errors_file)) {
    touch($errors_file);
}

// Comprovem si tenim dades a l'arxiu yaml
try {
    global $errors;
    $errors = Yaml::parseFile($errors_file);
    if ($errors == null) {
        saywarn("No hi han dades d'informació d'errors a l'arxiu d'errors.");
    }
} catch (Exception $e) {
    saydie("S'ha produït un error al intent de apertura de l'arxiu YAML d'errors.");
}


?>
<?php
// YAML -----------------------------------
require_once 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
use Symfony\Component\Yaml\Yaml;
// ----------------------------------------

$messages_directory = 'data';
$messages_file = 'data'.DIRECTORY_SEPARATOR.'messages.yaml';
$messages = array();

// Comprovem si existeix la carpeta DATA sino la creem.
if (!file_exists($messages_directory)) {
    mkdir($messages_directory, 0777, true);
}

// Comprovem si existeix l'arixu de errors sino el creem.
if (!file_exists($messages_file)) {
    touch($messages_file);
}

// Comprovem si tenim dades a l'arxiu yaml
try {
    global $messages;
    $messages = Yaml::parseFile($messages_file);
    if ($messages == null) {
        saywarn("No hi han dades d'informació de missatges.");
    }
} catch (Exception $e) {
    saydie("S'ha produït un error al intent de apertura de l'arxiu YAML de missatges.");
}

function get_message($id) {
    global $messages;
    foreach ($messages as $key => $value) {
        if ($key == $id){
            return $value;
        }
    }
    return "Missatge no identificat";
}

?>
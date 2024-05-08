<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$errorMessages = Yaml::parseFile('errors.yaml');

$config_file = '~/.config/task-manager.json';

$config = false;

if (file_exists($config_file)) {
    try {
        $config = file_get_contents($config_file);
    } catch (Exception $e) {
        echo '[!] Error inesperat a l`arxiu de configuració: ',  $e->getMessage(), "\n";
    }
} else {
    // Creem l'arxiu en cas que no existeix
    touch($config_file);

    // Iniciem la configuració del nostre programa [ on enmmagatzenarem les nostres tasques ]
    setup();
}

function setup() {
    //Començem a configurar els paràmetres del nostre programa.
    echo "\n[WARNING] Falta la configuració del tipus d'emmagatzematge a utilitzar per al programa";
    echo "\n===================================================";
    echo "\nSelecciona un tipus d'enmmagatzematge per a fer server: [1: MySQL | 2:JSON | 3: Cancelar operació]\n";
    $option = readline("Selecció: ");
    if ($option != "1" or $option != "2" or $option = "3") {
        echo $errorMessages["invalid_save_option"];
    }
}

?>
<?php
// Declarem el tipus de slash que s'utilitzarà segons el S.O.
$DS = DIRECTORY_SEPARATOR;

// YAML -----------------------------------
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
// ----------------------------------------

// Variables d'entorn necessaries ---------

$user_name = getenv("USER");
$os = strtoupper(substr(PHP_OS, 0, 3)); // WIN o LIN
$user_directory = false;
if ($os == "WIN") {
    $user_directory = "C:".$DS."Users".$DS.$user_name.$DS;
} elseif ($os == "LIN") {
    $user_directory = $DS."home".$DS.$user_name.$DS;
}

// ----------------------------------------

// Variables ------------------------------
$errors_data_file = "data".$DS."errors.yaml";
$errors = false;
$tasks_data_file = $user_directory.".config".$DS."task-manager.json";
$config_file = "data".$DS."config.yaml";
$config = false;
// ----------------------------------------

if (file_exists($errors_data_file)) {
    try {
        // Fem una asignació de variable pasant l'arxiu de yaml per a poder-ho interpretar
        $errors = Yaml::parseFile('data/errors.yaml');
    } catch (Exception $e) {
        // Agafem posibles errores que hi puguin apareixer i sortim del programa.
        echo '\n[!] Error inesperat amb l`arxiu de errors: ',  $e->getMessage(), "\n";
        die("[!] Programa finalitzat : error critic.");
    }
} else {
    // Creem l'arxiu de errors encara que no tinguem res a dins
    touch($errors_data_file);
}

function ensure_config_file(){
    if (file_exists($config_file)) {
        try {
            $config = file_get_contents($config_file);
        } catch (Exception $e) {
            echo '\n[!] Error inesperat a l`arxiu de configuració: ',  $e->getMessage(), "\n";
            die("[!] Programa finalitzat : error critic.");
        }
    } else {
        // Creem l'arxiu en cas que no existeix
        touch($config_file);
    
        // Iniciem la configuració del nostre programa [ on enmmagatzenarem les nostres tasques ]
        setup();
    }
}

function ensure_json_file() {
    if (!file_exists($tasks_data_file)) {
        // Creem l'arxiu en cas que no existeix per a desar dades
        touch($tasks_data_file);
    }
}
?>
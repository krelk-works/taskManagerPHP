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
$config_file = $user_directory.".config".$DS."task-manager.yaml";
$config = false;
// ----------------------------------------

function ensure_config_file() {
    global $config_file;
    global $config;
    global $errors_data_file;
    global $tasks_data_file;
    if (file_exists($config_file)) {
        try {
            // Creem una variable temporal per a poder verificar que la configuració esta bé
            $temporal_config = Yaml::parseFile($config_file);
    
            // Aprofitem i aqui mateix comprovem si les variables que hem agafat del config son valides en cas de ser del tipus MySQL o JSON
            if (!empty($temporal_config["storage-type"])) {
                if ($temporal_config["storage-type"] == "mysql") {
                    if (empty($temporal_config["database"]["host"]) or empty($temporal_config["database"]["user"]) or empty($temporal_config["database"]["db"])){
                        $config = false;
                    } else {
                        $config = $temporal_config;
                    }
                } elseif ($temporal_config["storage-type"] == "json") {
                    if (file_exists($tasks_data_file)) {
                        $config = $temporal_config;
                    }
                }
            }
        } catch (Exception $e) {
            echo '\n[!] Error inesperat a l`arxiu de configuració: ',  $e->getMessage(), "\n";
            die("[!] Programa finalitzat : error critic.");
        }
    } else {
        // Creem l'arxiu en cas que no existeix
        touch($config_file);
    }

    // Comprovem també que l'arxiu d'errors estigui creat
    if (!file_exists($errors_data_file)) {
        die("\nError critic : No s'ha pogut localitzar l'arxiu d'informació d'erros. Programa finalitzat\n");
    }
}


function ensure_json_file() {
    global $tasks_data_file;
    if (!file_exists($tasks_data_file)) {
        // Creem l'arxiu en cas que no existeix per a desar dades
        touch($tasks_data_file);
    }
}
?>
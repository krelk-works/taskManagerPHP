<?php
include_once("file_controller.php");

// YAML -----------------------------------
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
// ----------------------------------------

function setup() {
    // Declarem globals per a poder-hi accedir després als missatges d'errors
    global $errors;

    // Començem a configurar els paràmetres del nostre programa.
    echo "\n[WARNING] Falta la configuració del tipus d'emmagatzematge a utilitzar per al programa";
    echo "\n===================================================";
    echo "\nSelecciona un tipus d'enmmagatzematge per a fer server: [1: MySQL | 2:JSON | 3: Cancelar operació]\n";
    $option = readline("Selecció: ");
    
    // Comprovem l'opció inserida per l'usuari
    if ($option != "1" and $option != "2" and $option != "3") {
        //Salt de linia amb missatge d'error
        echo "\n", $errors['invalid_save_option'];
    } else {
        switch($option){
            case "1":
                global $config_file;
                $new_config;
                // --------------------------------------------------------------------------
                echo "\nHas seleccionar el tipus MySQL, introdueix les dades de connexió:\n";
                // --------------------------------------------------------------------------
                $new_config["storage-tye"] = "mysql";
                // --------------------------------------------------------------------------
                $new_config["database"]["host"] = readline("\n[] IP/HOST: ");
                $new_config["database"]["user"] = readline("\n[] USER: ");
                $new_config["database"]["password"] = readline("\n[] PASSWORD: ");
                $new_config["database"]["db"] = readline("\n[] DATABASE NAME: ");
                // Comprovem la connexió amb la base de dades abans de desar la configuració
                if (check_connection($new_config["database"]["host"], $new_config["database"]["user"], $new_config["database"]["password"], $new_config["database"]["db"]))
                {
                    // Creem el nou arxiu que tindra la configuració
                    ensure_config_file();
                    // Declarem el format yaml del nostre array $new_config
                    $new_config = Yaml::dump($new_config);
                    // Inserim les dades en format Yaml al nostre arxiu de configuració
                    file_put_contents($config_file, $new_config);
                    // ----------------------------------------------------------------
                    echo "\n[OK] Configuració desada amb exit.";
                } else {
                    echo "\n[!] No s'ha pogut establir una connexió amb la base de dades, prova-ho un altre cop.\n";
                    echo "\n\n";
                    setup();
                }
                break;
            case "2":
                ensure_json_file();
                break;
            default:
                echo "Adiooooosss.";
                break;
        }
    }
}
?>
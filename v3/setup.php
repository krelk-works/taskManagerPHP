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
    echo "[!] Tens que configurar el programa amb el tipus de enmmagatzematge a utilitzar";
    echo "\n=============================================================================\n";
    echo "\nSelecciona un tipus d'enmmagatzematge per a fer servir: [1: MySQL | 2:JSON | 3: Cancelar operació]\n";
    $option = readline("Selecció: ");
    
    // Comprovem l'opció inserida per l'usuari
    if ($option != "1" and $option != "2" and $option != "3") {
        //Salt de linia amb missatge d'error
        echo "\n", $errors['invalid_save_option'], "\n";
    } else {
        switch($option){
            case "1":
                global $config_file;
                $new_config = [];
                // --------------------------------------------------------------------------
                echo "\nHas seleccionar el tipus MySQL, introdueix les dades de connexió:\n";
                // --------------------------------------------------------------------------
                $new_config["storage-type"] = "mysql";
                // --------------------------------------------------------------------------
                $new_config["database"]["host"] = readline("\n[] IP/HOST: ");
                $new_config["database"]["user"] = readline("\n[] USER: ");
                //$new_config["database"]["password"] = readline("\n[] PASSWORD: ");
                $new_config["database"]["password"] = prompt_silent();
                $new_config["database"]["db"] = readline("\n[] DATABASE NAME: ");
                // Comprovem la connexió amb la base de dades abans de desar la configuració | Posem @ davant la funció per a suprimir els warnings de direccions IP no valides
                if (@check_connection($new_config["database"]["host"], $new_config["database"]["user"], $new_config["database"]["password"], $new_config["database"]["db"]))
                {
                    echo "\n[OK] Conexió realitzada amb exit a BBDD.\n";
                    // Declarem el format yaml del nostre array $new_config
                    $new_config = Yaml::dump($new_config);
                    // Inserim les dades en format Yaml al nostre arxiu de configuració
                    file_put_contents($config_file, $new_config);
                    // ----------------------------------------------------------------
                    echo "\n[OK] Configuració desada amb exit.\n";
                } else {
                    // En cas que no funcioni la conexió amb la base de dades tornem a llançar el setup() per a que l'usuari torni a posar dades.
                    echo "\n[!] No s'ha pogut establir una connexió amb la base de dades, prova-ho un altre cop.\n";
                    echo "\n\n";

                    // Tornem a llançar la configuració per a que l'usuari torni a inserir les dades novament
                    setup();
                }
                break;
            case "2":
                global $config_file;
                // Declarem un diccionari de dades
                $new_config = [];

                // Li asignem el tipus de enmmagatzematge
                $new_config["storage-type"] = "json";

                //Transormem el diccionari a YAML
                $new_config = Yaml::dump($new_config);

                // Creem l'arxiu json
                ensure_json_file();

                // Desem el diccionari en format YAML a l'arxiu de configuració
                file_put_contents($config_file, $new_config);
                echo "\n[OK] Configuració desada amb exit.\n";
                break;
            default:
                //echo "Programa finalitzat\n";
                break;
        }
    }
}

// Funció per a poder inserir contrasenyas de forma SEGURA per la terminal
function prompt_silent($prompt = "[] PASSWORD: ") {
    if (preg_match('/^win/i', PHP_OS)) {
        $vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
        file_put_contents($vbscript, 'wscript.echo(InputBox("'. addslashes($prompt). '", "", "password here"))');
        $command = "cscript //nologo " . escapeshellarg($vbscript);
        $password = rtrim(shell_exec($command));
        unlink($vbscript);
        return $password;
    } else {
        $command = "/usr/bin/env bash -c 'echo OK'";
        if (rtrim(shell_exec($command)) !== 'OK') {
            trigger_error("Can't invoke bash");
            return;
        }
        $command = "/usr/bin/env bash -c 'read -s -p \"". addslashes($prompt). "\" mypassword && echo \$mypassword'";
        $password = rtrim(shell_exec($command));
        echo "\n";
        return $password;
    }
}
function check_connection($host, $user, $pass, $db) {
    try {
        $connection=new mysqli($host,$user,$pass,$db);
        $connection->close();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
<?php
// YAML -----------------------------------
require_once 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
use Symfony\Component\Yaml\Yaml;
// ----------------------------------------

// Variables d'entorn necessaries ---------
$user_name = getenv("USER");
$os = strtoupper(substr(PHP_OS, 0, 3)); // WIN o LIN
$user_directory = false;
if ($os == "WIN") {
    $user_directory = "C:".DIRECTORY_SEPARATOR."Users".DIRECTORY_SEPARATOR.$user_name.DIRECTORY_SEPARATOR;
} elseif ($os == "LIN") {
    $user_directory = DIRECTORY_SEPARATOR."home".DIRECTORY_SEPARATOR.$user_name.DIRECTORY_SEPARATOR;
}
// ----------------------------------------

// Variables ------------------------------
$config_directory = $user_directory.".config";
$config_file = $user_directory.".config".DIRECTORY_SEPARATOR."task-manager.yaml";
$config = [];

// Comprovem si existeix la carpeta .config sino la creem.
if (!file_exists($config_directory)) {
    mkdir($config_directory, 0777, true);
}

if (!file_exists($config_file)) {
    setup();
}

function setup() {
    // Declarem globals per a poder-hi accedir després als missatges d'errors
    global $errors;

    // Començem a configurar els paràmetres del nostre programa.
    say("Tens que configurar el programa amb el tipus de enmmagatzematge a utilitzar");
    line();
    say("Selecciona un tipus d'enmmagatzematge per a fer servir: [1: MySQL | 2:JSON | 3: Cancelar operació]");
    $option = readline("Selecció: ");
    
    // Comprovem l'opció inserida per l'usuari
    if ($option != "1" and $option != "2" and $option != "3") {
        // Missatge d'error
        sayerror(get_message("tipus_enmm_no_valid"));
    } else {
        switch($option){
            case "1":
                global $config_file;
                $new_config = [];
                // --------------------------------------------------------------------------
                say(get_message("database_connection"));
                line();
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
                    // Declarem el format yaml del nostre array $new_config
                    $new_config = Yaml::dump($new_config);
                    // Inserim les dades en format Yaml al nostre arxiu de configuració
                    file_put_contents($config_file, $new_config);
                    // ----------------------------------------------------------------
                    sayok(get_message("write_config_ok"));
                    saydie("Programa finalitzat, torna-ho a executar per poder realitzar accions.");
                } else {
                    // En cas que no funcioni la conexió amb la base de dades tornem a llançar el setup() per a que l'usuari torni a posar dades.
                    sayerror(get_message("mysql_connection_error"));

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
                // Desem el diccionari en format YAML a l'arxiu de configuració
                file_put_contents($config_file, $new_config);
                // ----------------------------------------------------------------
                sayok(get_message("write_config_ok"));
                break;
            default:
                saymsg(get_message("exit_app"));
                break;
        }
    }
}

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
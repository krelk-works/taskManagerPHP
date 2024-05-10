<?php
// YAML -----------------------------------
require_once 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
use Symfony\Component\Yaml\Yaml;
// ----------------------------------------

if (file_exists($config_file)) {
    global $config;
    try {
        // Creem una variable temporal per a poder verificar que la configuració esta bé
        $temporal_config = Yaml::parseFile($config_file);
        // Comprovem si es del tipus mysql o json y asignem a la variable $config la nostra configuració
        if ($temporal_config["storage-type"] == "mysql" or $temporal_config["storage-type"] == "json") {
            $config = $temporal_config;
        }
    } catch (Exception $e) {
        saydie(get_message("error_config_file"));
    }
}
?>
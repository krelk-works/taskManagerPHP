<?php

include_once "sql_connection.php";
include_once "setup.php";

if (php_sapi_name() != "cli") {
    die("\nError: Only works on CLI environment\n");
}

// Verifiquem l'arxiu de configuració
ensure_config_file();

$options = getopt('c:l::f:d:n:r:', ['create:', 'list::', 'finish:', 'remove:', 'name:', 'description:']);

if (!$config) {
    // Iniciem la configuració del nostre programa [ on enmmagatzenarem les nostres tasques ]
    echo "\nFalta la configuració\n";
    setup();
    return;
}

if (empty($options) and $config) {
    showHelp();
} elseif (!empty($options) and $config) {
    if ((array_key_exists('c', $options) and array_key_exists('d', $options))){
        if ($config["storage-type"] == "mysql") {
            addTask($options["c"], $options["d"]);
        } elseif ($config["storage-type"] == "json") {

        }
    } elseif ((array_key_exists('create', $options) and array_key_exists('description', $options))) {
        if ($config["storage-type"] == "mysql") {
            addTask($options["create"], $options["description"]);
        } elseif ($config["storage-type"] == "json") {

        }
    } elseif (array_key_exists('l', $options) or array_key_exists('list', $options)){
        if ($config["storage-type"] == "mysql") {
            showTasks();
        } elseif ($config["storage-type"] == "json") {

        }
    } elseif (array_key_exists('f', $options)) {
        if ($config["storage-type"] == "mysql") {
            finishTask($options["f"]);
        } elseif ($config["storage-type"] == "json") {

        }
    } elseif(array_key_exists('finish', $options)) {
        if ($config["storage-type"] == "mysql") {
            finishTask($options["finish"]);
        } elseif ($config["storage-type"] == "json") {

        }
    } elseif (array_key_exists('r', $options) or array_key_exists('remove', $options)) {
        if ($config["storage-type"] == "mysql") {
            deleteTask($options["r"]);
        } elseif ($config["storage-type"] == "json") {

        }
    } elseif(array_key_exists('remove', $options)) {
        if ($config["storage-type"] == "mysql") {
            deleteTask($options["remove"]);
        } elseif ($config["storage-type"] == "json") {

        }
    }
}

function showHelp() {
    echo "\n";
    echo "ERROR : taskManager - Syntax error\n";
    echo "==================================\n";
    echo "\n";
    echo "-> To CREATE a new task:\n";
    echo "        -c | --create [task name | string] && -d '[string]' | --description '[task description | string]'\n";
    echo "\n";
    echo "-> To LIST all the tasks:\n";
    echo "        -l | --list\n";
    echo "\n";
    echo "-> To FINISH an task:\n";
    echo "        -f | --finish [task ID / int]\n";
    echo "\n";
    echo "-> To REMOVE an task:\n";
    echo "        -r | --remove [task ID / int]\n";
}
?>
<?php

include_once "sql_connection.php";
include_once "config_checker.php";

if (php_sapi_name() != "cli") {
    die("\nError: Only works on CLI environment\n");
}

$options = getopt('c:l::f:d:n:r:', ['create:', 'list::', 'finish:', 'remove:', 'name:', 'description:']);

if (empty($options)) {
    if (!$config) {
        echo "\nNo s'ha realitzat la configuració del programa";
    }
    //showHelp();
} else {
    if ((array_key_exists('c', $options) and array_key_exists('d', $options))){
        addTask($options["c"], $options["d"]);
    } elseif ((array_key_exists('create', $options) and array_key_exists('description', $options))) {
        addTask($options["create"], $options["description"]);
    } elseif (array_key_exists('l', $options) or array_key_exists('list', $options)){
        showTasks();
    } elseif (array_key_exists('f', $options)) {
        finishTask($options["f"]);
    } elseif(array_key_exists('finish', $options)) {
        finishTask($options["finish"]);
    } elseif (array_key_exists('r', $options) or array_key_exists('remove', $options)) {
        deleteTask($options["r"]);
    } elseif(array_key_exists('remove', $options)) {
        deleteTask($options["remove"]);
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




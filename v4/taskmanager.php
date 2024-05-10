<?php

include_once("includes/extras.php");
include_once("includes/messages.php");
include_once("includes/setup.php");
include_once("includes/config_initializer.php");
include_once("includes/mysql_connection.php");
include_once("includes/json_controller.php");
include_once("includes/cli_interpreter.php");

if (php_sapi_name() != "cli") {
    saydie(get_message("only_works_on_cli"));
}

$options = getopt('c:l::f:d:n:r:h::', ['create:', 'list::', 'finish:', 'remove:', 'name:', 'description:', 'help::']);

if ($config != null) {
    intelliexec($options);
}

?>
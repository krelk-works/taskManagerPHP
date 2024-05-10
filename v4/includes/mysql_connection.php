<?php

// Verifiquem que la nostra configuració esta amb MySQL
if ($config["storage-type"] != "mysql" or $config == null) {
    return;
}

?>
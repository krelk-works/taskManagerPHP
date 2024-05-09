<?php

function say($text) { echo "$text\n"; };
function line() { echo "===================================\n"; }

function saytask($name, $description, $id){
    line();
    say("[o] Identificador: $id");
    say("[o] Nom de la tasca: $name");
    say("[o] Descripció: $description");
    line();
    say("");
}

function saywarn($text) { echo "[?] $text\n"; };
function saydie($text) { echo "[!] $text\n"; die(); };

?>
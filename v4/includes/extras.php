<?php

function say($text) { echo "$text\n"; };
function line() { echo "===================================\n"; }

function saytask($name, $description, $id, $estat){
    line();
    say("[o] Identificador: $id");
    say("[o] Nom de la tasca: $name");
    say("[o] Descripció: $description");
    say("[o] Estat: $estat");
    line();
    say("");
}
function saywarn($text) { echo "[?] $text\n"; };
function saydie($text) { echo "[**] $text\n"; die(); };
function saymsg($text) { echo "[o] $text\n"; };

function sayerror($text) { echo "[!] $text\n"; };
function sayok($text) { echo "[OK] $text\n"; }
function saysintax() {
    line();
    say("Aqui teniu la forma de poder utilitzar la aplicació de taskmanager v4.");
    line();
    say("");
    say("-> Per CREAR una nova tasca:");
    say("        -c | --create [nom de la tasca | string] -d '[string]' | --description '[descripcio de la tasca | string]'");
    say("");
    say("-> Per LLISTAR totes les tasques:");
    say("        -l | --list");
    say("");
    say("-> Per FINALITZAR una tasca:");
    say("        -f | --finish [task ID / int]");
    say("");
    say("-> Per ELIMINAR una tasca:");
    say("        -r | --remove [task ID / int]");
    line();
}

function special_chars($str) {
    return preg_match('/[^a-zA-Z0-9]/', $str) > 0;
}

?>
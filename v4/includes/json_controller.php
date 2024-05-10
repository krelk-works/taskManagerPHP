<?php

// Verifiquem que la nostra configuració esta amb JSON
if ($config["storage-type"] != "json" or $config == null) {
    return;
}

// Variables
$tasks_data_file = $user_directory.".config".DIRECTORY_SEPARATOR."task-manager.json";
$json_tasks = array();

// Verifiquem que l'arxiu JSON existeix al nostre sistema
if(!file_exists($tasks_data_file)) {
    // Si no existeix el creem
    file_put_contents($tasks_data_file, "{}");
} else {
    try {
        // Asignem les tasques al array. (el true servei per a convertir-ho a array)
        $json_tasks = json_decode(file_get_contents($tasks_data_file), true);
        if ($json_tasks == null) {
            $json_tasks = array();
        }
    } catch (Exception $e) {
        // Sortim del programa en cas que no es pugui carregar
        saydie(get_message("json_file_error_open"));
    }
}

// Funcions
function create_task($name, $description) {
    if (task_name_exist($name)) {
        saydie(get_message("task_already_exists"));
    }
    
    global $json_tasks;

    // El nom de la tasca no pot ser mes gran de 30 caracters
    if (strlen($name) > 30) {
        saydie(get_message("task_name_too_long"));
    }

    // La descripció de la tasca no pot ser major a 150 caracters
    if (strlen($name) > 150) {
        saydie(get_message("desc_too_long"));
    }

    // Afegim el nou element a la nostra variable de tasques.
    array_push($json_tasks, ["name" => $name, "description" => $description, "status" => "No finalitzada"]);

    // Missatge de OK
    sayok(get_message("new_task_added").$name);

    // Desem el canvis fets
    json_save();
}

function list_tasks() {
    global $json_tasks;
    $count = 0;
    foreach($json_tasks as $task) {
        saytask($task["name"], $task["description"], $count, $task["status"]);
        $count++;
    }
    if ($count == 0) {
        saydie(get_message("no_tasks"));
    }
}

function remove_task($id) {
    global $json_tasks;
    if (array_key_exists($id, $json_tasks)) {
        array_splice($json_tasks, $id, 1);
        sayok(get_message("deleted_task").$id);
        json_save();
    } else {
        saydie(get_message("task_not_found").$id);
    }
}

function finish_task($id) {
    global $json_tasks;
    if (array_key_exists($id, $json_tasks)) {
        if ($json_tasks[$id]["status"] != "Finalitzada") {
            $json_tasks[$id]["status"] = "Finalitzada";
            // Missatge de OK
            sayok(get_message("task_done").$id);
            // Desem les dades
            json_save();
        } else {
            saydie(get_message("task_already_done").$id);
        }
    } else {
        saydie(get_message("task_not_found").$id);
    }
}


function task_name_exist($name) {
    global $json_tasks;
    foreach($json_tasks as $task) {
        if ($task["name"] == $name) {
            return true;
        }
    }
    return false;
}

function json_save() {
    global $json_tasks;
    global $tasks_data_file;
    $json_encoded = json_encode($json_tasks);
    file_put_contents($tasks_data_file, $json_encoded);
}
?>
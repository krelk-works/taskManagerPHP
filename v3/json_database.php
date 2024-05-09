<?php
/*
Name
Description
Status

Create
List All
Remove
Finish
*/

function json_create_task($name, $description) {
    if (json_name_exist($name)) {
        echo "[!] El nom de la tasca ja existeix\n";
        return;
    }
    
    global $json_data;
    echo "\n";

    if (strlen($name) > 30) {
        echo "[!] Nom de la tasca massa llarg : Maxim 30 caracters\n";
        addTask();
    } else if ($name == "c") {
        echo "\n\n";
        return;
    }

    if (empty($name)) {
        echo "[!] El nom no pot estar buit\n";
        addTask();
    }

    if (strlen($name) > 150) {
        echo "[!] Descripció massa llarga : Maxim 150 caracters\n";
        addTask();
    } else if ($name == "c") {
        echo "\n\n";
        return;
    }

    if (empty($description)) {
        echo "[!] La descripció no pot estar buida\n";
        addTask();
    }

    // Afegim el nou element a la nostra variable de tasques.
    array_push($json_data, ["name" => $name, "description" => $description, "status" => "Not finished"]);

    // Desem el canvis fets
    json_save();

    // Missatge de OK
    echo "[OK] Has afegit una nova tasca : $name\n";
}
function json_list_all_tasks() {
    global $json_data;
    $count = 0;
    foreach($json_data as $task) {
        echo "\n";
	    echo "==================================== TASK (",$count,")\n";
	    echo "Name: ",$task["name"],"\n";
	    echo "Description: ",$task["description"], "\n";
        echo "Status: ",$task["status"],"\n";
	    echo "====================================\n\n";
        $count++;
    }
}
function json_remove_task($id) {
    global $json_data;
    if (array_key_exists($id, $json_data)) {
        array_splice($json_data, $id, 1);
        if (!json_task_exist($id)) {
            // Missatge de OK
            echo "[OK] Tasca eliminada [$id]\n";
            json_save();
        }
    }
}
function json_finish_task($id) {
    global $json_data;
    if (array_key_exists($id, $json_data)) {
        if ($json_data[$id]["status"] != "Finished") {
            $json_data[$id]["status"] = "Finished";
            // Missatge de OK
            echo "[OK] Tasca finalitzada [$id]\n";
            json_save();
        } else {
            echo "[!] La tasca ja esta finalitzada [$id]\n";
        }
    }
} 
function json_task_exist($id) {
    global $json_data;
    if (array_key_exists($id, $json_data)) {
        return true;
    } else {
        return false;
    }
}
function json_name_exist($name) {
    global $json_data;
    foreach($json_data as $task) {
        if ($task["name"] == $name) {
            return true;
        }
    }
    return false;
}
function json_save() {
    global $json_data;
    global $tasks_data_file;
    $json_encoded = json_encode($json_data);
    file_put_contents($tasks_data_file, $json_encoded);
}

?>
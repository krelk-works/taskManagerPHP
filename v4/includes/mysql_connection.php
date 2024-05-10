<?php
if (!@check_connection($config["database"]["host"], $config["database"]["user"], $config["database"]["password"], $config["database"]["db"])){
    saydie(get_message("mysql_not_connect"));
}

function create_task($name, $description) {
    if (task_name_exist($name)) {
        saydie(get_message("task_already_exists"));
    }
    
    // El nom de la tasca no pot ser mes gran de 30 caracters
    if (strlen($name) > 30) {
        saydie(get_message("task_name_too_long"));
    }

    // La descripció de la tasca no pot ser major a 150 caracters
    if (strlen($name) > 150) {
        saydie(get_message("desc_too_long"));
    }

    // Netejem el format del text
    $name_sani = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (filter_var($name_sani, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $description_sani = filter_var($description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (filter_var($description_sani, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
            $con = get_connection();
            $insert_query = "INSERT INTO tasks (name, description) VALUES ('$name_sani', '$name_sani')";
            if ($con->query($insert_query) === true) {
                // Missatge de OK
                sayok(get_message("new_task_added").$name_sani);
            } else {
                // Missatge NO OK
                saydie(get_message("cant_create_new_task"));
            }
            $con->close();
        } else {
            saydie(get_message("description_invalid"));
        }
    } else {
        saydie(get_message("task_name_invalid"));
    }
}

function list_tasks() {
    $con = get_connection();
    $search = $con->query('SELECT * FROM tasks');
	while($task=$search->fetch_assoc()){
	    saytask($task["name"], $task["description"], $task["id"], $task["status"]);
	}
    $con->close();
}

function remove_task($id) {
    $con=get_connection();
    if (task_exist($id)) {
        $sql = "DELETE FROM tasks WHERE id=$id";
        if ($con->query($sql) === true) {
            sayok(get_message("deleted_task").$id);
        } else {
            saydie(get_message("task_cant_be_removed"));
        }
    } else {
        saydie(get_message("task_not_found").$id);
    }
    $con->close();
}

function finish_task($id) {
    $con=get_connection();
    if (task_exist($id)) {
        $sql = "UPDATE tasks SET status='Finalitzada' WHERE id=$id";
        if ($con->query($sql) === true) {
            sayok(get_message("task_done").$id);
        } else {
            saydie(get_message("task_cant_make_done"));
        }
    } else {
        saydie(get_message("task_not_found").$id);
    }
    $con->close();
}


function task_name_exist($name) {
    $con = get_connection();
    $query=$con->query("SELECT name FROM tasks WHERE name = '$name' LIMIT 1");
    // Tanquem la connexió
    $con->close();
    if ($query->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function task_exist($id) {
    $con = get_connection();
    $query=$con->query("SELECT id FROM tasks WHERE id = '$id' LIMIT 1");
    $con->close();
    if ($query->num_rows > 0) {
        return true;
    }
    return false;
}

function get_connection() {
    global $config;
    $host=$config["database"]["host"];
    $user=$config["database"]["user"];
    $pass=$config["database"]["password"];
    $db=$config["database"]["db"];

    try {
        //Connection to BBDD
        $connection=new mysqli($host,$user,$pass,$db);
        //Returnem l'objecte de la conexió
        return $connection;
    } catch (Exception $e) {
        saydie(get_message("mysql_not_connect"));
    }
}
?>
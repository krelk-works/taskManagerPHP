<?php
function bbdd() {
    global $config;
    if ($config["storage-type"] == "mysql") {
        $host=$config["database"]["host"];
        $user=$config["database"]["user"];
        $pass=$config["database"]["password"];
        $db=$config["database"]["db"];

        //Connection to BBDD
        $connection=new mysqli($host,$user,$pass,$db);

        //Check connection
        if ($connection->connect_error) {
            $connection->close();
            die("\nFATAL ERROR : Connection failed: " . $connection->connect_error);
        } else {
            return $connection;
        }
    } else {
        die("\nNo es pot utilitzar MySQL si no hi ha una configuració previament.\n");
    }
}

function taskExist($id) {
    $con = bbdd();
    $query=$con->query("SELECT id FROM tasks WHERE id = '$id' LIMIT 1");
    $con->close();
    if ($query->num_rows > 0) {
        return true;
    }
    return false;
}

function addTask($taskName, $taskDescription) {
    $con = bbdd();
    echo "\n";

    if (strlen($taskName) > 30) {
        echo "Error: Nom de la tasca massa llarg : Maxim 30 caracters\n";
        addTask();
    } else if ($taskName == "c") {
        echo "\n\n";
        return;
    }

    if (empty($taskName)) {
        echo "Error: El nom no pot estar buit\n";
        addTask();
    }

    if (strlen($taskDescription) > 150) {
        echo "Error: Descripció massa llarga : Maxim 150 caracters\n";
        addTask();
    } else if ($taskDescription == "c") {
        echo "\n\n";
        return;
    }

    if (empty($taskDescription)) {
        echo "Error: La descripció no pot estar buida\n";
        addTask();
    }

    $insert_query = "INSERT INTO tasks (name, description) VALUES ('$taskName', '$taskDescription')";

    if ($con->query($insert_query) === true) {
        echo "[OK] Nova tasca afegida: $taskName\n";
    } else {
        echo "[!] Hi ha hagut un error al fer l'intent de crear una nova tasca\n";
    }

    // Tanquem la conexió amb la base de dades
    $con->close();
}

function showTasks() {
    $con = bbdd();
    $search = $con->query('SELECT * FROM tasks');
    echo "========= - TASKS LIST - =========\n\n";
	while($task=$search->fetch_assoc()){
	    echo "\n";
	    echo "==================================== TASK (",$task["id"],")\n";
	    echo "Name: ",$task["name"],"\n";
	    echo "Description: ",$task["description"], "\n";
        echo "Status: ",$task["status"],"\n";
	    echo "====================================\n\n";
	}
    $con->close();
}

function finishTask($id) {
    $con=bbdd();
    if (taskExist($id)) {
        $sql = "UPDATE tasks SET status='Finished' WHERE id=$id";
        if ($con->query($sql) === true) {
            echo "[OK] La tasca amb ID $id ha sigut Finalitzada.\n";
        } else {
            echo "[!] Hi ha hagut un error a l'intentar Finalitzar la tasca.\n";
        }
    } else {
        echo "[!] La tasca no existeix (ID : $id)\n";
    }
    $con->close();
}

function deleteTask($id) {
    $con=bbdd();
    if (taskExist($id)) {
        $sql = "DELETE FROM tasks WHERE id=$id";
        if ($con->query($sql) === true) {
            echo "[OK] La tasca amb ID $id ha sigut eliminada.\n";
        } else {
            echo "[!] No es pot esborrar la tasca\n";
        }
    } else {
        echo "[!] La tasca no existeix (id : $id)\n";
    }
    $con->close();
}
?>
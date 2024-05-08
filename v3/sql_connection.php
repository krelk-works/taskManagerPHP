<?php
function bbddAction() {
    global $config;
    $host=$config["database"]["host"];
    $user=$config["database"]["user"];
    $pass=$config["database"]["password"];
    $db=$config["database"]["db"];

    //Connection to BBDD
    $connection=new mysqli($host,$user,$pass,$db);

    //Check connection
    if ($connection->connect_error) {
        die("\nFATAL ERROR : Connection failed: " . $connection->connect_error);
    } else {
        return $connection;
    }
}

function taskExist($id) {
    $query=bbddAction()->query("SELECT id FROM tasks WHERE id = '$id' LIMIT 1");
    
    if ($query->num_rows > 0) {
        return TRUE;
    }
    return FALSE;
}

function addTask($taskName, $taskDescription) {
    echo "\n";

    if (strlen($taskName) > 30) {
        echo "\nError: Task name is too long : Maximum is 30 characters\n";
        addTask();
    } else if ($taskName == "c") {
        echo "\n\n";
        
        return;
    }

    if (empty($taskName)) {
        echo "\nError: Task name can't be empty\n";
        addTask();
    }

    if (strlen($taskDescription) > 150) {
        echo "\nError: Task description is too long : Maximum is 150 characters\n";
        addTask();
    } else if ($taskDescription == "c") {
        echo "\n\n";
        return;
    }

    if (empty($taskDescription)) {
        echo "\nError: Task description can't be empty\n";
        addTask();
    }

    $insert_query = "INSERT INTO tasks (name, description) VALUES ('$taskName', '$taskDescription')";

    if (bbddAction()->query($insert_query) === TRUE) {
        echo "\nNew task added: $taskName";
    } else {
        echo "\nError: " . $sql . " - ". $conn->error;
    }
}

function showTasks() {
    $search = bbddAction()->query('SELECT * FROM tasks');
    echo "========= - TASKS LIST - =========\n\n";
	while($task=$search->fetch_assoc()){
	    echo "\n";
	    echo "==================================== TASK (",$task["id"],")\n";
	    echo "Name: ",$task["name"],"\n";
	    echo "Description: ",$task["description"], "\n";
        echo "Status: ",$task["status"],"\n";
	    echo "====================================\n\n";
	}
}

function finishTask($id) {
    $taskToFinish = $id;
    if (taskExist($taskToFinish)) {
        $sql = "UPDATE tasks SET status='Finished' WHERE id=$taskToFinish";

        if (bbddAction()->query($sql) === TRUE) {
            echo "\nTask with ID $taskToFinish was update to Finished.";
        } else {
            echo "\nError updating record: " . $conn->error;
        }

    } else {
        echo "\nError : Task not exist (id : $taskToFinish)";
    }
}

function deleteTask($id) {
    $taskToDelete = $id;

    if (taskExist($taskToDelete)) {
        $sql = "DELETE FROM tasks WHERE id=$taskToDelete";

        if (bbddAction()->query($sql) === TRUE) {
            echo "\nTask with ID $taskToDelete was deleted.";
        } else {
            echo "\nError updating record: " . $conn->error;
        }

    } else {
        echo "\nError : Task not exist (id : $taskToDelete)";
    }
}
?>
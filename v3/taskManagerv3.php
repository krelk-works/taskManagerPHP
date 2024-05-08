<?php

if (php_sapi_name() != "cli") {
    die("\nError: Only works on CLI environment\n");
}

function bbddAction() {
    $host='localhost';
    $user='root';
    $pass='hola1234';
    $db='taskManager';

    //Connection to BBDD
    $connection=new mysqli($host,$user,$pass,$db);

    //Check connection
    if ($connection->connect_error) {
        die("\nFATAL ERROR : Connection failed: " . $connection->connect_error);
    } else {
        return $connection;
    }

    $connection.close();
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

$options = getopt('c:l::f:d:n:r:', ['create:', 'list::', 'finish:', 'remove:', 'name:', 'description:']);

if (empty($options)) {
    showHelp();
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




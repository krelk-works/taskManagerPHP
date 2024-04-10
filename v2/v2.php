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
}

function setOption(){
    $line = readline("Option: ");
    $line = intval($line);
    if (gettype($line) != "integer" or $line === 0)  {
        echo "\n\nError : Invalid option (only numbers)\n";
        echo "\nTry again\n";
        
    } else {
        switch($line) {
            case 1:
                addTask();
                break;
            case 2:
                showTasks();
                break;
            case 3:
                finishTask();
                break;
            case 4:
                deleteTask();
                break;
            case 5:
                //Close the BBDD connection
                bbddAction()->close();
                exit;
                break;
            default:
                echo "\n\nError : Invalid option\n";
                echo "\nTry again\n";
                
                break;
        }
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

function finishTask() {
    $taskToFinish = readline("Task to finish (ID) : ");

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

function deleteTask() {

    $taskToDelete = readline("Task to delete (ID) : ");

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
/*
$options_available = ["c", "l", "f", "d", "create", "list", "finish", "delete"];
$options = getopt('c:l::f:d:', ['create:', 'list::', 'finish:', 'delete:']);


$count_options=0;


foreach ($options_available as $option) {
    global $options, $count_options;
    echo(var_dump($options));
    echo $option,"\n";
    foreach ($options as $option_selected) {
        
        if ($value == $selected_value) {
            if (!empty($options["c"]) or !empty($options["create"])) {
                $count_options = $count_options + 1;
            } elseif(empty($options["l"]) or empty($options["list"])){
                $count_options = $count_options + 1;
            } elseif (!empty($options["f"]) or !empty($options["finish"])) {
                $count_options = $count_options + 1;
            } elseif (!empty($options["d"]) or !empty($options["delete"])) {
                $count_options = $count_options + 1;
            }
        }
        
        echo "Opcion leída \n";
    }
}


echo "Hay ", $count_options, " opciones ingresadas";
*/

$options = getopt('f:h::');
var_dump($options);


if (!empty($options["c"]) or !empty($options["create"])) {
    
} elseif(empty($options["l"]) or empty($options["list"])){
    #showTasks();
} elseif (!empty($options["f"]) or !empty($options["finish"])) {
    $count_options = $count_options + 1;
} elseif (!empty($options["d"]) or !empty($options["delete"])) {
    $count_options = $count_options + 1;
}

#showHelp();

function showHelp() {
    echo "\n";
    echo "ERROR : taskManager - Syntax error\n";
    echo "==================================\n";
    echo "\n";
    echo "-> To CREATE a new task:\n";
    echo "        -c | --create [task name | string] && -d [string] | --description [task description | string]\n";
    echo "\n";
    echo "-> To LIST all the tasks:\n";
    echo "        -l | --list\n";
    echo "\n";
    echo "-> To FINISH an task:\n";
    echo "        -f | --finish [task ID / int]\n";
    echo "\n";
    echo "-> To DELETE an task:\n";
    echo "        -d | --delete [task ID / int]\n";
}

?>
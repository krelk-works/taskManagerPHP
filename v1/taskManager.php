<?php

if (php_sapi_name() == "cli") {
    echo "\nScript started with [·] CLI [·]\n";
} else {
    echo "\nError: Only works on CLI environment\n";
    return;
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
        drawMenu();
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
                drawMenu();
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

function addTask() {
    echo "\n";
    $taskName = readline("Task name (c to cancel): ");

    if (strlen($taskName) > 30) {
        echo "\nError: Task name is too long : Maximum is 30 characters\n";
        addTask();
    } else if ($taskName == "c") {
        echo "\n\n";
        drawMenu();
        return;
    }

    if (empty($taskName)) {
        echo "\nError: Task name can't be empty\n";
        addTask();
    }

    $taskDescription = readline("Task description (c to cancel): ");

    if (strlen($taskDescription) > 150) {
        echo "\nError: Task description is too long : Maximum is 150 characters\n";
        addTask();
    } else if ($taskDescription == "c") {
        echo "\n\n";
        drawMenu();
        return;
    }

    if (empty($taskDescription)) {
        echo "\nError: Task description can't be empty\n";
        addTask();
    }

    $insert_query = "INSERT INTO tasks (name, description) VALUES ('$taskName', '$taskDescription')";

    if (bbddAction()->query($insert_query) === TRUE) {
        echo "\nNew task added: $taskName";
        drawMenu();
    } else {
        echo "\nError: " . $sql . " - ". $conn->error;
        drawMenu();
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

    //Return to main menu
    drawMenu();
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

    //Return to main menu
    drawMenu();
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

    //Return to main menu
    drawMenu();
}

function drawMenu() {
    echo "\n\n";
    echo "Task Manager v0.1 Beta\n";
    echo "=========================";
    echo "\n";
    echo "1. Add new task\n";
    echo "2. List all tasks\n";
    echo "3. Finish task\n";
    echo "4. Delete task\n";
    echo "5. Exit\n";
    echo "=========================";
    echo "\n\n";
    setOption();
}

drawMenu();

?>
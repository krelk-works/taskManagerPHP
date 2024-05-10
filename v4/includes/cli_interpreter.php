<?php

function intelliexec($options) {
    if ((array_key_exists('c', $options) or array_key_exists('create', $options)) and (array_key_exists('d', $options) or array_key_exists('description', $options))
    and !array_key_exists('r', $options) and !array_key_exists('remove', $options) and !array_key_exists('f', $options) and !array_key_exists('finish', $options)) {
        if (!(array_key_exists('c', $options) and array_key_exists('create', $options))) {
            if (!(array_key_exists('d', $options) and array_key_exists('description', $options))) {
                if (sizeof($options) == "3" or sizeof($options) == "2") {
                    $name = "";
                    $description = "";
                    if (array_key_exists('c', $options)) { $name = $options['c']; }
                    if (array_key_exists('create', $options)) { $name = $options['create']; }
                    if (array_key_exists('d', $options)) { $description = $options['d']; }
                    if (array_key_exists('description', $options)) { $description = $options['description']; }
                    if (!empty($name) and !empty($description)) {
                        create_task($name, $description);
                        if (array_key_exists('l', $options) or array_key_exists('list', $options)) {
                            list_tasks();
                        }
                        return;
                    } else {
                        sayerror(get_message("task_name_or_desc_empty"));
                    }
                }
            }
        }
    }

    if (sizeof($options) == 1 and (array_key_exists('l', $options) or array_key_exists('list', $options))) {
        list_tasks();
        return;
    }

    if (sizeof($options) == 1 and (array_key_exists('f', $options) or array_key_exists('finish', $options))) {
        $id = "";
        if (array_key_exists('f', $options)) { $id = $options['f']; }
        if (array_key_exists('finish', $options)) { $id = $options['finish']; }
        if ($id != "" and is_numeric($id)) {
            finish_task($id);
        } else {
            saydie(get_message("empty_id"));
        }
        
        return;
    }

    if (sizeof($options) == 1 and (array_key_exists('r', $options) or array_key_exists('remove', $options))) {
        $id = "";
        if (array_key_exists('r', $options)) { $id = $options['r']; }
        if (array_key_exists('remove', $options)) { $id = $options['remove']; }
        if ($id != "" and is_numeric($id)) {
            remove_task($id);
        } else {
            saydie(get_message("empty_id"));
        }
        return;
    }

    if (sizeof($options) == 1 and (array_key_exists('h', $options) or array_key_exists('help', $options))) {
        saysintax();
        return;
    }
    
    saydie(get_message("invalid_parameters"));
}

?>
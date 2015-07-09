<?php

/**
 * @todo Redirect to another page
 **/
function redirect($page, $args = false)
{
    $page = $page.'.php'.$args;
    if (DEBUG == false) {
        header('location:'.$page);
    } else {
        debug('PHP', 'Redirection', $page, true);
    }
}

function redirect_action($args)
{
    $args_explode = explode(';', $args);
    $object = $args_explode[0];
    $id = $args_explode[1];
    $action = $args_explode[2];
    $action_nb = $args_explode[3];
    $state = $args_explode[4];
    header('location:actions.php?type=action&object='.$object.'&id='.$id.'&action='.$action.'&action_nb='.$action_nb.'&state='.$state);
}

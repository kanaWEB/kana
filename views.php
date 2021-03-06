<?php

include 'core/common.inc'; //Common libraries

//If the user is set
if (isset($currentUser)) {
    //If the user is not disable
    if ($currentUser->isuser()) {
        include CORE_TEMPLATES.'/header/header.view';

    //If a view is set
        if (isset($_['view'])) {
            $view_name = $_['view'];
            $view_right = $currentUser->ViewRight($view_name);

            if (($view_right)  || ($currentUser->isadmin())) {
                $view_dir = USER_VIEWS.'/'.$view_name.'/';
                $view_file = $view_dir.$view_name.'.view';
                $data_path = USER_DATA.$view_name.'/';
                $md_file = $view_dir.$view_name.'.md';

            //Show a button to make view as default only if it isn't already the case
                if ($currentUser->default_view() != $view_name) {
                    $default_view_button = [
                    'view' => $view_name,
                    'iduser' => $currentUser->id(),
                    ];
                    $tpl->assign('button', $default_view_button);
                } else {
                    $default_view_button = false;
                }

        //VIEW FILE (php code mode)
                if (file_exists($view_file)) {
                    include CORE_TEMPLATES.'/menu/top.view';
                    if ($default_view_button != false) {
                        $tpl->draw(CORE_TEMPLATES.'buttons/default_view');
                    }
                    include $view_file;
                } elseif (file_exists($md_file)) {
                    //VIEW MD TABLE (markdown mode)
                    include CORE_TEMPLATES.'/menu/top.view';
                    if ($default_view_button != false) {
                        $tpl->draw(CORE_TEMPLATES.'buttons/default_view');
                    }
                    $blocks = Draw::md2datatable($md_file, $view_dir);

                    include CORE_TEMPLATES.'buttons/ajax_play.view';
                    include CORE_TEMPLATES.'datatable/blocks.view';
                }
            } else {
                    $tpl->draw(CORE_TEMPLATES.'modal/permissions_denied');
            }
        } else {
            //If no view is set
            //Redirect to first view
            if ($currentUser->default_view() == '') {
                $view_list = Functions::getdir(USER_VIEWS);
                redirect('views', '?view='.$view_list[0]);
            } else {
                //Redirect to
                redirect('views', '?view='.$currentUser->default_view());
            }
        }
        include CORE_TEMPLATES.'/footer/footer.view';
    } else {
        redirect('index', '?error='.t('You need to be logged to see this page'));
    }
} else {
    //If no user is set, we assume nothing was installed
    redirect('install');
}

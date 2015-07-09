<?php

include 'core/common.inc'; //Common libraries

if (REINSTALL) {
    unlink(DATABASE);
    redirect('install');
}

//If Database exists
//@todo verify if database is valid (if it is superior to 0 in size for example)
if (DB_EXISTS) {
    //If submit is set than we post the data
    if (isset($_['logout']) || isset($_['submit'])) {
        include 'core/forms/index.post'; //Load Post
    } else {
        include 'core/forms/index.form'; //Load Form
    }
} else {
    redirect('install');
}

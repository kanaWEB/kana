<?php
include("core/common.inc"); //Common libraries

//If the database doesn't exists
if (!DB_EXISTS) {
//If the user has submit root account information
    if (isset($_["submit"])) {
        include("core/forms/install.post");
    } else {
        //Show Installation form
        include("core/forms/install.form");
    }
} else {
    //If database exists we forbid to run install
    echo "<h1>".t("Installation is already done!")."</h1>";
    echo "<legend>".t("Change this constant to reinstall:")."<legend><br>";
    echo "<b>core/constant.inc</b><br>";
    echo '<code>define("REINSTALL","TRUE")</code>';
}

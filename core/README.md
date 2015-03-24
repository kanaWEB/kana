# CORE FUNCTIONALITIES (core/)


## actions (HTTP request redirector for actions.php)
Every request on actions.php is redirect here
````actions.php?action=collectors````  --> ````include core/actions/collectors.actions  ````

* Only authorized users have access to theses files (actions.php verified this)
* Each files is named ````actions```` (so they cannot be executed directly and for easier recognition)
* Finer user verifications are done here

Main actions are
* action.actions (execute a system command based on plugins)
* data.actions   (include a data file)

**For more information see core/actions/readme.md**

## collectors (python daemon to collect data)
Collectors are python daemon that can collect data from various source (gpio state/serial communication etc...)
* Libraries are available to create your own daemon
* Daemon are autonomous and can be reuse in others applications

Theses deamon are controlled by local socket and data can be collected directly from theses sockets

**For more information see core/collectors/readme.md**

## data (data generator)
Data are php file that return information

## forms (form generator for settings.php/index.php/install.php)

Each form have two files:
* .form --> display form
* .post --> process information from form

Form are included by their php counterpart
* (login form) index.php --> index.form / index.post
* (install form) install.php --> install.form / install.post
* (settings form) settings.php --> settings.form / settings.post

Settings have two levels of forms.
* LEVEL 1 : category
* LEVEL 2 : menu
* LEVEL 3 (for objects) : tab

By looking at the url of a page inside settings.php you can deducted which form is used.

For example:
settings.php?category=configuration&menu=pref --> core/forms/settings/configuration/pref.form

**For more information see core/forms/readme.md**

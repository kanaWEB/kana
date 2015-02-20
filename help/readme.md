How plugins works
===

# There are 4 types of plugins
* Language : Global translation
* Objects  : Objects you can controlled
* Theme    : Modification of the original Bootstrap 3 theme
* Views    : Page where are display data or/and objects

Each plugins works based on directory and files.

# Objects plugin
There are 2 types of objects
* Object that are plugged directly on the Raspberry Pi (ex: led)
* Objects remotely controlled by the Raspberry Pi (ex: Radio devices/Computers)

## Direct
Theses objects can used as many GPIO as needed.  
For example : you can plug 1 or more led, whit remotely controlled objects.

## Remote
Theses objects used an exact number of gpios  
For example : You only need 1 gpio to plug an radio transmitter

## Directories inside plugins
* Gpios : This directory contains markdown files where fields of forms is defined
Gpios is used to configure hardware only
* Actions : This directory contains markdown files where fields of forms is defined
Actions are used 
* Buttons : Buttons displayed inside list
* Help : Help file in markdown
* Info : Information on the plugin (name)
* Language : Word by word translation loaded when needed

## Markdown Forms
Each markdown file represent a field inside your forms.
Data on how to build the form is presented as Markdown table so it could be
easily understand/modify directly inside github.

Exemple:
id    |name           |type|placeholder						|required|
------|---------------|----|--------------------------------|--------|
speed |Speed in ms    |text|Times before the led blink again|true    |


### General fields
* id : name of the database field
* name : Displayed name
* type : Type of field, will open core/views/form/input/TYPE.html 
* datatype : Type of data (number/text ?)

### Select
* options : Choice are loaded from a data file, for example : gpio/pinslists will load core/data/gpio/pinslists.data or views/pluginname/data/gpio/pinslists.data or objects/pluginname/data/gpio/pinslists.data

### Text
* placeholder
* required : Is this field require (Warning: This will only prevent user to forget to fill the field, not forced them as this is locked on the client side with HTML)



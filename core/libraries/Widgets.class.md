# Widgets.class.php
Widgets class is used to manage widget generation of objects AND sensors.

## Load widgetsList
### Objects
WidgetsList = new Widgets("objects");
### Sensors
WidgetsList = new Widgets("sensors");

## Populate WidgetsLists

### GetById (Get a specific action)
WidgetsList = Widgets->GetById("radio",1);

### GetByType (Get all object/sensors of a type)
WidgetsList = Widgets->GetByObject("radio");

### GetByGroupId (Get all objects/sensors of a specific group)
WidgetsList = Widgets->GetByObject(1);

## Widgets Data
Everything is inside list array

### Info (from action table)
#### action ID
* widget->list[0]["info"]["id"];

#### Foreign key to Config table
* widget->list[0]["info"]["object_key"];

#### Command name
* widget->list[0]["info"]["command"];

#### Object type
* widget->list[0]["info"]["object"];

#### Object Name
* widget->list[0]["info"]["name"];

#### Object Description
* widget->list[0]["info"]["description"];

#### Object Icon
* widget->list[0]["info"]["icon"];

### Buttons (from buttons.json)

#### Action complete Name
* widget->list[0]["buttons"]->name;

#### Button color (bootstrap)
* widget->list[0]["buttons"]->color;

#### Icon type (bootstrap)
* widget->list[0]["buttons"]->icon;
* widget->list[0]["buttons"]->command;
* widget->list[0]["buttons"]->state;
* widget->list[0]["buttons"]->effect;

### State (from state.json)
#### Data to get color
* widget->list[0]["state"]->onload;
#### Template of state (see core/templates/dashboard/)
* widget->list[0]["state"]->html; (Not implement)

## Drawing WidgetsList
* widget->draw();

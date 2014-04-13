Rentgen
=======
[![Build Status](https://travis-ci.org/czogori/Rentgen.png?branch=master)](https://travis-ci.org/czogori/Rentgen)
[![Latest Stable Version](https://poser.pugx.org/czogori/rentgen/v/stable.png)](https://packagist.org/packages/czogori/rentgen)

Database abstraction layer for both information and manipulation schema
## Installation 
Using composer:
```js
{
    "require": {        
        "czogori/rentgen": "dev-master"
    }
}
```
## Basic usage
Create sample table
```php
$rentgen = new Rentgen();
$manipulation = $rentgen->createManipulationInstance();

$table = new Table('foo');
$table->addColumn(new StringColumn('bar'));
$table->addColumn(new IntegerColumn('baz', array('not_null' => true)));
$manipulation->create($table);
```
Get information about foo table
```php
$info = $rentgen->createInfoInstance();
$fooTable = $info->getTable(new Table('foo'));
```

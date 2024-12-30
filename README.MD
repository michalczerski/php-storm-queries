# Storm PHP Queries

It's mix of query builder and ORM made to fetch data in most effective way.\
Intuitive, easy to learn light yet powerful tool for retrieving data from SQL databases.\
If you work with ORMs you will notice that you don't have to configure nothing but still you 
have possilbity to build easilly queries and retrieve in hierarchical manner. 

The most benefits with other tools is: 

- hierarchical models (biggest advantage of ORM without disadvantages of over-configuration etc.)
- workking with existing schema
- no need to change anything in your models (your aggregates in DDD can be clean, without any infrastructure code)
- query builder supporting assembling queries (good for DDD/finder-criteria pattern)
- handle sub queries
- no configuration needed
- fluent api
- supports various databases (tested with PostgreSql, MySql, MSSQL, SQLite)
- light and tidy code  (no additional dependencies)
- efficiency - it's really thin layer over simple query 
- developer friendly (profiling queries, overview of generated sql)
- intuitive. You need only knowledge of SQL and PHP

## Overview of possibilites

```php
use Storm\Query\StormQueries;
use Storm\Query\Mapper\Map;
use Storm\Query\ConnectionFactory;

$connection = ConnectionFactory::cr
$queries = new StormQueries();

$map = Map::create();

$orders = $queries
    ->select('*')
    ->from('orders')
    ->leftJoin('products', 'orders.product_id', 'products.id')
    ->where('total' '>=', $queries->select('avg(total)')->from('orders'))
    ->orderByDesc('total')
    ->find($map);
     
foreach($orders as $order) {
    foreach($order->products as $product) {
      
    }
}                   
```

## Index

## Quick start


### Installation

You will need composer or use Storm PHP Framework (Stom PHP Queries are part of it)
 
Add to `composer.json`
```php
 "stormmore/queries": "^1.0.0"
```

or run
```php
composer require stormmore/queries
```

### Establishing connection
All you need to connect to database is
```php
    use Storm\Query\ConnectionFactory;
    use Storm\Query\StormQueries;
    
    $connection = ConnectionFactory::createFromstring("");
    $queries = new StormQueries($connection);
```

## Select 

Simplest select would be

```php
$queries
    ->select("column1", "column2", "column3")
    ->from('tableName')
    ->where('id', 2);
    ->findOne();
```

or 

```php
    $query = $queries->select('*');
    $query->from('tableName');
    $query->where('id', 2);
    $record = $query->findOne();
```

Every time you invoke select method you add columns to final query.\
It's helpful to assembly dynamic queries in your presentation layer (with left join working similar)
```php
   $query = $queries->select('column1');
   $query->select('column2');
   $query->select('column3');
```


### Left join

### Where
Where in []
Where like
Where <>
Where NOT
etc.
OnSuccess/

#### Nested conditional

### SubQuery

### OrderBy

### GroupBy

### Having

### Mapping data to hierarchical structure

## Insert

## Update

## Delete

## Profiling 

## 
Use DateTime to compare dates
use strtolower to compare uuid (mssql)
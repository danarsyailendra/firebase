# Firestore for Laravel
This package extended from [kreait/laravel-firebase](https://github.com/kreait/laravel-firebase) 
focusing at firestore integration and support query builder like Laravel with several limitations.

+ [Installation](#installation)
    + [Composer](#composer)
    + [Service Provider](#service-provider)
+ [Usage](#usage)
    + [Read Data](#read-data)
    + [Insert Data](#insert-data)
    + [Update Data](#update-data)
    + [Delete Data](#delete-data)

## Installation
This package requires :
+ Laravel 6.x and higher
+ gRPC extension

### Composer

```composer
composer require syailendra/laravel-firestore
```

### Service Provider

```php
<?php
// config/app.php
return [
    // ...
    'providers' => [
        // ...
        Syailendra\Firebase\LaravelFirestoreServiceProvider::class,
    ]
    // ...
];
```

## Usage

### Read Data

#### Get Documents from Collection
```php
$getDocs = Firestore::collection('collection_name')
            ->get();
```
#### Get a Document from Collection
```php
$data = Firestore::collection('collection_name')
            ->whereDoc("document_id")->snapshot();
```
#### Get sub Collection
```php
$data = Firestore::collection('collection_name')
            ->whereDoc("document_id")->getCollections ();
```
#### Where
```php
$getDocs = Firestore::collection('collection_name')
            ->where('lastname', 'Doe')
            ->get();
```
Or with 3 parameters
```php
$getDocs = Firestore::collection('collection_name')
            ->where('age', '>=', 19)
            ->where('lastname', '=', 'Doe')
            ->get();
```
<span style="color:red"><i>Note : when you use two or more fields you must declare the index 
at firebase console</i></span>.

Or with array
```php
$getDocs = Firestore::collection('collection_name')
            ->where([
                ['age', '>=', 19],
                ['lastname', 'Doe']
            ])
            ->get();
```
#### Order By
```php
$getDocs = Firestore::collection('collection_name')
            ->orderBy('lastname')
            ->get();
```
With 2 parameters
```php
$getDocs = Firestore::collection('collection_name')
            ->orderBy('lastname', 'desc')
            ->orderBy('age')
            ->get();
```
<span style="color:red"><i>Note : when you use two or more fields you must declare the index 
at firebase console</i></span>.

Or with array
```php
$getDocs = Firestore::collection('collection_name')
            ->orderBy([
                ["lastname", "desc"],
                ["age"]
            ])
            ->get();
```
#### Limit
```php
$getDocs = Firestore::collection('collection_name')
            ->orderBy('lastname')
            ->limit(3)
            ->get();
```
### Insert Data
```php
$insert = Firestore::collection('collection_name')
            ->insert([
                'firstname' => 'John',
                'lastname' => 'Doe',
                'age' => 19
            ]);
```
<span style="color:red"><i>Note : always use auto generate id</i></span>.
### Update Data
```php
$update = Firestore::collection('collection_name')
            ->whereDoc('document_id')
            ->update([
                'firstname' => 'Jeremy',
                'lastname' => 'Smith',
                'age' => 19
            ]);
```
### Delete Data

#### Delete Document
```php
$delete = Firestore::collection('collection_name')
            ->whereDoc("document_id")
            ->deleteDoc();
```
#### Delete Field
```php
$delete = Firestore::collection('collection_name')
            ->whereDoc("document_id")
            ->deleteFields("age");
```
With array
```php
$delete = Firestore::collection('collection_name')
            ->whereDoc("document_id")
            ->deleteFields(["age","lastname"]);
```

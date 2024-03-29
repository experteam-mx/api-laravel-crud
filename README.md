API Laravel CRUD
=

Base service providers to manage crud services <br>
It includes:
- <b>Model paginate:</b> Trait to manage pagination, it can manage limit, offset and order by.


### Install

Run the following commands to install: <br>
```
composer require experteam/api-laravel-crud

php artisan vendor:publish --tag=lang
```

### Update
Run the composer command to update the package: <br>
```
composer update experteam/api-laravel-crud
```

### Use advanced Queries with mongoDB
To use advanced queries on mongoDB you need to add a public property to your model to identify it as a MongoDB collection<br>
`public bool $isMongoDB = true;`

### License
[MIT license](https://opensource.org/licenses/MIT).

Demo Order Service
========================

Requirements
------------

* PHP 8.2.0 or higher;
* and the [usual Symfony application requirements][1].
* RabbitMQ service.

Installation
------------
**STEP 1.** Please clone tne project into the document root folder .

**STEP 2.** Configure .env according to env.example

**STEP 3.** Create DB and execute migrations
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

API Endpints :
------------
**Get all orders.**

**Method:** `GET`  
**Endpoint:** `/order`
```
curl --request GET \
--url http://demo-order.loc/order
```

**Create a new order.**

**Method:** `POST`  
**Endpoint:** `/order`

**Content Body**
```
{
  "uuid": {Product uuid},
  "qty": {qty},
}

```

```
curl --request POST \
  --url http://demo-order.loc/order \
  --header 'Content-Type: application/json'
  --data '{
	"uuid": "1efbd2ee-68e0-608a-95c6-29d8fbfde3cf",
	"qty": 10
}'
```
[1]: https://symfony.com/doc/current/setup.html#technical-requirements
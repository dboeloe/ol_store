# Ol Store and Treasure Hunt
# Task 1
#### Analysis
- Bad reviews happens because at that time, lot of order requests are being processed and there is no proper integrity checking of the number of stock for each product. This lead to race conditions and resulted with neglected order because the stock is not available
- To maintain data integrity for stock number, we need to put some validation on database level. The most feasible solution is when we want to check in or check out product, we need to store all the stock transaction and put trigger. With this, every process that related with product check out or check in will be stored and validate to make sure the order transaction will always be feasible. With this approach, we also be able to have valid data for stock opname.

#### PoC Setup
This PoC will using:
- Lumen 8.
- Php version 7.4
- Postgres


Install the dependencies and devDependencies and start the server.
```sh
cd <repo_name>
composer install
php artisan migrate
php -S localhost:8000 -t public
```

Manual testing flow for concurrent requests
- Import postman collection inside this repo (olstore.postman_collections.json)
- register user via API
- add item via API
- add / remove stock via API
- create order via API
- to simulate concurrent request, convert postman create order API to curl command
    - run multiple curl request in one go in terminal. i.e. curl1 & curl2 & curl3 & curl4
    - the program will process stock changes when the requested number of stock is within reach. it will reject request that above available stock
- run unit test in using 'vendor/bin/phpunit' inside repo using terminal


# Task 2
Treasure hunt program will use python script called treasure_hunt.py . It will received 3 parameter for initial movement of player (up, right and down). At the end of the program, it will show the latest board after first movement and list its possible treasure coordination
```
python treasure_hunt.py 1 2 3
```



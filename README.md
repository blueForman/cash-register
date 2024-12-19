# Welcome to cash register project
This project is API only

To run project run: 
- `composer install`
- `docker compose up -d` to start db
- `composer run dev` to start dev server
- you can find request examples in `request_examples` folder and run them with PHPStorm http client


# Todo:
- introduce Item model that has Product and quantity and remove quantity from Product
- revise naming
- add input validation
- add reset cart endpoint
- add delete cart endpoint
- add stock info
- add quantity types(per unit, per weight, per hour, etc)
- extract items from order to a separate one to many table
- add posibility to add products and customers
- add product search
- 

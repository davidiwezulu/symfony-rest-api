# Symfony Premium API. 
<p>Built in Symfony MVC framework.</p>

## Installation and Setup
Run the following commands below to setup the server
- `composer install`
- Create an empty database to be used in `.env`file
- Setup the database connection in `.env` file and run `php bin/console doctrine:schema:create` to create entity table schemas
- Run a migration on the newly created database `php bin/console make:migration` 
- Migrate entities to database tables `php bin/console doctrine:migrations:migrate` 
- Populate database with dummy data `bin/console doctrine:fixtures:load` 
- Start the server with `php bin/console server:run`
<br/>That's it!

### Make a test API call with:
`curl --header "Content-Type: application/json" \
--request POST \
--data '{"age":"20","postcode":"PE3 8AF", "regNo":"PJ63 LXR"}' \
http://127.0.0.1:8000/api/premium
`


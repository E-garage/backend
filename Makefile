install:
	docker-compose build
	docker-compose run php composer install
db-create:
	docker-compose exec php php vendor/bin/doctrine orm:schema-tool:create
start:
	docker-compose up -d

stop:
	docker-compose down --remove-orphans

phpstan:
	docker-compose exec php composer phpstan

cs-check:
	docker-compose exec php composer cs:check

cs-fix:
	docker-compose exec php composer cs:fix

run-tests:
	docker-compose exec php composer cs:check --quiet
	docker-compose exec php composer test
db-seed:
	docker-compose exec php php bin/console.php db:seed
openapi:
	docker-compose exec php vendor/bin/openapi /var/www/src --output resources/docs/openapi.yaml


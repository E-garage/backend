install:
	docker-compose build
	docker-compose run php composer install

db-create:
	docker-compose exec php php artisan migrate
db-refresh:
	docker-compose exec php php artisan migrate:refresh
start:
	docker-compose up -d

stop:
	docker-compose down --remove-orphans

bash:
	docker-compose exec php bash

phpstan:
	docker-compose exec php composer phpstan

cs-check:
	docker-compose exec php composer cs:check

cs-fix:
	docker-compose exec php composer cs:fix

run-tests:
	docker-compose exec php composer cs:check --quiet
	docker-compose exec php composer test
openapi:
	docker-compose exec php php artisan l5-swagger:generate
ide-helper:
	docker-compose exec php php artisan ide-helper:generate
ide-helper-meta:
	docker-compose exec php php artisan ide-helper:meta
ide-helper-models:
	docker-compose exec php php artisan ide-helper:models --write
fix-permissions:
		docker-compose exec php usermod -u 1000 www-data

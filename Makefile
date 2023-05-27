build:
	@docker compose build --no-cache;

start:
	@docker compose up -d;

stop:
	@docker compose stop;

php-bash:
	@docker compose exec php bash;

php-install:
	@docker compose exec -T php rm -rf vendor;
	@docker compose exec -T php rm composer.lock;
	@docker compose exec -T php composer install;

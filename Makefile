test:
	php artisan test

mig:
	php artisan migrate

seed:
	php artisan db:seed

key:
	php artisan key:generate

migs:
	make mig
	make seed

up:
	docker compose up -d

dw:
	docker compose down

prepare-env:
	cp -n .env.example .env || true
	make key

install:
	composer install
	npm i
	npm run build

start: up
	sleep 3
	make c-prepare-db

c-prepare-db:
	docker exec json_docs_app chmod 777 -R storage
	docker exec json_docs_app make mig

c-seed-db:
	docker exec json_docs_app make seed


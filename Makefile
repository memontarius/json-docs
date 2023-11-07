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

install:
	composer install
	npm i
	npm run build

prepare-env:
	cp -n .env.example .env || true
	make key

setup: up
	sleep 3
	sudo chmod 777 -R storage
	make c-prepare-db

c-prepare-db:
	docker exec json_docs_app make mig

c-seed-db:
	docker exec json_docs_app make seed

c-test:
	docker exec json_docs_app make test


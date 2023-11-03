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
	docker-compose up -d

dw:
	docker-compose down

setup-env:
	cp -n .env.example .env || true
	make key
	composer install
	npm i
	npm run build

setup-app:
	make up
	sleep 1
	docker exec json_docs_app make mig

c-seed:
	docker exec json_docs_app make seed


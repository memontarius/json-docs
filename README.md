
# Json Docs

Выполенение тестового задания https://github.com/magdv/php-test-task

## Установка

### Предварительные требования

* PHP ^8.1
* Make
* Composer
* Node.js & NPM
* PostgreSQL

### Запуск с разворачиванием в Docker-контейнере

1. Установить зависимости

    ```sh
    make install
    ```
   
2. Подготовить конфигурационный файл

    ```sh
    make prepare-env
    ```

3. Указать параметры подключения к БД в файле *.env*

    ```dotenv
    DB_DATABASE=postgres
    DB_USERNAME=postgres
    DB_PASSWORD=secret
    ```

4. Запуск докер-контейнеров и миграция базы

    ```sh
    make start
    ```

5. Тестирование и заполнение базы в контейнера

    ```sh
    make test
    make c-seed-db
    ```


# Json Docs

Выполенение тестового задания https://github.com/magdv/php-test-task

## Установка

### Предварительные требования

* PHP ^8.1
* Make
* Composer
* Node.js & NPM

### Запуск с разворачиванием в Docker-контейнере

1. Установить зависимостей

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

4. Настройка приложения (запускает докер-контейнеры и делает миграцию)

    ```sh
    make setup
    ```

5. Тестирование в контейнере

    ```sh
    make c-test
    ```
   
6. Заполнение базы тетовыми данными в контейнере
    ```sh
    make c-seed-db
    ```

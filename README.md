
# Json Docs

Выполенение тестового задания https://github.com/magdv/php-test-task
Выполнены все опциональные задания.

## Установка

### Предварительные требования

* PHP ^8.1
* Make
* Composer
* Node.js & NPM

#### Установка пакетов в Ubuntu

```sh
./ubuntu-packs-installer.sh
```

Возможные параметры:
```sh
no-nvm no-docker no-composer
```


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

4. Настройка приложения (запускает докер-контейнеры и делает миграцию)

    ```sh
    make setup
    ```

5. Тестирование в контейнере

    ```sh
    make c-test
    ```
   
6. Заполнение базы данных в контейнере
    ```sh
    make c-seed-db
    ```

## Отчет затраченого времени

1. Настройка проекта - 15 мин.
2. Добавления роутинга - 30 мин.
3. Модель документа, создание черновика, первое редактирование - 4 ч.
   - Застопорился с отображением часового пояса в документе. Так как решил хранить время в базе в UTC (по best practice),
   то часовой пояс определял у текущего клиента через заголовок или чрез IP.
4. Патчинг документа - 4 ч
   - Долго искал готовое решение. В итоге нашел только один пакет с подходящей спецификацией патчинга.
5. Публикация и показ документа - 1 ч
6. Реализация ответа в виде json при ошибках - 2 ч. 
   - Искал как отобразить json ответ при ошибке 404
7. Рефакторинг - 2 ч
8. Тестирование - 2 ч
9. Своя реализация патчинга - 4 ч.
    - Сначала неправильно разобрался со спецификацией - пытался реализовать обновление массивов.
   После посмотрел реализацию алгоритма в спецификации, в итоге получилась такая же реализация как в используемом до этого пакете.
10. Добавление аутентификации пользователей, сид базы - 4 ч.
    - Разбирался с документацией Sanctum
    - Фикс тестирования
11. Добавление фронта - 5 ч.
    - Столкнулся с проблемой вызова self api, так как _Route::dispatch($request)_ - теряет параметры запроса, а
      _app()->handle($request)_ - затирает текущий запрос.
    - Разбирался с Tailwind CSS
12. Добавление транзакций для конкурентных запросов - 3 ч.
    - Изучал тему по транзакциям
13. Рефакторинг - 2 ч.
14. Настройка докера - 5 ч.
    - В докере решил подключить PostgreSQL - получил проблему с идентификаторами документов. Постгрес кидает исключение при неправильном адресе документа. Решил добавлением
    для всех идшек соответствие регулярке. Дальше не получалась персистентность данных. Решил проблему созданием волюма.
15. Тестирование сборки докер контейнера - 3 ч.
    - Написал bash-скрипт для установки необходимых пакетов
    


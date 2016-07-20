## Gitter Bot based on Laravel 5.1

[![volkswagen status](https://auchenberg.github.io/volkswagen/volkswargen_ci.svg?v=1)](https://github.com/auchenberg/volkswagen) 
[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/LaravelRUS/GitterBot?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

### Запуск

- Выполните `composer install`
- Сконфигурируйте бота 
    - Параметр `GITTER_TOKEN` в файле `.env` (если такого нету, 
        создайте из `.env.example`). Токен можно узнать по этой [ссылке](https://developer.gitter.im/apps).
    - Прочие параметры находятся в файле `config/gitter.php`
- Выполните миграции `php artisan migrate`
- Введите `php artisan gitter:listen $roomId`, где `$roomId` - идентификатор комнаты или его алиас. 
    Самый простой вариант получения `$roomId` выполнить artisan команду `php artisan gitter:get-room-id room/Name`,
    либо выполнить get запрос `https://api.gitter.im/v1/rooms?access_token=GITTER_TOKEN`
    

### Многопроцессовый режим

Для запуска бота в многопроцессовом режиме выполните команду 
`php artisan gitter:pool`. Команда создаст несколько процессов для 
чатов, перечисленных в `config/gitter.php`. Каждый процесс 
идентифицируется своим pid файлом (`storage/pids/**.pid`). 

Для остановки воспользуйтесь командой `php artisan gitter:pool stop`. 
Для перезапуска - `php artisan gitter:pool restart`.
 

### Как работает?

Бот создаёт стрим-соединение по Gitter Stream API. Далее 
инициализируются Middlewares и Subscribers, определённые 
в `app/Middlewares` и `app/Subscribers` соответственно. 
Дальнейшую логику работы определяют именно они.
 
### Что такое Middlewares?

Это набор классов, предназначенных для ответа в чат на *сообщения*.

Middlewares - это каскадная система классов, вызывающаяся 
при каждом сообщении из комнаты. Каждый класс имеет свой вес, 
т.е. порядок исполнения, определённый его приоритетом. После 
вызова (метод `handle`) он имеет возможность переопределить 
или подправить сообщение, передавая его вниз по каскаду, в том 
числе и прервать выполнение последующих элементов каскада.
  
### Что такое Subscribers?

Это набор классов, предназначенных для ответа в чат на *события*.

Subscribers (подписчики) - это классы, инициализирующиеся 
при старте системы, предназначенные для создания подписок на 
события и отображения этих событий в чат. Как можно понять - 
подписчики не обязательно должны реагировать на сообщения и
могут быть полностью асинхроонными.

### Прочие классы

#### Достижения (Achievements)

Предназначены для достижений (ничоси!)

- Создайте класс в `App\Subscribers\Achievements\*`
    - Класс должен быть наследником `App\Gitter\Achieve\AbstractAchieve` (или интерфейса AchieveInterface)
- Добавьте его в массив `$achievements` внутри `App\Subscribers\AchieveSubscriber.php`

### Что ещё следует знать?

Все Middlewares и Subscribers находятся в Laravel контейнере,
а это значит, что им доступно Dependency Injection в конструкторе.

Так же в DI контейнере содержатся два базовых инстанса - 
`App\Room` и `App\Gitter\Client`. Первый отвечает за взаимодействие 
с текущей комнатой, второй за общее взаимодействие с Gitter API.

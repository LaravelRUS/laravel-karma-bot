## Gitter Bot based on Laravel 5.1

[![volkswagen status](https://auchenberg.github.io/volkswagen/volkswargen_ci.svg?v=1)](https://github.com/auchenberg/volkswagen) 
[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/LaravelRUS/GitterBot?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

### Поддерживаемые платформы
 - [SLack](https://slack.com/)
 - [Gitter](https://gitter.im/)

### Запуск

- Выполните `composer install`
- Выполните миграции `php artisan migrate`
- Сконфигурируйте работу бота для с необходимой платформой
- Введите `php artisan gitter:listen $room`, где `$room` - название либо идентификатор комнаты (Например, `KarmaBot/KarmaTest`, 
	настройки для нее должны обязательно присутсвовать в соответсвующем конфиге).
- При использовании `Domains\Bot\Middlewares\NewGoogleSearchMiddleware` необходимо указать `GOOGLE_TOKEN` 
	для сервиса `Google Custom Search` _(Не обязательно)_
		
#### Конфигурация для работы с платформой Gitter

- Параметр `GITTER_TOKEN` в файле `.env` (если такого нет, создайте из `.env.example`).
	(Токен можно узнать по [ссылке](https://developer.gitter.im/apps))
- Прочие параметры находятся в файле `config/gitter.php`
- Для запуска бота необходимо в конфиг добавить комнаты с указанием групп middleware
    
#### Конфигурация для работы с платформой SLack (alpha версия)

- Параметр `SLACK_TOKEN` в файле `.env`. 
	(Для получения токена вам необходимо [создать бота](https://my.slack.com/services/new/bot), после чего вам будет выдан токен для этого бота.)
- Прочие параметры находятся в файле `config/slack.php`
- Для запуска бота необходимо в конфиг добавить комнаты с указанием групп middleware
	
	```php
		'rooms' => [
            '$roomID' => ['*'] // Все middleware,
            '$roomID' => ['common', 'improvements']
        ],
	```

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

**Класс `Middleware` должен реалтизовывать интерфейс `Interfaces\Gitter\Middleware\MiddlewareInterface`.**
  
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

### Синтаксис текста сообщений бота.
Т.к. бот может работать с различными платформами, то внутри системы он использует собственный язык на основе 
[bbcode](https://ru.wikipedia.org/wiki/BBCode) с последующем парсингом при отправке сообщения в нужный формат (Markdown, html, e.t.c).

#### Дополнительные теги
 - `[user]username[/user]` - упоминание пользователя (Например для gitter будет преобразован в `@username`)
 - `[pre] code [/pre]` - inline code
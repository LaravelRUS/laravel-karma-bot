# Скелёт вёрстки (Stylus, Lost, Boy)

## Подготовка
Устанавливаем зависимости и `gulp` если необходимо:

```sh
$ npm install --global gulp
$ npm install
```

## Запуск
Запускаем при помощи `gulp serve`, компилирование, соединение и сжатие стилей и скриптов происходит автоматически. Для генерации спрайтов используем `gulp sprite`.

## Структура css/stylus файлов
Входной файл `src/stylus/collector.styl`. Он собирает и подключает остальные `.styl` файлы, соответственно каждый новый подключаемый файл прописываем в нём.

Типичный макет средней сложности можно верстать 3-мя файлами:
* `src/stylus/all/base.styl` — базовые стили сайта
* `src/stylus/all/main.styl` — основные стили сайта
* `src/stylus/all/media/media.styl` — медиазапросы

`collector.styl` и все подключённые в нём `.styl` файлы компилируются в `src/css/collector.css`. Все файлы в `src/css` соединяются в один `src/css/build.css`. Соответственно дополнительные `.css` файлы можно класть в `src/css`.

Файл `src/css/build.css` сжимается и кладётся в `dest/build.css`. Именно этот файл и подключается к странице.

## Структура js файлов
Все файлы из `src/js` соединяются и сжимаются в `dest/build.js` в том порядке в котором они указаны в `gulpfile.js` в корне проекта.

## Структура изображений
Иконки для спрайта кладём в `img/icons` в двух размерах — обычном и 2x для ретины. Командой `gulp sprite` генерируем спрайты.
Файл `sprites.styl` уже подключён в `collector.styl`. В стилях просто пишем `retinaSprite($<icon_filename>_group)` и для блока у нас ставится спрайт и под ретину и под обычные экраны.

## Под капотом:
### Stylus: [Boy](https://github.com/corysimmons/boy), [Lost](https://github.com/corysimmons/lost), [Rupture](https://github.com/jenius/rupture), [Nib](https://github.com/tj/nib), [Autoprefixer](https://github.com/postcss/autoprefixer)
### Sprites: [Spritesmith](https://github.com/twolfson/gulp.spritesmith)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
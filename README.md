## Установка

### Ручные корректировки

Для корректной работы в админ панели orchid нужно: в `config/platform.php` в `middleware->private` нужно добавить
`ShareIconsMiddleware:class` для подгрузки актуального спрайта.


### vendor:publish

По умолчанию все файлы будут публиковаться в `packages/svg-reuser-laravel` для удобства. Чтобы внести коррективы в 
путь публикации, нужно опубликовать конфиг `tag=config` и изменить параметр `publish_base_path`

Для публикации всех рекомендуемых файлов используйте команду:
```shell
php artisan vendor:publish --tag="recommended"
```
Скорее всего понадобится скорректировать namespace.

Публикация всех файлов пакета
```shell
php artisan vendor:publish --provider="Cat4year\SvgReuser\Providers\ServiceProvider
```
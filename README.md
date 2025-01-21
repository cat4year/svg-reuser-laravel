Для установки всех рекомендуемых файлов

```shell
php artisan vendor:publish --provider="Cat4year\SvgReuser\Providers\ServiceProvider" --tag='recommended'
```

Для корректной работы в админ панеле orchid нужно: в `config/platform.php` в `middleware->private` нужно добавить 
`ShareIconsMiddleware:class` для подгрузки актуального спрайта.
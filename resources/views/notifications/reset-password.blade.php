<x-mail::message>
# Здравствуйте!

Вы получили это письмо потому что к нам пришёл запрос на сброс Вашего пароля.
Для сброса пароля - нажмите кнопку «{{ $actionText }}».

{{-- Action Button --}}
<x-mail::button :url="$actionUrl" color="primary">
{{ $actionText }}
</x-mail::button>

Если это ошибка, и Вы не запрашивали сброс пароля на нашем сайте, то никаких дальнейших действий не требуется.

С уважением,<br>
команда проекта «{{ config('app.name') }}»!

<x-slot:subcopy>
Если возникли проблемы с кнопкой «{{ $actionText }}», скопируйте ссылку ниже в Ваш браузер, и перейдите по ней.
<br>
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }}).</span>
</x-slot:subcopy>
</x-mail::message>

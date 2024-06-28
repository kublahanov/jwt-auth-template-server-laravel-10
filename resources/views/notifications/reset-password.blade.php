<x-mail::message>
# Здравствуйте!

Для завершения регистрации на сайте, необходимо подтвердить Ваш е-мейл.

{{-- Action Button --}}
<x-mail::button :url="$actionUrl" color="primary">
{{ $actionText }}
</x-mail::button>

Если это ошибка, и Вы не регистрировались на нашем сайте, то никаких дальнейших действий не требуется.

С уважением,<br>
команда проекта «{{ config('app.name') }}»!

<x-slot:subcopy>
Если возникли проблемы с кнопкой «{{ $actionText }}», скопируйте ссылку ниже в Ваш браузер, и перейдите по ней.
<br>
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }}).</span>
</x-slot:subcopy>
</x-mail::message>

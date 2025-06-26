@component('mail::message')
# Código de Verificación

Este es tu código de verificación:

@component('mail::panel')
{{ $codigo }}
@endcomponent

Gracias por registrarte.

Saludos,  
{{ config('app.name') }}
@endcomponent

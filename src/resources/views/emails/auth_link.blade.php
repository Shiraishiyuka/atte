@component('mail::message')
# メール認証の確認

以下のボタンをクリックして、メール認証を完了してください。

@component('mail::button', ['url' => $url])
認証する
@endcomponent


このリンクは一度しか使用できません。

@lang('Regards'),<br>
{{ config('app.name') }}
@endcomponent
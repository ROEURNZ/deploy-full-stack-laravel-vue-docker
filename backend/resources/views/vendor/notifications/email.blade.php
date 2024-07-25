@component('mail::message')

<div style="text-align: center;">
    <img src="{{ asset('image.png') }}" alt="Reset Password" style="width: 100px; height: auto;">
</div>

# Hello {{ $user->name }},

We received a request to reset the password for your account associated with this email address. If you didn’t request this change, please ignore this email.

@component('mail::panel')
Your password reset request has been received, and you can reset your password using the button below. Please note that the link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.
@endcomponent

@component('mail::button', ['url' => $actionUrl])
Reset Password
@endcomponent

If you have any questions or need further assistance, please contact our support team at {{ config('mail.from.address') }}.

Thank you for using {{ config('app.name') }}!

Best regards,<br>
{{ config('app.name') }} Team

@endcomponent

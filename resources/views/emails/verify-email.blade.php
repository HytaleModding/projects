@component('mail::message')
# Email Verification Required

Hello {{ $user->name }},

Thank you for registering with {{ config('app.name') }}! To complete your registration and start creating mods, please verify your email address by clicking the button below.

@component('mail::button', ['url' => $verificationUrl])
Verify Email Address
@endcomponent

This verification link will expire in {{ config('auth.verification.expire', 60) }} minutes.

If you did not create an account with us, no further action is required.

Thanks,<br>
{{ config('app.name') }}

---
<small>This email was sent to {{ $user->email }}</small>
@endcomponent

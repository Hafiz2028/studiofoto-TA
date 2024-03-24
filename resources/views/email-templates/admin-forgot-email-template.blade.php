@if (Route::is('admin.*'))
<p>Dear {{$admin->name}}</p>
@elseif (Route::is('owner.*'))
<p>Dear {{$owner->name}}</p>
@elseif (Route::is('customer.*'))
<p>Dear {{$customer->name}}</p>
@endif
<p>
    @if (Route::is('admin.*'))
    We are received a request to reset the password for FotoYuk account associated with {{ $admin->email }}.
    You can reset your password by clicking the button below:
    @elseif (Route::is('owner.*'))
    We are received a request to reset the password for FotoYuk account associated with {{ $owner->email }}.
    You can reset your password by clicking the button below:
    @elseif (Route::is('customer.*'))
    We are received a request to reset the password for FotoYuk account associated with {{ $customer->email }}.
    You can reset your password by clicking the button below:
    @endif
    <br>
    <br>
    <a href="{{ $actionLink }}" target="_blank" style="color:#fff;border-color:#22bc66;border-style:solid;border-width:5px 10px;
    background-color:#22bc66;display:inline-block;text-decoration:none;border-radius:3px;box-shadow:0 2px 3px rgba(0,0,0,0.16);
    -webkit-text-size-adjust:none;box-sizing:border-box;">Reset Password
    </a>
    <br>
    <br>
    <b>NB:</b> This link will valid within 15 minutes
    <br>
    If you did not request for a password reset, please ignore this email.
</p>

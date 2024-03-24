@if (Route::is('admin.*'))
<p>Dear {{$admin->name}}</p>
@elseif (Route::is('owner.*'))
<p>Dear {{$owner->name}}</p>
@elseif (Route::is('customer.*'))
<p>Dear {{$customer->name}}</p>
@endif
<br>
<p>
    Your password on <b>FotoYuk</b> system was changed successfully.
    Here is your new login credentials:
    <br>
    <br>
    @if (Route::is('admin.*'))
    <b>Login ID : </b>{{$admin->username}} or {{$admin->email}}
    <br>
    <b>Password : </b>{{$new_password}}
    @elseif (Route::is('owner.*'))
    <b>Login ID : </b>{{$owner->username}} or {{$owner->email}}
    <br>
    <b>Password : </b>{{$new_password}}
    @elseif (Route::is('customer.*'))
    <b>Login ID : </b>{{$customer->username}} or {{$customer->email}}
    <br>
    <b>Password : </b>{{$new_password}}
    @endif
</p>
<br>
Please, keep your credentials confidential. Your username and password are your own credentials and you should
never share them with anybody else.
<p>
    <b>FotoYuk</b> will not be liable for any misuse of your username and password.
</p>
<br>
-----------------------------------------------------
<p>
    This email was autmatically sent by <b>FotoYuk</b> system. Do not reply it.
</p>

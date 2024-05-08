<p>Dear {{ $customer->name }}</p>
<p>
    We are receiving this email because you requested to reset your password for <b>FotoYuk</b> account associated with
    {{ $customer->email }}.
</p>
<p>You can reset your password by clicking the link below: <br> <br>
    <a href="{{ $actionLink }}" target="_blank">{{ $actionLink }}</a><br>
</p>
<p>
    <b>NB:</b> This link will valid within 15 minutes.
</p>
<p>If you are having trouble with the link above, copy and paste it into your web browser.</p>
<p>If you did not request for a password reset, please ignore this email.</p>
----------------------------------------------------------
<p>This e-mail was automatically sent by <b>FotoYuk</b>. Don't reply to it. </p>

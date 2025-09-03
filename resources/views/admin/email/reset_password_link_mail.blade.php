<div>
    Hello {{ $name }},

    <p style="font-size: 25px; color:#1143af">You have requested to reset your password. Please click the link below to reset your
        password:</p>
    <p><a style="padding: 10px; border: 2px solid black; border-radius: 15px; text-decoration: none;" href="{{ route('admin-resetPassword', ['token' => $token]) }}">Reset Password</a></p>
</div>

<p>If you did not request a password reset, please ignore this email.</p>

<p>Thank you,</p>
<p>Event Manager</p>

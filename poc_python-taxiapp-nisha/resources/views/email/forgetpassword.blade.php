

<!DOCTYPE html>
<html>
<head>
    <title>Addweb</title>
</head>
<body>
   
    {!!$mail_data[0]!!}
    <a href="{{ route('password.reset.form',[$mail_data['token']]) }}">Reset Password</a>

</body>
</html>
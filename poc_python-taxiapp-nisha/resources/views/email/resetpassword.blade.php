

<!DOCTYPE html>
<html>
<head>
    <title>Addweb</title>
</head>
<body>
    {!!$mail_data[0]!!}
   
    <a href="{{ route('reset.password',[$mail_data['token'],$mail_data['user_type']]) }}">Reset Password</a>


</body>
</html>
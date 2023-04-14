

<!DOCTYPE html>
<html>
<head>
    <title>Addweb</title>
</head>
<body>
   
    {!!$mail_data[0]!!}
    <a href="{{ route('email.verifyaccount',[$mail_data['token'],$mail_data['user_type']]) }}">Verify Account</a>

</body>
</html>
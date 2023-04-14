<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ddb77e80e6.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ ('css/style.css')}}">
    <title>Coming Soon - UVG Forex</title>
    <style>
        .email{
            text-overflow: ellipsis;
            overflow:hidden;      
            white-space:nowrap;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <header>
            <h1>UVG Forex</h1>
        </header>
        <main class="content">
            <h1>COMING SOON!</h1>
            <p>Our website is under construction. We`ll be here soon<br />with our new awesome site. Subscribe to be
                notified.
            </p>
            <form action="{{ route('contact.store')}}" method="POST">
                @csrf
                <input id="email" class="email" name="email" type="email" placeholder="Your Email Address" aria-label="Email" />
                <input type="submit" name="submit" aria-label="Notify Me" />
                @if ($message = Session::get('success'))
                    <div class="alert-success">
                        <strong>{!! $message !!}</strong>
                    </div>
                @endif 
                @if ($errors->any())
                    <div class="alert-warning">
                        @foreach ($errors->all() as $error)
                            <strong>{{ $error }}</strong>
                        @endforeach
                    </div>
                @endif
            </form>
        </main>
    </div>

</body>

</html>
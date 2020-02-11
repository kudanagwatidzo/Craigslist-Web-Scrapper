<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Craigslist Web Scraper</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <style>
            .pagination {
                clear: both;
                position: relative;
                text-align: center;
            }
            div {
                text-align: center;
                font-family: 'Roboto', sans-serif;
            }
        </style>
    </head>
    <body>
            <div id="letsnavigate" class="text-xs-center"> {{ $results->links() }} </div>
            <div class="container-fluid">
                @foreach($results as $value)
                    <div class='d-inline-block bg-warning'>
                        <h2> {{ $value->Title }}</h2>
                        <img src= {{ $value->ImageLink }} class="img-fluid" >
                        <p>Price: {{ $value->Price }} </p>
                        <p>Description: {{ $value->Description }} </p>
                        <a href={{ $value->LocationLink }}>Go to Google Maps Link</a>
                        <p>Item Posted: {{ $value->PostedDate }} </p>
                        <p>Craigslist ID: {{ $value->CraigslistID }} </p>
                        <a href='/edit/{{ $value->id }}'>EDIT</a>
                    </div>    
            @endforeach
            </div>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>
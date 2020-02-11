<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Craigslist Item</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <style>
            body {
                width: 100%;
                background-color: #ffaa3e;
            }
            section {
                font-family: 'Roboto', sans-serif;
                text-align: center;
            }
            input {
                display: block;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <section>
            {{ Form::model($selected, array('url' => 'form-submit')) }} 
                {{ Form::label('Title', 'Change Name of Craigslist Item') }}
                {{ Form::text('Title') }}
                {{ Form::label('ImageLink', 'Change the Image URL of the Item') }}
                {{ Form::text('ImageLink') }}
                {{ Form::label('Price', 'Change the Listed Price of the Item') }}
                {{ Form::text('Price') }}
                {{ Form::label('Description', 'Change the Description of the Item') }}
                {{ Form::text('Description') }}
                {{ Form::label('LocationLink', 'Change the Location URL of the Item') }}
                {{ Form::text('LocationLink') }}
                {{ Form::hidden('id') }}
                {{ Form::submit('Upload Changes') }}
            {{ Form::close() }}
        </section>
    </body>
</html>
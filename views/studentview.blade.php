<!doctype html>
<html>
<head>
    <title>GithubScraper</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<div class="flexcontainer">
    <pre><?php echo($debug) ?></pre>
</div>


<div class="flexcontainer wrap column">

    @foreach ($students as $student)
    <div class="flexitem">
        <div class="inner" style="margin:5px;">
            @if(isset($student->trello_avatar_base64))
                <img style="float:right;" src="data:image/png;base64,{{ $student->trello_avatar_base64 or '' }}">
            @endif
            <span style="font-size:200%;">{{ $student->displayname }}</span>
            <span style="color: gray;">{{ $student->fullname }}</span><br>

            <span style=""><a target="_blank" href="https://trello.com/b/{{ $student->trello_board_1 or '#' }}"><img style="width:20px; height:20px;" src="img/trello.png"> {{ $student->trello_activeboard_name }}</a></span>
        </div>
    </div>
    @endforeach

</div>

</body>
</html>

<!doctype html>
<html>
<head>
    <title>GithubScraper</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

@if (false && isset($_SESSION['debug']))
<div class="debug" style="border: 1px solid red;">
    <ul>
    @foreach($_SESSION['debug'] as $dbg)
        <li>{{ $dbg }}</li>
    @endforeach
    </ul>
</div>
    <?php $_SESSION['debug'] = []; ?>
@endif

@foreach ($scrapers as $scraper)
<div style="display:flex; align-items: flex-start;" >
    <div class="vcard">
        <span class="displayname">{!! $scraper->displayName !!}</span>
        <br>
        <span class="accountname"><a href="{!! $scraper->url !!}">{!! $scraper->url !!}</a></span>
        <br>
        {{-- <span class="displayName">{!! $scraper->githubDisplayName !!}</span> --}}
        <img class="avatar" style="width:50px;" src="{!! $scraper->avatarurl !!}"/>
    </div>
    <div class="calendar-graph">{!! $scraper->activitysvg !!}</div>
</div>
@endforeach

</body>
</html>

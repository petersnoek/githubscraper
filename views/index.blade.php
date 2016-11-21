<!doctype html>
<html>
<head>
    <title>GithubScraper</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

@foreach ($scrapers as $scraper)
<div style="display:flex; align-items: flex-start;" >
    <span class="naam">{!! $scraper->displayName !!}</span><br>
    <span class="displayName">{!! $scraper->githubDisplayName !!}</span>
    {{-- <img class="avatar" style="width:100px;" src="{!! $scraper->avatarurl !!}"/> --}}
    <div class="calendar-graph">{!! $scraper->activitysvg !!}</div>
</div>
@endforeach

</body>
</html>

<meta name="description" content="{{ setting('site.name') }}">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ setting('site.name') }}">
<meta property="og:title" content="{{ setting('site.name') }}">
<meta property="og:url" content="{{ URL::to('/') }}">
<meta property="og:image" content="{{ asset('assets/images/icon/'.setting('site.icon')) }}">
<meta property="og:description" content="{{ setting('site.name') }}">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
@if (Auth::check())
@if (Auth::user()->role==role('it'))
<meta name="supported-color-schemes" content="dark light">
<meta name="color-scheme" content="dark light">
<meta name="theme-color" content="">
@endif
@endif
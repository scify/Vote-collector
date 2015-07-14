{{--
    Link for the navbar. If the url matches the $link variable, the li has the "active" class
    for bootstrap to make it look pressed
--}}
<li class="{{ Request::path() == $link ? 'active' : '' }}">
    <a href="/{{ $link }}">{{ $linkText }}</a>
</li>
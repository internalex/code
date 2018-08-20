<?php
function unparse_url($parsed_url) {
    $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
    $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
    $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
    $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
    $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
    $pass     = ($user || $pass) ? "$pass@" : '';
    $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
    $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
    $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
    return "$scheme$user$pass$host$port$path$query$fragment";
}

function removeQueryParam($url, $param_to_remove) {
    $parsed = parse_url($url);
    if ($parsed && isset($parsed['query'])) {
        $parsed['query'] = implode('&', array_filter(explode('&', $parsed['query']), function($param) use ($param_to_remove) {
            return explode('=', $param)[0] !== $param_to_remove;
        }));
        if ($parsed['query'] === '') unset($parsed['query']);
        return unparse_url($parsed);
    } else {
        return $url;
    }
}

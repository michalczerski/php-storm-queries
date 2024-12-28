<?php

function remove_new_lines($text   ): string
{
    return str_replace("\n", " ", $text);
}

function get_nth_line($text, $i): ?string
{
    $element = explode("\n", $text);
    if ($i <= count($element)) {
        return $element[$i];
    }
    return null;
}
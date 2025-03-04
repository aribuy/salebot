<?php

namespace App\Helpers;

class TextHelper
{
    public static function transformText($text)
    {
        // Strikethrough for text like: ~text~
        $text = preg_replace('/~(.*?)~/', '<del>$1</del>', $text);

        // Italic for text like: _text_
        $text = preg_replace('/_(.*?)_/', '<i>$1</i>', $text);

        // Bold for text like: *text*
        $text = preg_replace('/\*(.*?)\*/', '<b>$1</b>', $text);

        // Monospace for text like: ```text```
        $text = preg_replace('/```(.*?)```/', '<code>$1</code>', $text);

        // Replace newlines with <br>
        $text = nl2br($text); // Do not use e() here to prevent double escaping

        return $text;
    }
}

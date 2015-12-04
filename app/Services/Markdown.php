<?php

namespace App\Services;

use ParsedownExtra;

class Markdown extends ParsedownExtra {

    // Pattern to search for 'article#000, ArTicle#00, A#0, a#00, ...' mention
    const PATTERN_ARTICLE = '/(article|a)\#(?P<id>\d+)/i';

    /**
     * Add link to another articles
     *
     * @param $text
     * @return mixed|string
     */
    public function text($text) {
        if (preg_match(self::PATTERN_ARTICLE, $text, $matches) > 0) {
            $text = preg_replace_callback(self::PATTERN_ARTICLE, function ($matches) {
                return sprintf(
                    "<a href='%s'>%s</a>",
                    route('articles.show', $matches['id']),
                    $matches[0]
                );
            }, $text);
        }

        return parent::text($text);
    }
}
<?php

if (!function_exists('truncateDescription')) {
    function truncateDescription($description, $wordLimit) {
        $words = explode(' ', $description);
        if (count($words) > $wordLimit) {
            return implode(' ', array_slice($words, 0, $wordLimit)) . '...';
        }
        return $description;
    }
}
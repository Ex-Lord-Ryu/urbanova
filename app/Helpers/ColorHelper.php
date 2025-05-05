<?php

namespace App\Helpers;

use App\Models\Color;

class ColorHelper
{
    /**
     * Get the hex code for a color by name
     *
     * @param string $colorName The name of the color
     * @return string The hex code with # prefix
     */
    public static function getColorHex($colorName)
    {
        // Check if it's a Color model instance
        $color = Color::where('name', $colorName)->first();
        if ($color && $color->hex_code) {
            return '#' . $color->hex_code;
        }

        // Fallback to hash function
        $hash = 0;
        for ($i = 0; $i < strlen($colorName); $i++) {
            $hash = ord($colorName[$i]) + (($hash << 5) - $hash);
        }

        $c = dechex($hash & 0xFFFFFF);
        return '#' . str_pad($c, 6, '0', STR_PAD_LEFT);
    }
}

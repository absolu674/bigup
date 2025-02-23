<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use getID3;

class VideoDurationRule implements Rule
{
    protected $maxDuration;

    public function __construct($maxDuration = 60)
    {
        $this->maxDuration = $maxDuration;
    }

    public function passes($attribute, $value)
    {
        if (!$value->isValid()) {
            return false;
        }

        $getID3 = new getID3();
        $fileInfo = $getID3->analyze($value->getPathname());

        if (!isset($fileInfo['playtime_seconds'])) {
            return false;
        }

        return $fileInfo['playtime_seconds'] <= $this->maxDuration;
    }

    public function message()
    {
        return "La durée de la vidéo ne doit pas dépasser {$this->maxDuration} secondes.";
    }
}

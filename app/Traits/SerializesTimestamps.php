<?php

namespace App\Traits;

trait SerializesTimestamps
{
    /**
     * Serialize timestamps according to our desired format.
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}

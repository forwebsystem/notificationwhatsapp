<?php

namespace ForWebSystem\NotificationWhatsApp\Contracts;

interface FivezapInterface
{
    public function prepare();
    public function message(string $message);
}

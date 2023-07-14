<?php

namespace ForWebSystem\NotificationWhatsApp\Contracts;

interface FivezapInterface
{
    public function prepare();
    public function message(string $message);
    public function searchContact();
    public function createContact();
    public function getContactConversation();
    public function createConversation();
}

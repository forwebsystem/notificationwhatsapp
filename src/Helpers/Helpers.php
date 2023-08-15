<?php

namespace ForWebSystem\NotificationWhatsApp\Helpers;

class Helpers
{
    /**
     * Remove ou adiciona nono digito em um dado telefone.
     *
     * @param string $phone
     * @return string
     */
    public static function changePhoneDigit(string $phone): string
    {
        $digit = '9';
        
        // lado a da string +5562.
        $a_side = substr($phone, 0, 5);

        // lado b da string tudo após +5562.
        $b_side = substr($phone, 5, strlen($phone));

        // adiciona nono digito.
        if (strlen($b_side) == 8) {
            $phone = $a_side . $digit . $b_side;
        }

        // remove nono digito.
        if (strlen($b_side) == 9) {
            $phone = $a_side . substr($b_side, 1, strlen($b_side));
        }

        return $phone;
    }
}

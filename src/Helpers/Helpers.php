<?php

namespace ForWebSystem\NotificationWhatsApp\Helpers;

use ForWebSystem\NotificationWhatsApp\Exceptions\FivezapException;

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

    /**
     * Valida uma url qualquer pelo cabeçalho.
     *
     * @param string $url
     * @return boolean
     */
    public static function urlExists(string $url): bool
    {
        return str_contains(get_headers($url)[0], "200 OK");
    }

    /**
     * Procesa e faz a validação do arquivo.
     *
     * @param string $path
     * @param array $mime_types
     * @return object
     */
    public static function processFile(string $path, array $mime_types): object
    {
        // Verifica se url é válida.
        if(!Self::urlExists($path)) {
            throw new FivezapException("URL é inválida. ($path)");
        }

        // dados brutos do arquivo.
        $attachment = file_get_contents($path);

        // instancia de finfo.
        $finfo = new \finfo(FILEINFO_MIME_TYPE);

        // mime type, nome e extensão do arquivo.
        $filename = strtolower(basename($path));
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $mime = $finfo->buffer($attachment);

        // verifica tipos permitidos.
        if (!in_array($mime, $mime_types)) {
            // string de formatos aceitos.
            $types = implode(', ', array_keys($mime_types));
            throw new FivezapException("Formato de arquivo não permitido, apenas... $types.");
        }

        // Objeto com o arquivo.
        $file = new \stdClass();
        $file->content = $attachment;
        $file->name = $filename;
        $file->extension = ".$ext";
        $file->mime = $mime;
        
        return $file;
    }
}

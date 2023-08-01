<?php

namespace ForWebSystem\NotificationWhatsApp\Exceptions;

use Exception;

class FivezapException extends Exception
{
    protected $statusCode;

    public function __construct($message, $statusCode = null, $code = 0, Exception $previous = null)
    {
        // Chama o construtor da classe pai (Exception) para configurar a mensagem e o código da exceção
        parent::__construct($message, $code, $previous);
        // Define o código de status da resposta da API associado à exceção
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function __toString()
    {
        // Se o código de status estiver disponível, retorna a mensagem de erro junto com o código de status da API
        if ($this->statusCode !== null) {
            return "[{$this->statusCode}] {$this->message}";
        }

        // Caso contrário, retorna apenas a mensagem de erro
        return $this->message;
    }
}

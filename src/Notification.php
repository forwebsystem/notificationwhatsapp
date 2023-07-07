<?php

namespace ForWebSystem\NotificationWhatsApp;

use ForWebSystem\NotificationWhatsApp\Contracts\NotificationInterface;
use ForWebSystem\NotificationWhatsApp\Traits\RequestTrait;

abstract class Notification implements NotificationInterface
{
    use RequestTrait;

    /**
     * Conteudo da mensagem.
     *
     * @var string
     */
    protected string $content = '';

    /**
     * Metodo para a requisição atual.
     *
     * @var string
     */
    protected string $method;

    /**
     * Endpoint a ser acessado.
     *
     * @var string
     */
    protected string $end_point;

    /**
     * Headers da requisição atual.
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Body da requisição atual.
     *
     */
    protected $body = null;

    /**
     * Array de dados a ser usado na requisição.
     *
     * @var array
     */
    protected array $data = [];

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * Faz o envio da mensagem.
     *
     * @return boolean
     */
    public function send(): bool
    {
        return $this->request($this->method, $this->end_point, $this->data);
    }
}

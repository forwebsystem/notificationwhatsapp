# NotificationWhatsApp

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require forwebsystem/notificationwhatsapp
```

## Usage

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email joaopaulo@forwebsystem.com.br instead of using the issue tracker.

## Credits

- [João Paulo Paranahyba][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/forwebsystem/notificationwhatsapp.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/forwebsystem/notificationwhatsapp.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/forwebsystem/notificationwhatsapp/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/forwebsystem/notificationwhatsapp
[link-downloads]: https://packagist.org/packages/forwebsystem/notificationwhatsapp
[link-travis]: https://travis-ci.org/forwebsystem/notificationwhatsapp
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/forwebsystem
[link-contributors]: ../../contributors

** Enviando uma mensagem via Channel 

use ForWebSystem\NotificationWhatsApp\Broadcasting\NotificationWhatsAppChannel;
use ForWebSystem\NotificationWhatsApp\Services\Interfaces\NotificacaoMensagensInterface;

     /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            NotificationWhatsAppChannel::class
        ];
    }
    
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toWhatsApp($notifiable, NotificacaoMensagensInterface $configuracao)
    {
        return $configuracao->sendText($phone, $texto);
    }


** Via service 

use ForWebSystem\NotificationWhatsApp\Services\NotificacaoZApiService;

$notificacao = new NotificacaoZApiService($usuario, $idInstancia, $license);

$notificacao->sendText($phone, $texto);

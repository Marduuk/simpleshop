<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
/**
 * Class MailerService
 * @package App\Service
 */
class MailerService
{
    /** @var MailerInterface  */
    private $mailer;

    /**
     * MailerService constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendNewProductNotification(): void
    {
        $email = (new Email())
            ->from('kacper_shop@shop.com')
            ->to('fake@example.com')
            ->subject('New product.')
            ->text('New product has been added.');

        $this->mailer->send($email);
    }
}
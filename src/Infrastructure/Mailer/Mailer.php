<?php

declare(strict_types=1);

namespace App\Infrastructure\Mailer;

use App\Domain\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailerInterface;
use Symfony\Component\Mime\Email;

final readonly class Mailer implements MailerInterface
{
    public function __construct(
        private SymfonyMailerInterface $mailer,
        private string $emailAddress,
    ) {}

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(string $to, string $subject, string $content): void
    {
        $email = (new Email())
            ->from($this->emailAddress)
            ->to($to)
            ->subject($subject)
            ->text($content);

        $this->mailer->send($email);
    }
}

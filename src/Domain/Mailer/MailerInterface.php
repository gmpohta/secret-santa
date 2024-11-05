<?php

declare(strict_types=1);

namespace App\Domain\Mailer;

interface MailerInterface
{
    public function sendEmail(string $to, string $subject, string $content): void;
}

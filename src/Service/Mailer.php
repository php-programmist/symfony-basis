<?php

namespace App\Service;

use App\Request\CallbackFormRequest;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    /**
     * @var MailerInterface
     */
    protected $mailer;
    
    /**
     * @var RecipientResolverService
     */
    protected $recipient_resolver;
    /**
     * @var Address
     */
    private $from;
    
    public function __construct(
        MailerInterface $mailer,
        ConfigService $config,
        RecipientResolverService $recipient_resolver
    ) {
        $this->mailer             = $mailer;
        $fromEmail                = $config->get('mail.fromEmail','');
        $fromName                 = $config->get('mail.fromName','');
        $this->from               = new Address($fromEmail, $fromName);
        $this->recipient_resolver = $recipient_resolver;
    }
    
    public function sendConsultationMessage(CallbackFormRequest $request): TemplatedEmail
    {
        $recipients = $this->recipient_resolver->getRecipients($request->phone);
        
        $email      = (new TemplatedEmail())
            ->from($this->from)
            ->to(...$recipients)
            ->subject("Заказ обратного звонка")
            ->htmlTemplate('mail/callback/consultation.html.twig')
            ->context((array)$request);
        
        $this->mailer->send($email);
        
        return $email;
    }
}
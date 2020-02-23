<?php

namespace App\Service;

class RecipientResolverService
{
    private $recipients_dev;
    private $recipients;
    
    public function __construct(ConfigService $configs)
    {
        $this->recipients_dev = $configs->get('mail.recipients_dev','');
        $this->recipients_dev = explode(',', $this->recipients_dev);
        $this->recipients_dev = array_map('trim', $this->recipients_dev);
        $this->recipients = $configs->get('mail.recipients','');
        $this->recipients = explode(',', $this->recipients);
        $this->recipients = array_map('trim', $this->recipients);
    }
    
    public function getRecipients(string $phone)
    {
        $phoneCheck = preg_replace('~\D+~','',$phone);
        
        $recipients = $this->recipients_dev;
        if(!stristr($phoneCheck,'71111111111')){
            $recipients = array_merge($recipients,$this->recipients);
        }
        return $recipients;
    }
}
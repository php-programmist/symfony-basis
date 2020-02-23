<?php

namespace App\Controller\Mail;

use App\Request\CallbackFormRequest;
use App\Response\MailJsonResponse;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/callback", name="callback_")
 */
class CallbackController extends AbstractController
{
   
    /**
     * @var MailJsonResponse
     */
    protected $response;
    /**
     * @var Mailer
     */
    protected $mailer;
    
    public function __construct(MailJsonResponse $response,Mailer $mailer)
    {
        $this->response = $response;
        $this->mailer = $mailer;
    }
    
    /**
     * @Route("/consultation", name="consultation",methods={"POST"})
     */
    public function consultation(CallbackFormRequest $request)
    {
        $this->mailer->sendConsultationMessage($request);
        return $this->response->success("Спасибо, отправлено!");
    }
    
}

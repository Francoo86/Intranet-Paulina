<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class SendEmailController extends AbstractController
{
    #[Route('/send/email', name: 'app_send_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        try{
            $email = (new Email())
                ->from('sendersig@gmail.com')
                ->to('gxnzxlx.9@gmail.com')
                ->subject('SIG-GMAIL')
                ->text('Sending emails is fun fun fun fun!')
                ->html('<p>Correo de prueba SIG</p>');

            $mailer->send($email);
            return new Response('Correo enviado con exito :)');
        }catch(\Throwable $th){
            return new Response($th->getMessage());
        }
    }
}
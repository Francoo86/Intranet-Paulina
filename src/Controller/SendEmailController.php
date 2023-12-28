<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CustomerRepository;

class SendEmailController extends AbstractController
{
    #[Route('/send/email', name: 'app_send_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        try{
            $email = (new Email())
                ->from('sendersig@gmail.com')
                ->to('example@gmail.com')
                ->subject('SIG-GMAIL')
                ->text('Sending emails is fun!')
                ->html('<p>Correo de prueba SIG</p>');

            $mailer->send($email);
            return new Response('Correo enviado con exito :)');
        }catch(\Throwable $th){
            return new Response($th->getMessage());
        }
    }

    #[Route('/send/AllEmail/test', name: 'app_send_email')]
    public function sendAllEmailTest(MailerInterface $mailer, CustomerRepository $customerRepository): Response
    {
        try {
            // Obtén todos los clientes desde el repositorio
            $customers = $customerRepository->findAll();

            // Crea una lista de destinatarios
            $recipients = [];
            foreach ($customers as $customer) {
                $recipients[] = $customer->getEmail();
            }

            $email = (new Email())
                ->from('sendersig@gmail.com')
                ->to(...$recipients)
                ->subject('SIG-GMAIL')
                ->text('Sending emails is fun!')
                ->html('<p>Correo de prueba SIG</p>');

            $mailer->send($email);
            return new Response('Correo enviado con éxito :)');
        } catch (\Throwable $th) {
            return new Response($th->getMessage());
        }
    }

    #[Route('/send/AllEmail', name: 'app_send_email')]
    public function sendAllEmail(MailerInterface $mailer, CustomerRepository $customerRepository): Response
    {
        try {
            $customers = $customerRepository->findAll();
    
            foreach ($customers as $customer) {
                $name = $customer->getName();
                $organisation = $customer->getOrganisation();
                $emailAddress = $customer->getEmail();
                $message = "Hola $name de la organización $organisation";
    
                $email = (new Email())
                    ->from('sendersig@gmail.com')
                    ->subject('SIG-GMAIL')
                    ->addTo($emailAddress)
                    ->text($message);
    
                $mailer->send($email);
            }
    
            return new Response('Correos enviados con éxito !!!');
        } catch (\Throwable $th) {
            return new Response($th->getMessage());
        }
    }


    #[Route('/send/AllEmail/Data', name: 'app_send_email')]
    public function sendAllEmailData(MailerInterface $mailer, CustomerRepository $customerRepository): Response
    {
        $customers = $customerRepository->findAll();
    
        foreach ($customers as $customer) {
            $name = $customer->getName() ?? 'to be defined';
            $organisation = $customer->getOrganisation() ?? 'to be defined';
            $phone = $customer->getPhone() ?? 'to be defined';
            $emailAddress = $customer->getEmail() ?? 'to be defined';
    
            $publicityDetails = [];
            foreach ($customer->getPublicity() as $publicity) {
                $publicityDetails[] = $publicity->getSentence();
            }
            $publicityDetails = !empty($publicityDetails) ? implode(', ', $publicityDetails) : 'to be defined';

            $paymentDetails = [];
            foreach ($customer->getPayment() as $payment) {
                $paymentDetails[] = $payment->getAmount();
            }
            $paymentDetails = !empty($paymentDetails) ? implode(', ', $paymentDetails) : 'to be defined';

            $summaryDetails = [];
            foreach ($customer->getSummary() as $summary) {
                $summaryDetails[] = $summary->getId();
            }
            $summaryDetails = !empty($summaryDetails) ? implode(', ', $summaryDetails) : 'to be defined';

            $notificationDetails = [];
            foreach ($customer->getNotification() as $notification) {
                $notificationDetails[] = $notification->getMessage();
            }
            $notificationDetails = !empty($notificationDetails) ? implode(', ', $notificationDetails) : 'to be defined';
                
            $message = "Hola $name de la organización $organisation. Phone: $phone. Publicity: $publicityDetails. Payment: $paymentDetails. Summary: $summaryDetails. Notification: $notificationDetails.";
    
            $email = (new Email())
                ->from('sendersig@gmail.com')
                ->subject('SIG-GMAIL')
                ->addTo($emailAddress)
                ->text($message);
    
            $mailer->send($email);
        }
    
        return new Response('Correos enviados con éxito !!!');
    }
    

    
}
<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SendEmailController extends AbstractController
{
    #[Route('/send/email', name: 'app_send_email_exito', methods: ['POST'])]
    public function sendEmail(MailerInterface $mailer, Request $req): JsonResponse
    {
        try{
            $request_email = $req->get('email');
            //Check if email exists.
            $request_email = $request_email != null ? $request_email : "example@gmail.com";

            //The same check for message.
            $request_msg = $req->get('message');
            $request_msg = $request_msg != null ? $request_msg : "Correo de prueba SIG.";

            $email = (new Email())
                ->from('sendersig@gmail.com')
                ->to($request_email)
                ->subject('SIG-GMAIL')
                //->text('Sending emails is fun!')
                ->html(sprintf('<p>%s</p>', $request_msg));

            $mailer->send($email);

            return $this->json(array(
                ["message" => sprintf("Message was sent to %s successfully.", $request_email)]
            ));
        }catch(\Throwable $th){
            return $this->json(array(["message" => $th->getMessage(), "data" => $request_email]));
        }
    }

    #[Route('/send/AllEmail/test', name: 'app_send_email', methods: ['POST'])]
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

    #[Route('/send/AllEmail', name: 'app_send_email', methods: ['POST'])]
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


    #[Route('/send/AllEmail/Data', name: 'app_send_email', methods: ['POST'])]
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
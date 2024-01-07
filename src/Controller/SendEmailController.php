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
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Guideline;
use App\Entity\Manager;
use Symfony\Component\Security\Core\Security;
use App\Repository\GuidelineRepository;
use App\Repository\ShowRepository;
use App\Repository\PublicityRepository;

class SendEmailController extends AbstractController
{

    private $security;
    public function __construct(Security $security) 
    {
        $this->security = $security;
    }

    #[Route('/send/email', name: 'app_send_email_exito', methods: ['POST'])]
    public function sendEmail(MailerInterface $mailer, Request $req): JsonResponse
    {
        try{
            $request_email = $req->get('email');
            $request_email = $request_email != null ? $request_email : "example@gmail.com";

            $request_msg = $req->get('message');
            $request_msg = $request_msg != null ? $request_msg : "Correo de prueba SIG.";

            $email = (new Email())
                ->from('sendersig@gmail.com')
                ->to($request_email)
                ->subject('Test mail')
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

    #[Route('/send/AllEmail/test', name: 'app_send_email_test')]
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
                ->subject('Spam')
                ->text('Sending emails is fun!')
                ->html('<p>Correo de prueba SIG</p>');

            $mailer->send($email);
            return new Response('Correo enviado con éxito :)');
        } catch (\Throwable $th) {
            return new Response($th->getMessage());
        }
    }

    #[Route('/send/AllEmail', name: 'app_send_all_email', methods: ['POST'])]
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
                    ->subject('Saludo masivo')
                    ->addTo($emailAddress)
                    ->text($message);
    
                $mailer->send($email);
            }
    
            return new Response('Correos enviados con éxito !!!');
        } catch (\Throwable $th) {
            return new Response($th->getMessage());
        }
    }


    #[Route('/send/AllEmail/Data', name: 'app_send_all_email_data', methods: ['POST'])]
    public function sendAllEmailData(MailerInterface $mailer, CustomerRepository $customerRepository, Environment $twig): Response
    {
        try {
            $customers = $customerRepository->findAll();
        } catch (\Exception $e) {
            return new Response('Error retrieving customers from the database');
        }
    
        foreach ($customers as $customer) {
            $name = $customer->getName() ?? 'UndefinedName';
            $organisation = $customer->getOrganisation() ?? 'UndefinedOrganisation';
            $emailAddress = $customer->getEmail() ?? 'UndefinedEmail';
            
            foreach ($customer->getPublicity() as $publicity) {
                if($publicity === null){
                    continue;
                }

                if($publicity->getStock()->getBalance()->isActive()){

                    $stock = $publicity->getStock()  ?? 'UndefinedStock';
                    $balance = $stock->getBalance()  ?? 'UndefinedBalance';
                    $amountStock = $stock->getAmount()  ?? 'UndefinedAmountStock';
                    $amountBalance = $balance->getAmount() ?? 'UndefinedAmountBalance';
                    $audience = $publicity->getAudience()->getDemography() ?? 'UndefinedAudience';
                    $locality = $publicity->getAudience()->getLocality() ?? 'UndefinedLocality';
                    
                    $stockTime = $publicity->getStock()->getTime();
                    $publicityTime = $publicity->getDuration();
                    $totalEmisions = ($stockTime / $publicityTime);
                    $totalEmisions = number_format($totalEmisions, 0);

                    $totalAmount = $publicity->getStock()->getAmount();
                    $balanceAmount = $publicity->getStock()->getBalance()->getAmount();
                    $percentageAmount = 100-($balanceAmount / $totalAmount) * 100;
                    $percentageAmount = number_format($percentageAmount, 2);
                    
                    try{
                        $message = $twig->render('email/info_email.html.twig', [
                            'name' => $name,
                            'organisation' => $organisation,
                            'amountStock' => $amountStock,
                            'amountBalance' => $amountBalance,
                            'audience' => $audience,
                            'locality' => $locality,
                            'totalEmisions' => $totalEmisions,
                            'percentageAmount' => $percentageAmount
                        ]);
                    } catch (\Exception $e) {
                        return new Response('Error in template email: ' . $e->getMessage());
                    }

                    $email = (new Email())
                        ->from('sendersig@gmail.com')
                        ->subject('Actualización de Estado - RadioPaulina')
                        ->addTo($emailAddress)
                        ->html($message);
                    try {
                        $mailer->send($email);
                    } catch (\Exception $e) {
                        return new Response('Error sending email');
                    }
                        
                }
            }
        }
    
        return new Response('Information emails sent successfully');
    }

    #[Route('/send/AlertEmail/Data', name: 'app_send_alert_email_data', methods: ['GET', 'POST'])]
    public function sendAlertEmailData(MailerInterface $mailer, CustomerRepository $customerRepository, Environment $twig): Response
    {
        try {
            $customers = $customerRepository->findAll();
        } catch (\Exception $e) {
            return new Response('Error retrieving customers from the database');
        }

        $alertAmount = 1000;

        foreach ($customers as $customer) {
            foreach ($customer->getPublicity() as $publicity) {
                $stock = $publicity->getStock();

                if ($stock && $stock->getBalance() && $stock->getBalance()->getAmount() < $alertAmount) {
                    
                    $name = $customer->getName() ?? 'UndefinedName';
                    $organisation = $customer->getOrganisation() ?? 'UndefinedOrganisation';
                    $emailAddress = $customer->getEmail() ?? 'UndefinedEmail';
                    $amount = $publicity->getStock()->getBalance()->getAmount() ?? 'UndefinedAmount';

                    $totalAmount = $publicity->getStock()->getAmount();
                    $balanceAmount = $publicity->getStock()->getBalance()->getAmount();
                    $percentageAmount = 100-($balanceAmount / $totalAmount) * 100;
                    $percentageAmount = number_format($percentageAmount, 2);

                    try {
                        $message = $twig->render('email/alert_email.html.twig', [
                            'name' => $name,
                            'organisation' => $organisation,
                            'publicity' => $publicity,
                            'amount' => $amount,
                            'percentageAmount' => $percentageAmount
                        ]);
                    } catch (\Exception $e) {
                        return new Response('Error in template email: ' . $e->getMessage());
                    }

                    $email = (new Email())
                        ->from('sendersig@gmail.com')
                        ->subject('¡Atención! Saldo Crítico en tu publicidad en RadioPaulina')
                        ->addTo($emailAddress)
                        ->html($message);

                    try {
                        $mailer->send($email);
                    } catch (\Exception $e) {
                        return new Response('Error sending email');
                    }
                }
            }
        }

        return new Response('Alert emails sent successfully');
    }

    #[Route('/send/GuidelineEmail/Data', name: 'app_send_guideline_email', methods: ['GET', 'POST'])]
    public function sendGuidelineEmail(MailerInterface $mailer, GuidelineRepository $guidelineRepository, ShowRepository $showRepository, PublicityRepository $publicityRepository, Environment $twig): Response
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw $this->createNotFoundException('No user is logged in.');
        }
        $emailAddress = $user->getUserIdentifier(); //email
        $guidelineNumber = 1;   //num pauta
    
        $guideline = $guidelineRepository->findOneBy(['emission_number' => $guidelineNumber]);
        if (!$guideline) {
            throw $this->createNotFoundException('No guideline found for number '.$guidelineNumber);
        }
    
        $creationDate = $guideline->getCreationDate()->format('Y-m-d');
        $broadcasterFirstName = $guideline->getBroadcaster()->getFirstName();
        $broadcasterLastName = $guideline->getBroadcaster()->getLastName();
    
        $shows = $guideline->getShows();
    
        $showsData = [];
        foreach ($shows as $show) {
            $showData = [
                'name' => $show->getName(),
                'startTime' => $show->getStart(),
                'endTime' => $show->getFinish(),
                'publicities' => [],
            ];
    
            $publicities = $show->getPublicities();
            foreach ($publicities as $publicity) {
                $showData['publicities'][] = [
                    'sentence' => $publicity->getSentence(),
                    'duration' => $publicity->getDuration(),
                    'demography' => $publicity->getAudience()->getDemography(),
                    'locality' => $publicity->getAudience()->getLocality(),
                    'stockAmount' => $publicity->getStock()->getAmount(),
                    'balanceAmount' => $publicity->getStock()->getBalance()->getAmount(),
                ];
            }
    
            $showsData[] = $showData;
        }
    
        try {
            $message = $twig->render('email/report.html.twig', [
                'guidelineNumber' => $guidelineNumber,
                'creationDate' => $creationDate,
                'broadcasterFirstName' => $broadcasterFirstName,
                'broadcasterLastName' => $broadcasterLastName,
                'showsData' => $showsData,
            ]);
        } catch (\Exception $e) {
            return new Response('Error in template email: ' . $e->getMessage());
        }
        $email = (new Email())
            ->from('sendersig@gmail.com')
            ->subject("Resumen de pauta N°$guidelineNumber")
            ->addTo('gxnzxlx.9@gmail.com')
            ->html($message);
    
        try {
            $mailer->send($email);
        } catch (\Exception $e) {
            return new Response('Error sending email');
        }
    
        return new Response('Guideline email sent successfully');
    }


}
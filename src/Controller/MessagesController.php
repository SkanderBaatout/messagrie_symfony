<?php


namespace App\Controller;


use App\Entity\Messages;
use App\Form\MessagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/messages', name: 'messages')]
    public function index(): Response
    {
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }
     /**
     * @Route("/send", name="send")
     */
    public function send(Request $request): Response
    {
        $message = new Messages;
        $form = $this->createForm(MessagesType::class, $message);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $message->setSender($this->getUser());

            $this->entityManager->persist($message);
            $this->entityManager->flush();

            $this->addFlash("message", "Message envoyé avec succés.");
            return $this->redirectToRoute("messages");
        }

        return $this->render("messages/send.html.twig", [
            "form" => $form->createView()
        ]);
    }
    /**
     * @Route("/received", name="received")
     */
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }
    /**
     * @Route("/sent", name="sent")
     */
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig');
    }


     /**
     * @Route("/read/{id}", name="read")
     */
    public function read(Messages $message): Response
    {
        $message->setIsRead(true);
        
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $this->render('messages/read.html.twig', compact("message"));
    }

     /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Messages $message): Response
    {
        
        $this->entityManager->remove($message);
        $this->entityManager->flush();

        return $this->redirectToRoute("received");
    }
}

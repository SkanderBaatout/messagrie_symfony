<?php

namespace App\Controller;

use App\Form\MessagesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
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
    public function send(Request $request) :Response
    {
        $message = new Message;
        $form= $this->createForm(MessagesType::class,$message);
      return $this->render("messages/send.html.twig",[
            "form" =>$form->createView()
      ]);
    }
}

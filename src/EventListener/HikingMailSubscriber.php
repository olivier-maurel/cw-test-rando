<?php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use App\Repository\HikingRepository;

class HikingMailSubscriber implements EventSubscriberInterface
{
    public function __construct(\Swift_Mailer $mailer, HikingRepository $hikingRepository)
    {
        $this->mailer           = $mailer;
        $this->hikingRepository = $hikingRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        
        if ($method != 'POST') {
            return;
        }
        
        $route  = $event->getRequest()->attributes->get('_route');
        $id     = $event->getRequest()->attributes->get('id');
        $hiking = $this->hikingRepository->find($id);

        switch ($route) {
            case 'hiking.new':
                $title   = 'Une nouvelle randonnée a été créé - '.$hiking->getTitle();
                $message = '';
                break;
            case 'hiking.edit':
                $title   = 'Une randonnée a été modifié - '.$hiking->getTitle();
                $message = '';
                break;
            case 'hiking.delete':
                $title   = 'Une randonnée a été supprimé - '.$hiking->getTitle();
                $message = '';
                break;
            default:
                return;
                break;
        }

        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('send@example.com')
        ->setTo('omaurel@pm.me')
        ->setBody($title);

    $this->mailer->send($message);

    }
}
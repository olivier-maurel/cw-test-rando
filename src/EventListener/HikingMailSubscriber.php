<?php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use App\Repository\HikingRepository;


class HikingMailSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $hikingRepository;
    private $route;

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
        $route      = $event->getRequest()->attributes->get('_route');
        $id         = $event->getRequest()->attributes->get('id');
        dump($event); exit;
        $url        = 'http://'.$event->getRequest()->headers->get('host').'/hiking/'.$id.'/show';
        $link       = '<a href="'.$url.'">'.$url.'</a>';

        switch ($route) {
            case 'hiking.new':
                $newTitle   = $event->getRequest()->request->get('hiking')['title'];
                $title   = 'Une nouvelle randonnée a été créé - '.$newTitle;
                $message = 'La randonnée '.$newTitle.' ayant pour ID : '.$id.'vient d\'être créée.<br>Vous pouvez accéder à la randonnée à cette adresse : '.$link;
                break;
            case 'hiking.edit':
                $newTitle   = $event->getRequest()->request->get('hiking')['title'];
                $hiking  = $this->hikingRepository->find($id);
                if ($newTitle != $hiking->getTitle())
                $changeTitle = $hiking->getTitle().' devenu -> '.$newTitle;
                else $changeTitle = $hiking->getTitle();
                $title   = 'Une randonnée a été modifié - '.$changeTitle;
                $message = 'La randonnée '.$changeTitle.' a été modifiée. (id:'.$id.')<br>Vous pouvez accéder à la randonnée à cette adresse : '.$link;
                break;
            case 'hiking.delete':
                $url     = 'http://'.$event->getRequest()->headers->get('host').'/hiking/new';
                $hiking  = $this->hikingRepository->find($id);
                $title   = 'Une randonnée a été supprimé - '.$hiking->getTitle();
                $message = 'La randonnée '.$hiking->getTitle().'(id:'.$id.') a été supprimée :(<br>\
                            Mais vous pouvez toujours en créer une nouvelle à cette adresse :<a href="'.$url.'">'.$url.'</a>';
                break;
            default:
                return;
                break;
        }

        $message = (new \Swift_Message('Hello Email'))
        ->setSubject($title)
        ->setFrom('send@example.com')
        ->setTo('omaurel@pm.me')
        ->setBody($message,'text/html');
        $this->mailer->send($message);

    }
}
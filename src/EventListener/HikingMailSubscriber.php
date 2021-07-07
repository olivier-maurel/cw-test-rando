<?php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class HikingMailSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $params;

    public function __construct(\Swift_Mailer $mailer, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->params = $params;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ResponseEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(ResponseEvent $event)
    {
        $method = $event->getRequest()->getMethod();

        if ($method != 'POST') {
            return;
        }

        $route      = $event->getRequest()->attributes->get('_route');
        $id         = $event->getRequest()->attributes->get('id');
        $title      = $event->getRequest()->attributes->get('title');
        $url        = 'http://'.$event->getRequest()->headers->get('host').'/hiking/'.$id.'/show';
        $link       = '<a href="'.$url.'">'.$url.'</a>';
        
        switch ($route) {
            case 'hiking.new':
                $subject   = 'Une nouvelle randonnée a été créé - '.$title;
                $message = 'La randonnée '.$title.' ayant pour ID : '.$id.' vient d\'être créée.<br>
                            Vous pouvez accéder à la randonnée à cette adresse : '.$link;
                break;
            case 'hiking.edit':
                $subject   = 'Une randonnée a été modifié - '.$title;
                $message = 'La randonnée '.$title.' a été modifiée. (id:'.$id.')<br>
                            Vous pouvez accéder à la randonnée à cette adresse : '.$link;
                break;
            case 'hiking.delete':
                $url     = 'http://'.$event->getRequest()->headers->get('host').'/hiking/new';
                $subject   = 'Une randonnée a été supprimé - '.$title;
                $message = 'La randonnée '.$title.'(id:'.$id.') a été supprimée :(<br>
                            Mais vous pouvez toujours en créer une nouvelle à cette adresse :<a href="'.$url.'">'.$url.'</a>';
                break;
            default:
                return;
                break;
        }

        $message = (new \Swift_Message())
        ->setSubject($subject)
        ->setFrom($this->params->get('mailer_addr'))
        ->setTo($this->params->get('mailer_admin'))
        ->setBody($message,'text/html');
        $this->mailer->send($message);

    }
}
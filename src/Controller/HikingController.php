<?php

namespace App\Controller;

use App\Entity\Hiking;
use App\Entity\HikingType as HikingTypes;
use App\Entity\HikingDifficulty;
use App\Entity\WayPoint;

use App\Form\HikingType;
use App\Form\SearchType;

use App\Repository\HikingRepository;
use App\Repository\WayPointRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/hiking", name="hiking.")
 */
class HikingController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(HikingRepository $hikingRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data    = $hikingRepository->findBy([],['created_at' => 'desc']);
        $hikings = $paginator->paginate($data, $request->query->getInt('page', 1), 5);

        return $this->render('hiking/index.html.twig', [
            'hikings'       => $hikings,
        ]);
    }

    public function search(): Response
    {
        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('hiking.handleSearch'))
            ->add('search', TextType::class,[
                'required' => false,
            ])
            ->add('difficulty_min', HiddenType::class,[
                'required' => false,
            ])
            ->add('difficulty_max', HiddenType::class,[
                'required' => false,
            ])
            ->add('duration_min', HiddenType::class,[
                'required' => false,
            ])
            ->add('duration_max', HiddenType::class,[
                'required' => false,
            ])
            ->add('type', EntityType::class,[
                'class' => HikingTypes::class,
                'label' => 'Type',
                'placeholder' => 'Sélectionner une type',
                'mapped' => true,
                'required' => false,
            ])
            ->add('return_start_point', CheckboxType::class,[
                'required' => false
            ])
            ->add('submit', SubmitType::class)
            ->getForm();  

        return $this->render('hiking/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/handleSearch", name="handleSearch")
     */
    public function handleSearch(Request $request, HikingRepository $hikingRepository, PaginatorInterface $paginator)
    {
        $data    = $hikingRepository->findBySearch($request->request->get('form'));
        $hikings = $paginator->paginate($data, $request->query->getInt('page', 1), 5);

        return $this->render('hiking/index.html.twig', [
            'hikings' => $hikings,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $hiking = new Hiking();
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(HikingType::class, $hiking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->checkSetPicture($form['picture'], $hiking, $entityManager);
            $this->checkSetWayPoints($form->getExtraData()['wayPoints'], $hiking, $entityManager);
            $hiking->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($hiking);
            $entityManager->flush();

            $request->attributes->set('id',$hiking->getId());
            $request->attributes->set('title',$hiking->getTitle());
            
            return $this->redirectToRoute('hiking.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hiking/new.html.twig', [
            'hiking' => $hiking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/show", name="show", methods={"GET"})
     */
    public function show(Hiking $hiking, WayPointRepository $wayPointRepository): Response
    {
        return $this->render('hiking/show.html.twig', [
            'hiking' => $hiking,
            'waypoints' => $wayPointRepository->findBy(['hiking' => $hiking->getId()])
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Hiking $hiking, WayPointRepository $wayPointRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(HikingType::class, $hiking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->checkSetPicture($form['picture'], $hiking, $entityManager);
            if (isset($form->getExtraData()['wayPoints']))
                $this->checkSetWayPoints($form->getExtraData()['wayPoints'], $hiking, $entityManager);
            $hiking->setModifiedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $request->attributes->set('id',$hiking->getId());
            $request->attributes->set('title',$hiking->getTitle());

            return $this->redirectToRoute('hiking.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hiking/edit.html.twig', [
            'hiking' => $hiking,
            'waypoints' => $wayPointRepository->findBy(['hiking' => $hiking->getId()]),
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Hiking $hiking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hiking->getId(), $request->request->get('_token'))) {
            $request->attributes->set('id',$hiking->getId());
            $request->attributes->set('title',$hiking->getTitle());
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hiking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hiking.index', [], Response::HTTP_SEE_OTHER);
    }

    /*
     * Gestion de l'upload d'image.
     */
    private function checkSetPicture($data, $hiking, $entityManager)
    {
        if ($data->getViewData() != null){
            $file = $data->getViewData();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            if ($file->move($this->getParameter('upload_path'),$filename)) {
                $hiking->setPicture($filename);
                return true;
            } else return false;
        } else true;
    }

    /*
     * Gestion des coordonnées GPS.
     */
    private function checkSetWayPoints($data, $hiking, $entityManager)
    {
        if (isset($data) && count($data) > 0 ) {
            if ($hiking->getId() != null) {
                $entities = $entityManager->getRepository(WayPoint::class)->findBy(['hiking'=>$hiking->getId()]);
                foreach ($entities as $entity) {$entityManager->remove($entity);}
            }
            foreach ($data as $key => $value) {
                $wayPoint = new wayPoint();
                $geo = explode(';',$value);
                $wayPoint->setHiking($hiking);
                $wayPoint->setStep($key+1);
                $wayPoint->setLongitude($geo[0]);
                $wayPoint->setLatitude($geo[1]);
                $entityManager->persist($wayPoint);        
            }
            return true;
        } else return false;
    }
}

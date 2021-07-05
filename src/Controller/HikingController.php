<?php

namespace App\Controller;

use App\Entity\Hiking;
use App\Entity\HikingType as HikingTypes;
use App\Entity\HikingDifficulty;

use App\Form\HikingType;
use App\Form\SearchType;

use App\Repository\HikingRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


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
        // $this->search($hikingRepository, $paginator, $request);
        $donnees = $hikingRepository->findBy([],['created_at' => 'desc']);
        // $donnees = $this->search($request, $hikingRepository);
        $hikings = $paginator->paginate($donnees, $request->query->getInt('page', 1), 5);

        return $this->render('hiking/index.html.twig', [
            'hikings' => $hikings,
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
            ->add('submit', SubmitType::class)
            ->getForm();  

        // if ($form->isSubmitted() && $form->isValid()) {
        //     dump('coucou'); exit;
        // }

        // $hikings = $paginator->paginate($donnees, $request->query->getInt('page', 1), 5);
        return $this->render('hiking/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/handleSearch", name="handleSearch")
     */
    public function handleSearch(Request $request, HikingRepository $hikingRepository)
    {
        $data = $request->request->get('form');
        dump($data);
        $hikingRepository->findBySearch($data);
        dump($request->request->get('form')); exit;
    }


    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $hiking = new Hiking();
        $form = $this->createForm(HikingType::class, $hiking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!empty($form['picture'])){
                $file = $form['picture']->getData();
                $filename = md5(uniqid()).'.'.$file->guessExtension();
                if ($file->move($this->getParameter('upload_path'),$filename))
                    $hiking->setPicture($filename);
            }

            $hiking->setCreatedAt(new \DateTimeImmutable());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hiking);
            $entityManager->flush();

            return $this->redirectToRoute('hiking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hiking/new.html.twig', [
            'hiking' => $hiking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/show", name="show", methods={"GET"})
     */
    public function show(Hiking $hiking): Response
    {
        return $this->render('hiking/show.html.twig', [
            'hiking' => $hiking,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Hiking $hiking): Response
    {
        $form = $this->createForm(HikingType::class, $hiking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hiking->setModifiedAt(new \DateTimeImmutable());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hiking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hiking/edit.html.twig', [
            'hiking' => $hiking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Hiking $hiking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hiking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hiking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hiking_index', [], Response::HTTP_SEE_OTHER);
    }
}

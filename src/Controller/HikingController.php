<?php

namespace App\Controller;

use App\Entity\Hiking;
use App\Entity\HikingType as HikingTypes;
use App\Entity\HikingDifficulty;
use App\Form\HikingType;
use App\Repository\HikingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hiking")
 */
class HikingController extends AbstractController
{
    /**
     * @Route("/", name="hiking_index", methods={"GET"})
     */
    public function index(HikingRepository $hikingRepository): Response
    {
        return $this->render('hiking/index.html.twig', [
            'hikings' => $hikingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="hiking_new", methods={"GET","POST"})
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
     * @Route("/{id}", name="hiking_show", methods={"GET"})
     */
    public function show(Hiking $hiking): Response
    {
        return $this->render('hiking/show.html.twig', [
            'hiking' => $hiking,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="hiking_edit", methods={"GET","POST"})
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
     * @Route("/{id}", name="hiking_delete", methods={"POST"})
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

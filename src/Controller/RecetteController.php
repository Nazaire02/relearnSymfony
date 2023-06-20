<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'app_recette', methods:['GET'])]
    public function index(
        RecetteRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        
        $recettes = $paginator->paginate(
            $repository -> findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/recette/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    #[Route('/recette/new', name: 'recette.new', methods:['POST', 'GET'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class,$recette);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingredient a été créé avec succes ! '
            );

            return $this->redirectToRoute('app_recette');
        }
        return $this->render('pages/recette/new.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    #[Route('/recette/edition/{id}', name:'recette.edit', methods:['GET', 'POST'])]
    public function edit(
        Recette $recette,
        Request $request,
        EntityManagerInterface $manager
    ): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                'Modification effectuée avec succes'
            );

            return $this->redirectToRoute('app_recette');
        }
        return $this->render('pages/recette/edit.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/recette/delete/{id}', 'recette.delete', methods: ['GET', 'POST'])]
    public function delete(
        Recette $recette,
        EntityManagerInterface $manager
    ): Response
    {
        if(!$recette){
            return $this->redirectToRoute('app_recette');
        }

        $manager->remove($recette);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre recette a été supprimée avec succes ! '
        );

        return $this->redirectToRoute('app_recette');
    }
}

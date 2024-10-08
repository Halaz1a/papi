<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\TypeBatterie;
use App\Repository\TypeBatterieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ModifierBatterieType;

class BatterieController extends AbstractController
{
    #[Route('/admin-liste-typebatterie', name: 'app_liste-typebatterie')]
    public function listeTypeBatterie(TypeBatterieRepository $typeBatterieRepository): Response
    {
        $batteries = $typeBatterieRepository->findAll();
        return $this->render('batterie/liste-typebatterie.html.twig', [
            'batteries' => $batteries
        ]);
    }

    #[Route('/admin-modifier_batterie/{id}', name: 'app_modifier_batterie')]
    public function modifierBatterie(Request $request, TypeBatterie $batterie, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModifierBatterieType::class, $batterie);
        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $em->persist($batterie);
                $em->flush();
                $this->addFlash('notice', 'Batterie modifiée');
                return $this->redirectToRoute('app_liste-typebatterie');
            }
        }
        
        return $this->render('batterie/modifier_batterie.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin-supprimer_batterie/{id}', name: 'app_supprimer_batterie')]
    public function supprimerBatterie(Request $request, TypeBatterie $batterie, EntityManagerInterface $em): Response
    {
        if($batterie!=null){
            $em->remove($batterie);
            $em->flush();
            $this->addFlash('notice', 'Batterie supprimée');
        }
        
        return $this->redirectToRoute('app_liste-typebatterie');
    }
}

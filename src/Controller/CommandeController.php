<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\CommandeArticle;
use App\Entity\Article;
use App\Form\RechercheClientType;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'commande_index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Formulaire de recherche client
        $formRechercheClient = $this->createForm(RechercheClientType::class);
        $formRechercheClient->handleRequest($request);

        $client = null;
        if ($formRechercheClient->isSubmitted() && $formRechercheClient->isValid()) {
            $telephone = $formRechercheClient->get('telephone')->getData();
            $client = $entityManager->getRepository(Client::class)->findOneBy(['telephone' => $telephone]);

            if (!$client) {
                $this->addFlash('danger', 'Client introuvable.');
            }
        }

        // Formulaire de commande
        $commande = new Commande();
        $commandeForm = $this->createForm(CommandeType::class, $commande);

        $commandeForm->handleRequest($request);
        if ($commandeForm->isSubmitted() && $commandeForm->isValid() && $client) {
            $commande->setClient($client);

            $valid = true; // Flag pour vérifier la validité des quantités
            foreach ($commande->getLignes() as $ligne) {
                $article = $ligne->getArticle();
                if ($ligne->getQuantite() > $article->getQuantiteDisponible()) {
                    $this->addFlash('danger', sprintf('Quantité demandée pour %s non disponible.', $article->getNom()));
                    $valid = false;
                    break;
                }
                $article->setQuantiteDisponible($article->getQuantiteDisponible() - $ligne->getQuantite());
                $entityManager->persist($article);
            }

            if ($valid) {
                $entityManager->persist($commande);
                $entityManager->flush();

                $this->addFlash('success', 'Commande enregistrée avec succès.');
                return $this->redirectToRoute('commande_index');
            }
        }

        return $this->render('commande/index.html.twig', [
            'formRechercheClient' => $formRechercheClient->createView(),
            'formCommande' => $commandeForm->createView(),
            'client' => $client,
        ]);
    }
}
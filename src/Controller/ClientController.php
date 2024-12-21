<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\RechercheClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    private $entityManager;

    // Injection de dépendances via le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/client", name="client_index")
     */
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();
        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    /**
     * @Route("/client/new", name="client_new")
     */
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(RechercheClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le client en utilisant l'EntityManager injecté
            $this->entityManager->persist($client);
            $this->entityManager->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/client/{id}", name="client_show")
     */
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/client/{id}/edit", name="client_edit")
     */
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(RechercheClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour le client en utilisant l'EntityManager injecté
            $this->entityManager->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
        ]);
    }

    /**
     * @Route("/client/{id}/delete", name="client_delete")
     */
    public function delete(Client $client): Response
    {
        // Supprimer le client en utilisant l'EntityManager injecté
        $this->entityManager->remove($client);
        $this->entityManager->flush();

        return $this->redirectToRoute('client_index');
    }

    /**
     * @Route("/client/search", name="client_search")
     */
    public function search(Request $request, ClientRepository $clientRepository): Response
    {
        $searchTerm = $request->query->get('searchTerm');
        $clients = $clientRepository->findBySearchTerm($searchTerm);

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }
}

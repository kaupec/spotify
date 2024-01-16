<?php

namespace App\Controller;

use App\Entity\Analytics;
use App\Entity\Song;
use App\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SongController extends AbstractController
{
    #[Route('/songs', name: 'app_song')]
    public function index(Request $request, SongRepository $songRepository): Response
    {
        $form = $this->createForm(SearchType::class,null, ['method' => Request::METHOD_GET]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $query = $form->getData();
            if ($query && is_string($query) && $query !== "") {
                $song = $songRepository->findOneByName($query);
            }

            if ($song === null) {
                $this->addFlash('error', 'Oups, aucune chanson trouvée');

                return $this->redirectToRoute('app_song');
            }

            return $this->render('song/show.html.twig', [
                'song' => $song,
            ]);
        }
        $songs = $songRepository->findAll();
        return $this->render('song/index.html.twig', [
            'songs' => $songs,
            'form' => $form->createView()
        ]);
    }

    #[NoReturn] #[Route('/songs/{id}/play', name: 'app_song_play')]
    public function play(Song $song, EntityManagerInterface $entityManager): RedirectResponse
    {
        $analytics = (new Analytics())
            ->setSong($song)
            ->setCreatedAt((new \DateTimeImmutable()))
        ;

        $entityManager->persist($analytics);
        $entityManager->flush();

        return $this->redirectToRoute('app_song');
    }
}

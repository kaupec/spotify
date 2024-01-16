<?php

namespace App\Controller;

use App\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SongController extends AbstractController
{
    #[Route('/song', name: 'app_song')]
    public function index(SongRepository $songRepository): Response
    {
        $songs = $songRepository->findAll();
        return $this->render('song/index.html.twig', [
            'songs' => $songs,
        ]);
    }
}

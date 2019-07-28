<?php

namespace App\Controller;

use App\Repository\MicroPostRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/micro-post")
 */

class MicroPostController extends Controller
{
    /**
     * @var \twig_Environment
     */
    private $twig;
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    public function __construct(\Twig_Environment $twig, MicroPostRepository $microPostRepository)
    {
        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
    }
    /**
     * @Route("/",name="micro_post_index")
     */
    public function index()
    {
        //$posts= $this->getDoctrine()->getRepository(MicroPost::class)->findAll();
        $html = $this->twig->render('micro-post/index.html.twig', [
            'posts' => $this->microPostRepository->findAll()
        ]);
        return new Response($html);
    }
    /**
     * @Route("/create",name="micro_post_add")
     */

    public function create()
    {
        return new Response(
            $this->twig->render('micro-post/create.html.twig')
        );
    }
}

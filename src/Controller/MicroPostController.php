<?php

namespace App\Controller;

use App\Repository\MicroPostRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\MicroPost;
use Symfony\Component\Form\FormFactoryInterface;
use App\Form\MicroPostType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    public function __construct(
        \Twig_Environment $twig,
        MicroPostRepository $microPostRepository,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        RouterInterface $router
    ) {
        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
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
     * @Route("/create",name="micro_post_create")
     */

    public function create(Request $request)
    {
        $microPost = new MicroPost();
        $microPost->setTime(new \DateTime());
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();
            return new RedirectResponse($this->router->generate('micro_post_index'));
        }
        return new Response(
            $this->twig->render(
                'micro-post/create.html.twig',
                ['form' => $form->createView()]
            )
        );
    }
}

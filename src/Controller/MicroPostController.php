<?php

namespace App\Controller;

use App\Entity\User;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Entity\Collection;
use App\Security\MicroPostVoter;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
// use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use App\Repository\UserRepository;

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
    /**
     * @var EntityManagerInerface
     */
    private $entityManager;
    /**
     * @var RouterInterface 
     */
    private $router;
    /**
     * @var FlashBagInterface
     */
    private $flashBagInterface;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    public function __construct(
        \Twig_Environment $twig,
        MicroPostRepository $microPostRepository,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        FlashBagInterface $flashBagInterface,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->flashBagInterface = $flashBagInterface;
        $this->authorizationChecker = $authorizationChecker;
    }
    /**
     * @Route("/",name="micro_post_index")
     */
    public function index(UserRepository $userRepository)
    {
        $currentUser = $this->getUser();
        $usersToFollow = [];
        if ($currentUser instanceof User) {
            $posts = $this->microPostRepository->findAllByUsers($currentUser->getFollowing());
            $usersToFollow = count($posts) === 0 ? $userRepository->findAllWithMoreThan5PostsExpectUser($currentUser) : [];
        } else {
            $posts = $this->microPostRepository->findBy([], ['time' => 'DESC']);
        }
        $html = $this->twig->render('micro-post/index.html.twig', [
            'posts' => $posts,
            'usersToFollow' => $usersToFollow
        ]);
        return new Response($html);
        //$posts= $this->getDoctrine()->getRepository(MicroPost::class)->findAll();
    }
    /**
     *@Route("/edit/{id}",name="micro_post_edit")
     *@Security("is_granted('edit',microPost)",message="Access denied")
     */
    public function edit(MicroPost $microPost, Request $request)
    {
        if (!$this->authorizationChecker->isGranted('edit', $microPost)) {
            throw new UnauthorizedHttpException('Access denied');
        }
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
    /**
     * @Route("/create",name="micro_post_create")
     */

    public function create(Request $request)
    {
        $microPost = new MicroPost();
        $microPost->setTime(new \DateTime());
        $user = $this->getUser();
        $microPost->setUser($user);
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
    /**
     * @Route("/post/{id}",name="micro_post_post")
     * 
     */
    public function post(MicroPost $post)
    {
        //  $post= $this->microPostRepository->find($id);
        $html = $this->twig->render('micro-post/post.html.twig', [
            'post' => $post
        ]);
        return new Response($html);
    }
    /**
     * @Route("/user/{username}",name="micro_post_user")
     * 
     */
    public function userPosts(User $userwithPosts)
    {
        $html = $this->twig->render('micro-post/user-post.html.twig', [
            'posts' => $this->microPostRepository->findBy([
                'user' => $userwithPosts
            ], ['time' => 'DESC']),
            'user' => $userwithPosts
        ]);
        return new Response($html);
    }
    /**
     * @Route("/delete/{id}",name="micro_post_delete")
     * @Security("is_granted('delete',microPost)",message="Access denied")
     */
    public function delete(MicroPost $microPost)
    {
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();
        $this->flashBagInterface->add('notice', 'post was deleted');
        return new RedirectResponse($this->router->generate('micro_post_index'));
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\MicroPost;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/likes")
 */

class LikesController extends AbstractController
{
    /**
     * @Route("/like/{id}", name="likes_like")
     */
    public function like(MicroPost $microPost)
    {
        $currentUser = $this->getUser();
        if (!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $microPost->like($currentUser);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse([
            'count' => $microPost->getLikedBy()->count()
        ]);
    }
    /**
     * @Route("/unlike/{id}", name="likes_unlike")
     */
    public function unlike(MicroPost $microPost)
    {
        $currentUser = $this->getUser();
        if (!$currentUser instanceof User) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $microPost->getLikedBy()->removeElement($currentUser);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse([
            'count' => $microPost->getLikedBy()->count()
        ]);
    }
}

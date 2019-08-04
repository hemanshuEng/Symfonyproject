<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/***
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends AbstractController
{
    /**
     * @Route("/follow/{id}", name="following_follow")
     */
    public function follow(User $userToFollow)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();
        if ($userToFollow->getId() !== $currentUser) {

            $currentUser->getFollowing()->add($userToFollow);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('micro_post_user', [
            'username' => $userToFollow->getUsername()
        ]);
    }
    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     */
    public function unfollow(User $userToUnfollow)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();
        $currentUser->getFollowing()->removeElement($userToUnfollow);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('micro_post_user', [
            'username' => $userToUnfollow->getUsername()
        ]);
    }
}

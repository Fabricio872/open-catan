<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api", name="user.")
 */
class UserController extends ExtendedAbstractController
{
    /**
     * @Route("/new/profile-picture", name="uploadProfilePic", methods={"POST"})
     */
    public function uploadProfilePic(Request $request)
    {
        $image = new Image();
        $image->setFile($request->files->get('profile-picture'));

        $user = $this->em()->getRepository(User::class)->find($this->getUser());
        if ($user->getImage() != null) {
            $this->em()->remove($user->getImage());
        }
        $user->setImage($image);

        $this->em()->persist($user);
        $this->em()->flush();

        return $this->json([
            "status" => 200,
            "success" => $image->getName()
        ]);
    }
}

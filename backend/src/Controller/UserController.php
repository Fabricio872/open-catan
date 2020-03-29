<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api", name="user.")
 */
class UserController extends ExtendedAbstractController
{
    /**
     * @Route("/get/profile-picture", name="geProfilePic")
     */
    public function profile()
    {
        /** @var Image $image */
        $image = $this->getUser()->getImage();

        if ($image == null) {
            return $this->json([
                "url" => $this->generateUrl("image.asset", ["image" => "default-user.png"], UrlGeneratorInterface::ABSOLUTE_URL)
            ]);
        }

        return $this->json([
            "url" => $this->generateUrl("image.uploaded", ["id" => $this->encryptor->encrypt($image->getId())], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    /**
     * @Route("/new/profile-picture", name="uploadProfilePic", methods={"POST"})
     */
    public function uploadProfilePic(Request $request)
    {
        if ($request->files->get('profile-picture') == null) {
            return $this->json("bad request", Response::HTTP_BAD_REQUEST);
        }
        $image = new Image();
        $image->setFile($request->files->get('profile-picture'));

        /** @var User $user */
        $user = $this->getUser();
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

    /**
     * @Route("/delete/profile-picture", name="removeProfilePic", methods={"DELETE"})
     */
    public function removeProfilePic()
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getImage() == null) {
            return $this->json([
                "status" => 200,
                "fail" => "No profile picture found"
            ]);
        }
        $user->setImage(null);
        $this->em()->flush();

        return $this->json([
            "status" => 200,
            "success" => "Picture deleted"
        ]);
    }
}

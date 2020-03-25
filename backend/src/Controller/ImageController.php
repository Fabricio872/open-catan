<?php

namespace App\Controller;

use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ImageController
 * @package App\Controller
 * @Route("/", name="image.")
 */
class ImageController extends ExtendedAbstractController
{
    static string $assetsDir = __DIR__ . "/../../assets/";

    /**
     * @Route("/api/profile-picture", name="profile")
     */
    public function profile()
    {
        /** @var Image $image */
        $image = $this->getUser()->getImage();

        if ($image == null) {
            return $this->redirectToRoute("image.asset", ["image" => "default-user.png"]);
        }

        $content = file_get_contents($image->getFile()->getRealPath());

        $headers = array(
            'Content-Type' => 'image/' . $image->getFile()->guessExtension(),
            'Content-Disposition' => 'inline; filename="' . $image->getName() . '"');
        return new Response($content, 200, $headers);
    }

    /**
     * @Route("/asset/{image}", name="asset")
     */
    public function asset(string $image)
    {
        if (!file_exists(self::$assetsDir . $image)) {
            return $this->json([
                "status" => Response::HTTP_NOT_FOUND,
                "failed" => "File $image not found"
            ], Response::HTTP_NOT_FOUND);
        }

        $imageFile = new File(self::$assetsDir . $image);
        $content = file_get_contents(self::$assetsDir . $image);

        $headers = array(
            'Content-Type' => 'image/' . $imageFile->guessExtension(),
            'Content-Disposition' => 'inline; filename="' . $image . '"');
        return new Response($content, 200, $headers);
    }
}

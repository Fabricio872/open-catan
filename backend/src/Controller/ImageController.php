<?php

namespace App\Controller;

use App\Entity\Image;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;
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
     * @ParamDecryptor(params={"id"})
     * @Route("/upload/{id}", name="uploaded")
     */
    public function uploaded(Image $image)
    {
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
            throw $this->createNotFoundException("File $image not found");
        }

        $imageFile = new File(self::$assetsDir . $image);
        $content = file_get_contents(self::$assetsDir . $image);

        $headers = array(
            'Content-Type' => 'image/' . $imageFile->guessExtension(),
            'Content-Disposition' => 'inline; filename="' . $image . '"');
        return new Response($content, 200, $headers);
    }
}

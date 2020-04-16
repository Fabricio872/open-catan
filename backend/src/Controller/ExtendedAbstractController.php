<?php

namespace App\Controller;

use App\Entity\Image;
use Nzo\UrlEncryptorBundle\UrlEncryptor\UrlEncryptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ExtendedAbstractController extends AbstractController
{
    protected $encryptor;

    public function __construct(UrlEncryptor $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    protected function em()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function getImageUrl(?Image $image, string $default = "not-found.png")
    {
        if ($image == null) {
            return $this->generateUrl("image.asset", ["image" => $default], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $this->generateUrl("image.uploaded", ["id" => $this->encryptor->encrypt($image->getId())], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}

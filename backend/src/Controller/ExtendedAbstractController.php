<?php

namespace App\Controller;

use Nzo\UrlEncryptorBundle\UrlEncryptor\UrlEncryptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}

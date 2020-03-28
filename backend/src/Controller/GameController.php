<?php

namespace App\Controller;

use App\Entity\Session;
use App\Service\Hex;
use App\Service\Playground;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/game", name="game")
 */
class GameController extends ExtendedAbstractController
{
    /**
     * @ParamDecryptor({"id"})
     * @Route("/get_playground/{id}", name="getPlayground", methods={"POST"})
     */
    public function getPlayground(Session $session, Playground $playground)
    {
        $playground->setSession($session);

        $hexagons = [];
        foreach ($playground->getPlan() as $hexagon){
            $hexArray["id"] = $hexagon->getId();
            $hexArray["position"] = $hexagon->getPosition();
//            $hexArray["position"] = Hex::cubeToOddr($hexagon->getPosition());
            $hexArray["type"] = $hexagon->getTypeName();
            $hexArray["value"] = $hexagon->getValue();
            $hexagons[] = $hexArray;
        }

        return $this->json($hexagons);
    }
}

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
     * @Route("/get_session/{id}", name="getSession", methods={"POST"})
     */
    public function getSession(Session $session, Playground $playground)
    {
        $playground->setSession($session);

        $response["name"] = $session->getName();
        $response["seed"] = $session->getSeed();
        $response["date"] = $session->getDate();
        $response["roads"] = $session->getRoads();
        $response["cities"] = $session->getCities();
        $response["score"] = $session->getScore();
        $response["settlements"] = $session->getSettlements();

        foreach ($playground->getPlan() as $hexagon) {
            $hexArray["id"] = $hexagon->getId();
            $hexArray["position"] = $hexagon->getPosition();
//            $hexArray["position"] = Hex::cubeToOddr($hexagon->getPosition());
            $hexArray["type"] = $hexagon->getTypeName();
            $hexArray["value"] = $hexagon->getValue();
            $response["plan"][] = $hexArray;
        }

        return $this->json($response);
    }
}

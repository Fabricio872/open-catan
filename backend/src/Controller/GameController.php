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

        $gameArray = [];
        foreach ($playground->getPlan() as $hexagon){
            $hexArray["id"] = $hexagon->getId();
            $hexArray["position"] = $hexagon->getPosition();
//            $hexArray["position"] = Hex::cubeToOddr($hexagon->getPosition());
            $hexArray["type"] = $hexagon->getTypeName();
            $hexArray["value"] = $hexagon->getValue();
            $gameArray["playground"][] = $hexArray;
        }
        foreach ($session->getPlayers() as $player){
            $playerArray["id"] = $player->getId();
            $playerArray["color"] = $player->getColor();
            $playerArray["is_host"] = (bool)$player->getIsHost();
            $playerArray["profile_picture"] = $this->getImageUrl($player->getUser()->getImage(), "default-user.png");
            $gameArray["players"][] = $playerArray;
        }

        return $this->json($gameArray);
    }
}

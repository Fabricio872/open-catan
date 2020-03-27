<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Session;
use App\Service\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/api/session", name="session.")
 */
class SessionController extends ExtendedAbstractController
{
    /**
     * @Route("/new", name="new", methods={"POST"})
     */
    public function new(Request $request)
    {
        $data = json_decode($request->getContent());

        $host = new Player();
        $host->setIsHost(true);
        $host->setUser($this->getUser());
        $host->setColor($data->color);

        $session = new Session();
        $session->setName($data->name);
        $session->setPlayerCount($data->players);
        $session->setSeed($data->seed);
        $session->addPlayer($host);

        $this->em()->persist($session);
        $this->em()->flush();

        return $this->json([
//            "invitation-link" => $this->generateUrl("session.addPlayer", ["id" => $session->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            "invitation-code" => $session->getId()
        ]);
    }

    /**
     * @Route("/add/{id}", name="addPlayer")
     */
    public function addPlayer(Session $session, Request $request)
    {
        $data = json_decode($request->getContent());

        if ($session->getPlayerCount() <= $session->getPlayers()->count()) {
            throw $this->createAccessDeniedException("Maximum players is " . $session->getPlayerCount());
        }

        if ($this->em()->getRepository(Session::class)->isInSession($this->getUser(), $session)) {
            throw $this->createAccessDeniedException("Player already in game");
        }

        if (!Validator::isColor($data->color)) {
            throw $this->createAccessDeniedException("Color must be in hex format");
        }

        $player = new Player();
        $player->setUser($this->getUser());
        $player->setColor(substr($data->color, 1));
        $session->addPlayer($player);

        $this->em()->persist($session);
        $this->em()->flush();

        return $this->json([
            "success" => "player added",
            "game" => $session->getName()
        ]);
    }
}

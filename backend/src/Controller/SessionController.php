<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Session;
use App\Service\Validator;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/api/session", name="session.")
 */
class SessionController extends ApiController
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

        $data->seed ??= crc32(uniqid());
        $data->score ??= 10;
        $data->cities ??= 4;
        $data->settlements ??= 5;
        $data->roads ??= 15;

        $session = new Session();
        $session
            ->setName($data->name)
            ->setPlayerCount($data->players)
            ->setSeed($data->seed)
            ->addPlayer($host)
            ->setScore($data->score)
            ->setCities($data->cities)
            ->setSettlements($data->settlements)
            ->setRoads($data->roads);


        $this->em()->persist($session);
        $this->em()->flush();

        return $this->response([
//            "invitation-link" => $this->generateUrl("session.addPlayer", ["id" => $session->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            "invitation-code" => $this->encryptor->encrypt($session->getId())
        ]);
    }

    /**
     * @ParamDecryptor(params={"id"})
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
        $player->setColor($data->color);
        $session->addPlayer($player);

        $this->em()->persist($session);
        $this->em()->flush();

        return $this->respondWithSuccess('Joined game ' . $session->getName());
    }

    /**
     * @ParamDecryptor(params={"id"})
     * @Route("/list/{id}", name="getPlayers", methods={"POST"})
     */
    public function getPlayers(Session $session)
    {
        $players = [];
        $this->em()->getRepository(Player::class)->setOnline($this->getUser(), $session);
        foreach ($session->getPlayers() as $player) {
            $playerArray["id"] = $player->getId();
            $playerArray["color"] = $player->getColor();
            $playerArray["is_host"] = (bool)$player->getIsHost();
            $playerArray["is_online"] = (bool)$player->getIsOnline();
            $playerArray["profile_picture"] = $this->getImageUrl($player->getUser()->getImage(), "default-user.png");
            $players[] = $playerArray;
        }
        return $this->response($players);
    }
}

<?php

namespace App\Controller;

use App\Entity\Hexagon;
use App\Entity\Player;
use App\Entity\Road;
use App\Entity\Session;
use App\Entity\Settlement;
use App\Service\GameValidator;
use App\Service\Response;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/build", name="build.")
 */
class BuildController extends ApiController
{
    /**
     * @ParamDecryptor(params={"id"})
     * @Route("/road/{id}", name="road")
     */
    public function road(Session $session, Request $request, GameValidator $validator)
    {
        $data = json_decode($request->getContent());
        $validator->setHexagons(
            $this->em()->getRepository(Hexagon::class)->findOneBy(["id" => $this->encryptor->decrypt($data[0]), "session" => $session]),
            $this->em()->getRepository(Hexagon::class)->findOneBy(["id" => $this->encryptor->decrypt($data[1]), "session" => $session])
        );

        $validator->setPlayer($this->em()->getRepository(Player::class)->findOneBy([
            'session' => $session,
            'user' => $this->getUser()
        ]));

        if ($validator->isOnWater()) {
            Response::inst()->addError("You cannot build on water");
        }

        if ($validator->isNotNeighbors()) {
            Response::inst()->addError("Hexagons are not neighbors");
        }

        if (count(array_unique($data)) != count($data)) {
            Response::inst()->addError("Hexagons must be unique");
        }

        if ($validator->isRoadNotNearSettlement() && $validator->isRoadNotNearRoad()) {
            Response::inst()->addError("Road must be connected to settlement or another road");
        }

        /** @var Road $road */
        $road = $validator->getEntity();

        if ($this->em()->getRepository(Road::class)->exists($road)) {
            Response::inst()->addError("Road already exists");
        }

        if (Response::inst()->ok()) {
            $this->em()->persist($road);
            $this->em()->flush();
        }

        Response::inst()->setMessage("Road was built");
        return Response::inst()->getResponse();
    }

    /**
     * @ParamDecryptor(params={"id"})
     * @Route("/settlement/{id}", name="settlement")
     */
    public function settlement(Session $session, Request $request, GameValidator $validator)
    {
        $data = json_decode($request->getContent());
        $validator->setHexagons(
            $this->em()->getRepository(Hexagon::class)->findOneBy(["id" => $this->encryptor->decrypt($data[0]), "session" => $session]),
            $this->em()->getRepository(Hexagon::class)->findOneBy(["id" => $this->encryptor->decrypt($data[1]), "session" => $session]),
            $this->em()->getRepository(Hexagon::class)->findOneBy(["id" => $this->encryptor->decrypt($data[2]), "session" => $session])
        );

        $validator->setPlayer($this->em()->getRepository(Player::class)->findOneBy([
            'session' => $session,
            'user' => $this->getUser()
        ]));

        if ($validator->isOnWater()) {
            Response::inst()->addError("You cannot build on water");
        }

        if ($validator->isNotNeighbors()) {
            Response::inst()->addError("Hexagons are not neighbors");
        }

        if (count(array_unique($data)) != count($data)) {
            Response::inst()->addError("Hexagons must be unique");
        }

        /** @var Settlement $settlement */
        $settlement = $validator->getEntity($session, $this->getUser());

        if ($city = $this->em()->getRepository(Settlement::class)->exists($settlement)) {
            if ($city->getIsCity()) {
                Response::inst()->addError("Settlement is already a city");
            } else {
                $city->setIsCity(true);

                if (Response::inst()->ok()) {
                    $this->em()->persist($city);
                    $this->em()->flush();
                }

                Response::inst()->setMessage("City was built");
                return Response::inst()->getResponse();
            }
        }

        if (Response::inst()->ok()) {
            $this->em()->persist($settlement);
            $this->em()->flush();
        }

        Response::inst()->setMessage("Settlement was built");
        return Response::inst()->getResponse();
    }
}

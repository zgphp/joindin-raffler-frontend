<?php

declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RaffleWebController extends Controller
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /*
            dev.raffler.loc:8000/api/joindin/events/fetch
            dev.raffler.loc:8000/api/joindin/talks/fetch
            dev.raffler.loc:8000/api/joindin/comments/fetch
    */

    public function index()
    {
        $data = [
            'events' => $this->apiGetJson('joindin/events/'),
        ];

        return $this->render('Raffle/index.html.twig', $data);
    }

    public function start(Request $request)
    {
        $eventIds = array_keys($request->request->all());

        $options = [
            'json' => ['events' => $eventIds],
        ];

        $raffleId = $this->apiPostJson('raffle/start', $options);

        return $this->redirect($this->generateUrl('raffle_web_show', ['id' => $raffleId]));
    }

    public function show(string $id)
    {
        $data = [
            'raffle' => $this->apiGetJson('raffle/'.$id),
        ];

        return $this->render('Raffle/show.html.twig', $data);
    }

    public function pick(string $id)
    {
        $data = [
            'raffle' => $this->apiGetJson('raffle/'.$id),
            'user'   => $this->apiPostJson('raffle/'.$id.'/pick'),
        ];

        return $this->render('Raffle/pick.html.twig', $data);
    }

    public function winner(string $id, string $userId)
    {
        $this->apiPostJson('raffle/'.$id.'/winner/'.$userId);

        return $this->redirect($this->generateUrl('raffle_web_show', ['id' => $id]));
    }

    public function noShow(string $id, string $userId)
    {
        $this->apiPostJson('raffle/'.$id.'/no_show/'.$userId);

        return $this->redirect($this->generateUrl('raffle_web_show', ['id' => $id]));
    }

    private function apiGetJson(string $url)
    {
        $response = $this->client->get(getenv('API_BASE_URL').$url);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function apiPostJson(string $url, array $options = [])
    {
        $response = $this->client->post(getenv('API_BASE_URL').$url, $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}

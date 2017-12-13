<?php

declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RaffleWebController extends Controller
{
    /** @var Client */
    private $client;
    private $session;

    public function __construct(Client $client, SessionInterface $session)
    {
        $this->client = $client;

        $this->session = $session;
        $this->session->start();
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
        if (null === $raffleId) {
            return $this->redirect($this->generateUrl('raffle_web_homepage'));
        }

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
        try {
            $response = $this->client->post(getenv('API_BASE_URL').$url, $options);

            $raffleID = json_decode($response->getBody()->getContents(), true);
            if (204 === $response->getStatusCode()) {
                $this->session->getFlashBag()->add('notice', 'Selected event has no comments to raffle!');

                return null;
            }

            return $raffleID;
        } catch (ClientException $ex) {
            if (400 === $ex->getCode()) {
                $this->session->getFlashBag()->add('error', 'No events selected! It\'s a must, yo!');

                return null;
            }
        }
    }
}

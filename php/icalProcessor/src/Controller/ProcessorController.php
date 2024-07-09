<?php

namespace App\Controller;

use ICal\ICal;
use Spatie\IcalendarGenerator\Components\Calendar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProcessorController extends AbstractController
{
    #[Route('/processor', name: 'app_processor')]
    public function index(
        Request $request,
    ): JsonResponse
    {
        $url = $request->query->get('url');
        $httpClient = HttpClient::create();
        $icalSource = $httpClient->request('GET', $url)->getContent();
        $ical = new ICal();
        $ical->initString($icalSource);

        foreach ($ical->cal['VEVENT'] as $key => $event) {
            if(\strpos($event['DESCRIPTION'], '0 place') === false) {
                unset($ical->cal['VEVENT'][$key]);
            }
        }

        $processedCal = Calendar::create()->source()

        foreach ($ical->events() as $key => $event) {
            dump($event);
        }

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProcessorController.php',
        ]);
    }
}

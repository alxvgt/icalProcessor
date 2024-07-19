<?php

namespace App\Controller;

use DateTimeImmutable;
use ICal\ICal;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Components\Timezone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProcessorController extends AbstractController
{
    #[Route('/processor', name: 'app_processor')]
    public function index(
        Request $request,
    ): Response
    {
        $url = $request->query->get('url');
        $httpClient = HttpClient::create();
        $icalSource = $httpClient->request('GET', $url)->getContent();
        $ical = new ICal();
        $ical->initString($icalSource);

        foreach ($ical->cal['VEVENT'] as $key => $event) {
            if (\strpos($event['DESCRIPTION'], '0 place') === false) {
                unset($ical->cal['VEVENT'][$key]);
            }
        }

        $processedCal = Calendar::create($ical->calendarName())
            ->description($ical->calendarDescription())
            ->timezone(Timezone::create($ical->calendarTimezone()))
            ->withoutAutoTimezoneComponents();

        /** @var \ICal\Event $event */
        foreach ($ical->events() as $event) {
            $event = Event::create($event->summary)
                ->startsAt(new DateTimeImmutable($event->dtstart))
                ->endsAt(new DateTimeImmutable($event->dtend))
                ->description((string) $event->description)
                ->uniqueIdentifier((string) $event->uid);
            $processedCal->event($event);
        }

        return new Response(
            content: '<pre>'.$processedCal->toString().'</pre>',
        );

        return new Response(
            content: $processedCal->toString(),
            headers: [
                'Content-Type' => 'text/calendar; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="cal.ics"',
            ]
        );
    }
}

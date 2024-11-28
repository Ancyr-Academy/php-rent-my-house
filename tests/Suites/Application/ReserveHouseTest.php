<?php

namespace App\Tests\Suites\Application;

use App\Application\Ports\Repositories\IHouseRepository;
use App\Application\Ports\Repositories\IReservationRepository;
use App\Domain\Entity\House;
use App\Tests\Infrastructure\ApplicationTestCase;

class ReserveHouseTest extends ApplicationTestCase {
  public function test_happyPath() {
    $client = self::initialize();

    /** @var IHouseRepository $houseRepository */
    $houseRepository = self::getContainer()->get(IHouseRepository::class);
    $houseRepository->save(new House("house-id"));

   $this->request('POST', '/api/reserve-house', [
     'houseId' => 'house-id',
     'startDate' => '2022-01-01',
     'endDate' => '2022-01-02',
   ]);

    $this->assertResponseStatusCodeSame(200);

    $response = $client->getResponse();
    $data = json_decode($response->getContent(), true);

    $id = $data['id'];

    /** @var IReservationRepository $reservationRepository */
    $reservationRepository = self::getContainer()->get(IReservationRepository::class);
    $reservation = $reservationRepository->findById($id);

    $this->assertNotNull($reservation);

    $this->assertEquals("house-id", $reservation->getHouseId());
    $this->assertEquals("2022-01-01", $reservation->getStartDate()->format('Y-m-d'));
    $this->assertEquals("2022-01-02", $reservation->getEndDate()->format('Y-m-d'));
  }
}
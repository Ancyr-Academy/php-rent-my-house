<?php

namespace App\Application\Commands\AcceptReservation;

use Symfony\Component\Validator\Constraints as Assert;

class AcceptReservationCommand {
  #[Assert\NotBlank(message: 'Reservation Id is required')]
  private readonly string $reservationId;

  public function __construct(string $reservationId) {
    $this->reservationId = $reservationId;
  }

  public function getReservationId(): string {
    return $this->reservationId;
  }
}
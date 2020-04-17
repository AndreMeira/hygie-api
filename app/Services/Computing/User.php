<?php

namespace App\Services\Formatters;

use \App\Services\Computing\BodyFat as BodyFatComputer;
use \App\UserBodyParam as BodyParam;
use \App\UserBodyFat as FatParam;

class User {

  private $computer;

  public function __construct(BodyFatComputer $computer) {
    $this->computer = $computer;
  }

  /**
   *
   */
  public function format(\App\User $user) {

      $userInfos = $this->formatBase($user);
      $body = $user->getLastBodyParam();
      $fat = $user->getLastBodyFat();

      return $userInfos
        + $this->formatBody($body)
        + $this->formatFat($body, $fat);
  }

  /**
   *
   */
  public function formatBase(\App\User $user) {
    return $user->toArray() + $user->info->toArray();
  }

  /**
   *
   */
  public function formatBody(BodyParam $body = null) {
    return ["body" => $body ? $body->toArray() : null];
  }

  public function formatFat(BodyParam $body = null, FatParam $fat = null) {
    $canCompute = $body && $fat;

    return $canCompute ? [
      "fat" => $fat->toArray() + $this->formatFatBodyMass($body, $fat)
    ] : ["fat" => null];
  }

  public function formatFatBodyMass(BodyParam $body = null, FatParam $fat = null) {
    $fatBodyProportion = $this->computer->computeFatProportion($body, $fat);

    $logHeight     = log($body->height, 10);
    $logProportion = log($body->hips + $body->waist - $body->neck, 10);

    return [
      "fat_proportion"  => $fatBodyProportion,
      "fat"             => $body->weight * $fatBodyProportion/100,
      "lean"            => $body->weight * (1 - $fatBodyProportion/100),
      // "details" => [$logHeight, $logProportion, $height, $waist, $neck, $hips]
    ];
  }
}

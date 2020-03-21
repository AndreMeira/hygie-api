<?php

namespace App\Services\Formatters;

use \App\UserBodyParam as BodyParam;
use \App\UserBodyFat as FatParam;

class User {

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
    $fatBodyMass = $this->computeFat($body, $fat);
    $age    = $body->age;
    $height = $body->height;
    $gender = $body->gender;
    $waist  = $fat->waist;
    $neck   = $fat->neck;
    $hips   = $fat->hips ?? 0;

    $logHeight     = log($height, 10);
    $logProportion = log($hips + $waist - $neck, 10);

    return [
      "fat_proportion"  => $fatBodyMass,
      "fat"             => $body->weight * $fatBodyMass/100,
      "lean"            => $body->weight * (1 - $fatBodyMass/100),
      "details" => [$logHeight, $logProportion, $height, $waist, $neck, $hips]
    ];
  }

  protected function computeFat(BodyParam $body = null, FatParam $fat = null) {
    $age    = $body->age;
    $height = $body->height;
    $gender = $body->gender;
    $waist  = $fat->waist;
    $neck   = $fat->neck;
    $hips   = $fat->hips ?? 0;

    $logHeight     = log($height, 10);
    $logProportion = log($hips + $waist - $neck, 10);

    return $gender === "F"
      ? 163.205 * $logProportion - $logHeight * 97.684 - 104.912
      : 86.01   * $logProportion - $logHeight * 70.041 +  30.300;
  }
}

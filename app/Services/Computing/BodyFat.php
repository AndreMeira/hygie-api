<?php

namespace App\Services\Computing;

use \App\UserBodyParam as BodyParam;
use \App\UserBodyFat as FatParam;

class BodyFat {


  public function computeLeanMass(BodyParam $body = null, FatParam $fat = null) {
    $fatBodyMass = $this->computeFatProportion($body, $fat);
    return $body->weight * (1 - $fatBodyMass/100);
  }

  protected function computeFatProportion(BodyParam $body = null, FatParam $fat = null) {
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

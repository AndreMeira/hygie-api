<?php

namespace App\Services\Computing;

use \App\UserBodyParam as BodyParam;
use \App\UserBodyFat as FatParam;

class CaloriesRecommandations {

  const BASE_CAL = 370;

  const LABELS = [
    "Taux métabolique basal (TBM) // ne jamais descendre en dessous de ce nombre de calories",
    "Activité sédentaire, temps de marche quotidienne inférieur à 30 min.",
    "Travail sédentaire, déplacements à pieds ou à vélo de plus de 20 minutes, activités peu intenses",
    "Travail actif ou debout, marche ou activité durant plus de 30 minutes d’affilée",
    "Mode de vie très actif (entrainement sportif de plus d’une heure/jour / déménageur / etc. "
  ];

  const FACTORS = [
    1, 1, 1.12, 1.27, 1.45
  ];

  /**
   *
   */
  public function get($bodyLeanMass) {
    $i = 0;
    $result = [];

    foreach (self::LABELS as $i => $label) {
      $coeff = 1.15;
      $coeff = $i > 1 ? $coeff : $coeff**$i;
      $factor = self::FACTORS[$i];
      $dej = (self::BASE_CAL + (21.6 * $bodyLeanMass)) * $coeff * $factor;
      $result[] = ["label" => $label, "dej"   => $dej];
      $i++;
    }

    return $result;
  }
}

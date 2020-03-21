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

  /**
   *
   */
  public function get($bodyLeanMass) {
    $i = 0;
    $result = [];

    foreach (self::LABELS as $label) {
      $result[] = [
        "label" => $label,
        "dej"   => (self::BASE_CAL + (21,6 * $bodyLeanMass)) * pow(1.15, ++$i)
      ];
    }
  }
}

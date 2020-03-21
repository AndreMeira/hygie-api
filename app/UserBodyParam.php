<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBodyParam extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'height', 'weight', 'user_id', 'year_of_birth', 'gender'
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */
  protected $appends = ['age', 'imc'];

  public function getAgeAttribute() {
    return $this->attributes['age'] = date("Y") - $this->year_of_birth;
  }

  public function getImcAttribute() {
    $square = pow($this->height/100, 2);
    $imc = $this->weight / $square;
    return $this->attributes['imc'] = $imc;
  }
}

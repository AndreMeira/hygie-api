<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user infos
     */
    public function info() {
        return $this->hasOne('App\UserInfo');
    }

    /**
     * Get the last
     */
    public function getLastBodyParam() {
        if ($this->bodyParams) {
          return $this->bodyParams->last();
        }
        return null;

    }

    public function getSurveyResult($survey) {
        return $this->surveyResult()
          // ->with("\App\Survey")
          ->where("survey_id", $survey->id)
          ->get()->last();
    }

    public function getSurveyResults() {
        return $this->surveyResult()
          // ->with("\App\Survey")
          ->get();
    }

    /**
     * Get the last
     */
    public function getLastBodyFat() {
        if ($this->bodyFat) {
          return $this->bodyFat->last();
        }

        return null;
    }

    /**
     * Get the last
     */
    public function getDailyData($id) {
        return $this->dailyDatas()->where("id", $id)->get()->last();
    }

    /**
     * Get the last
     */
    public function getLastDailyData() {
        return $this->dailyDatas()->where("open", 1)->get()->last();
    }

    /**
     * Get the user body params
     */
    public function bodyParams() {
        return $this->hasMany('App\UserBodyParam');
    }

    /**
     * Get the user body params
     */
    public function bodyFat() {
        return $this->hasMany('App\UserBodyFat');
    }

    /**
     * Get the user body params
     */
    public function dailyDatas() {
        return $this->hasMany('App\DailyData');
    }

    /**
     * Get the user body params
     */
    public function surveyResult() {
        return $this->hasMany('App\UserSurveyResult');
    }
}

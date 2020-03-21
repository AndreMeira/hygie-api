<?php

namespace App\Http\Controllers;

use App\User;
use App\UserInfo;
use App\UserBodyFat;
use App\UserBodyParam;
use App\Services\Computing\CaloriesRecommandations;
use App\Services\Computing\BodyFat as BodyFatComputing;
use App\Services\Formatters\User as UserFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    const REGISTER_VALIDATION = [
      'firstname' => ['required', 'string', 'max:255'],
      'lastname'  => ['required', 'string', 'max:255'],
      'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password'  => ['required', 'string', 'min:8', 'confirmed'],
    ];

    const BODY_PARAMS = [
      'height'        => ['required', 'integer', 'max:255'],
      'weight'        => ['required', 'integer', 'max:255'],
      'year_of_birth' => ['required', 'integer'],
      'gender'        => ['required', 'string'],
    ];

    const BODY_FAT = [
      'waist' => ['required', 'integer', 'max:255'],
      'neck'  => ['required', 'integer', 'max:255'],
      'hips'  => ['integer']
    ];

    private $formatter;

    private $bodyFatComputing;

    private $caloriesRecommandation;

    // public function __construct(UserFormatter $formatter) {
    public function __construct() {
      $this->caloriesRecommandation = new CaloriesRecommandations();
      $this->bodyFatComputing = new BodyFatComputing();
      $this->formatter = new UserFormatter(
        $this->bodyFatComputing
      );
    }

    public function me(Request $req) {
      $user = $req->user();

      return response()->json(
        $user ? $this->formatter->format($user) : new \stdClass
      );
    }

    public function logout(Request $req) {
      $user = $req->user();

      if ($user) {
          $user->token()->revoke();
      }
      // $req->user()->token()->delete();
      return response()->json([]);
    }

    public function updateBodyParams(Request $req) {
      $req->validate(self::BODY_PARAMS);

      $user = $req->user();
      $data = $req->all();
      $info = UserBodyParam::create([
        'user_id' => $user->id,
        'height'  => $data['height'],
        'weight'  => $data['weight'],
        'gender'  => $data['gender'],
        'year_of_birth' => $data['year_of_birth'],
      ]);

      return $this->me($req);
    }

    public function updateBodyFat(Request $req) {
      $req->validate(self::BODY_FAT);

      $user = $req->user();
      $data = $req->all() + ["hips" => 0];
      $info = UserBodyFat::create([
        'user_id' => $user->id,
        'waist'  => $data['waist'],
        'neck'   => $data['neck'],
        'hips'   => $data['hips'],
      ]);

      return $this->me($req);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function register(Request $req) {
      $req->validate(self::REGISTER_VALIDATION);

      $data = $req->all();
      $user = User::create([
          'email'    => $data['email'],
          'password' => Hash::make($data['password']),
          'name'     => $data['firstname'] . " " . $data['lastname'],
      ]);

      $info = UserInfo::create([
        'user_id'   => $user->id,
        'firstname' => $data['firstname'],
        'lastname'  => $data['lastname'],
      ]);

      return response()->json(
        $user->toArray() + $info->toArray()
      );
    }

    /**
     *
     */
    public function getCaloriesRecommandations(Request $req) {
      $result = $this->caloriesRecommandation->get(
        $req->user()->getLastBodyParam(),
        $req->user()->getLastBodyFat()
      );

      return response()->json($result);
    }
}

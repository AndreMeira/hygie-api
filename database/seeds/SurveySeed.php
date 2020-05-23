 <?php

use Illuminate\Database\Seeder;

class SurveySeed extends Seeder
{
    const DATAPATH = "/data";

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::transaction(function () {
            $this->fillDatabase();
        });
    }

    protected function fillDatabase() {
        DB::table('surveys')->update(['active' => false]);
        foreach (["food", "health"] as $cat) {
            $survey = $this->createSurvey($cat);
            $this->createQuestionsOfSurvey($survey);
        }
    }

    /**
     * @return row
     */
    protected function createSurvey($cat) {
        $id = DB::table('surveys')->insertGetId([
            "category" => $cat,
            "title"    => "Ton état de santé",
            "active"   => 1
        ]);

        return DB::table('surveys')->find($id);
    }

    /**
     * @return void
     */
    protected function createQuestionsOfSurvey($catAsObject) {
        $questions = $this->loadQuestions($catAsObject->category);
        foreach ($questions as $key => $question) {
            $questionAsObject = $this->createQuestion(
                $question, $catAsObject,
            );

            $this->createAnswersForQuestion(
              $questionAsObject, $question->answers
            );
        }
    }

    /**
     * @return void
     */
    protected function createQuestion($questionAsObject, $catAsObject) {
        $id = DB::table('survey_questions')->insertGetId([
            "survey_id"       => $catAsObject->id,
            "label"           => $questionAsObject->label,
            "multiple_choice" => $questionAsObject->multiple,
            "active"          => 1
        ]);

        return DB::table('survey_questions')->find($id);
    }

    /**
     * @return void
     */
    protected function createAnswersForQuestion($questionAsObject, $answers) {
        foreach ($answers as $key => $answer) {
            $this->createAnswer($questionAsObject, $answer);
        }
    }

    /**
     * @return void
     */
    protected function createAnswer($questionAsObject, $answer) {
        DB::table('survey_answers')->insert([
            "survey_id"          => $questionAsObject->survey_id,
            "survey_question_id" => $questionAsObject->id,
            "label"              => $answer->label,
            "score"              => $answer->score,
            "active"             => 1
        ]);
    }

    /**
     * @return void
     */
    protected function loadQuestions($cat) {
        $file = $this->getFilePath($cat);
        $data = file_get_contents($file);
        return @json_decode($data);
    }

    /**
     * @return void
     */
    private function getDataPath() {
        return __DIR__.self::DATAPATH;
    }

    /**
     * @return void
     */
    private function getFilePath($cat) {
        return $this->getDataPath()."/questions-${cat}.json";
    }
}

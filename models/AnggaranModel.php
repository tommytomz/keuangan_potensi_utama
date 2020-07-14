<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\RequiredValidator;

/**
 * Class ExampleModel
 * @package unclead\multipleinput\examples\actions
 */
class AnggaranModel extends Model
{

    public $schedule;

    // public function init()
    // {
    //     parent::init();
    //     $this->emails = [
    //         'test@test.com',
    //         'test2@test.com',
    //         'test3@test.com',
    //     ];

    //     $this->schedule = [
    //         [
    //             'day'       => '27.02.2015',
    //             'user_id'   => 31,
    //             'priority'  => 1,
    //             'enable'    => 1
    //         ],
    //         [
    //             'day'       => '27.02.2015',
    //             'user_id'   => 33,
    //             'priority'  => 2,
    //             'enable'    => 0
    //         ],
    //     ];

    //     $this->questions = [
    //         [
    //             'question' => 'test1',
    //             'answers' => [
    //                 [
    //                     'right' => 0,
    //                     'answer' => 'test1'
    //                 ],
    //                 [
    //                     'right' => 1,
    //                     'answer' => 'test2'
    //                 ]
    //             ]
    //         ]
    //     ];
    // }


    public function rules()
    {
        return [
            ['schedule', 'validateSchedule', 'skipOnEmpty' => false]
        ];
    }

    public function attributes()
    {
        return [
            'schedule'
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => $this->attributes()
        ];
    }

    /**
     * Phone number validation
     *
     * @param $attribute
     */

    public function validateSchedule($attribute)
    {   echo "gasdg";
        $requiredValidator = new RequiredValidator();
        echo "gasdg";
        foreach($this->$attribute as $index => $row) {
            $error = null;
            foreach (['kegiatan', 'jumlah'] as $name) {
                $error = null;
                $value = isset($row[$name]) ? $row[$name] : null;
                $requiredValidator->validate($value, $error);
                if (!empty($error)) {
                    $key = $attribute . '[' . $index . '][' . $name . ']';
                    $this->addError($key, $error);
                }
            }
        }
    }
}
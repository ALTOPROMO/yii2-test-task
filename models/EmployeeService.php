<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_service".
 *
 * @property int $id
 * @property int|null $employee_id
 * @property int|null $service_id
 */
class EmployeeService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id', 'service_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'service_id' => 'Service ID',
        ];
    }
}

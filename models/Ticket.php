<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property int|null $status
 * @property int|null $employee_id
 * @property string|null $text
 */
class Ticket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'employee_id'], 'integer'],
            [['text'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'employee_id' => 'Employee ID',
            'text' => 'Text',
        ];
    }

    /**
     * Метод фиксирует заявку в базе данных.
     * 
     * @param object $employee Сотрудник, закрепленный за заявкой
     * @param string $text Текст заявки
     * @return null|object 
     */
    public static function create($employee, $text) {
        $ticket = new Ticket();

        $ticket->status = 0;
        $ticket->employee_id = $employee->id;
        $ticket->text = $text;

        if($ticket->save()) {
            return $ticket;
        } else {
            return null;
        }
    }
}

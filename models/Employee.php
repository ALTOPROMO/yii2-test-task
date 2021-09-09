<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $start_date
 * @property int|null $end_dete
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_date', 'end_date'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }

    /**
     * Связь один ко многим
     * 
     * Забираем услуги, прикрепленные к операторам.
     */
    public function getService()
    {
        return $this->hasMany(EmployeeService::className(), ['employee_id' => 'id']);
    }

    /**
    * Данная функция возвращает свободного оператора по ID услуги.
     * 
     * @param int $service_id ID услуги
     * @return string
     */
    public static function getFreeByService($service_id)
    {
        // Здесь мы выбираем всех сотрудников, владеющих услугой и работающих в данный момент по графику.
        $employee = self::find()
            ->joinWith('service')
            ->onCondition(['=', 'employee_service.service_id', $service_id])
            ->where(['<=', 'employee.start_date', date('G')])
            ->andWhere(['>=', 'employee.end_date', date('G')])
            ->all();

        /* 
         * Далее, необходимо исключить всех уже занятых в данный момент операторов.
         */
        
        // Проверяем, есть ли сейчас работающие сотрудники, владеющие услугой.
        if($employee) {
            // Массив с ID свободных операторов.
            $free_employes = [];

            /* 
             * Проверим каждого оператора за занятость, и, если он свободен 
             * (нет задач в очереди со статусом (0 - то есть не завершена, в процессе) и нет этого оператора, прикрепленного
             * к задаче)
             */
            foreach($employee as $e) {
                $ticket = Ticket::find()
                    ->where([
                        'status' => 0,
                        'employee_id' => $e->id
                    ])
                    ->one();

                // Если оператор не занят, добавляем его ID в массив.
                if(!$ticket) {
                    $free_employes[] = $e->id;
                }
            }
        } else {
            return null;
        }    
        
        // Случайным образом выберем из свободных операторов кого-либо.
        $key_free_employee = array_rand($free_employes, 1);

        // И вернем модель этого оператора.
        return Employee::findOne($free_employes[$key_free_employee]);
    }
}

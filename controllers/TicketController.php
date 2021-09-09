<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Employee;
use app\models\Ticket;

class TicketController extends Controller
{
    /**
     * Метод формирует новую заявку.
     * 
     * Метод выбирает свободного оператора и фиксирует заявку в базе данных. 
     * Возвращает информацию о заявке и оператора, ответственного за нее.
     *
     * @return string
     */
    public function actionCreate()
    {
        $service_id = $_REQUEST['service_id'];
        $text = $_REQUEST['text'];

        // Получаем модель свободного сотрудника.
        $employee = Employee::getFreeByService($service_id);

        // Если нашелся свободный оператор.
        if($employee) {
            // Пробуем создать тикет.
            $ticket = Ticket::create($employee, $text);

            // Удостоверимся, что тикет создался успешно.
            if($ticket) {
                return $this->asJson([
                    'status' => 'success',
                    'text' => 'Тикет успешно создан.',
                    'employee' => $employee,
                    'ticket' => $ticket
                ]);
            } else {
                // А в случае провала, вернем человекопонятный текст, а так же текст ошибки для анализа 
                // (а можем отправить куда-либо, или просто сохранить в лог-файл).
                return $this->asJson([
                    'status' => 'error',
                    'text' => 'К сожалению, при создании заявки произошла ошибка. Мы уже решаем проблему!',
                    'error_message' => $ticket->errors,
                ]);
            }
        } else {
            return $this->asJson([
                'status' => 'error',
                'text' => 'К сожалению, свободных операторов пока нет. Попробуйте задать свой вопрос позже.',
            ]);
        }
    }
}

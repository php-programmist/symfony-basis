<?php

namespace App\Request;

use App\Http\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CallbackFormRequest implements RequestDTOInterface
{
    /**
     * @Assert\NotBlank(
     *     message="Укажите имя"
     *     )
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "В имени должно быть минимум {{ limit }} символа",
     *      maxMessage = "В имени должно быть максимум {{ limit }} символов"
     * )
     * @Assert\Regex(
     *     "#[\da-zA-Z]#",
     *     match=false,
     *     message="Укажите корректное имя"
     *     )
     */
    public $name;
    /**
     * @Assert\NotBlank(
     *     message="Укажите телефон"
     *     )
     * @Assert\Regex(
     *     "#\+(7|8)\s?\(\d{3}\)\s?\d{3}-\d{2}-\d{2}#",
     *     message="Укажите корректный телефон"
     *     )
     */
    public $phone;
    public $subject;
    public $referer;    
    
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($data) {
            $request->request->replace(is_array($data) ? $data : array());
        }
        
        if ($request->request->has('name')) {
            $this->name = trim($request->get('name'));
        } else {
            $this->name = "Не указано";
        }
        
        $this->phone               = trim($request->get('phone'));
        $this->subject             = trim($request->get('subject', 'Заказ звонка'));
        $this->referer             = $_SERVER['HTTP_REFERER'] ?? 'Нет';        
    }
    
}
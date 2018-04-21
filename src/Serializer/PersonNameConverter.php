<?php


namespace App\Serializer;


use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class PersonNameConverter implements NameConverterInterface
{
    public function normalize($propertyName)
    {
        return $propertyName;
    }

    /**
     * @param string $propertyName
     * @return string
     */
    public function denormalize($propertyName)
    {
        switch ($propertyName) {
            case 'Прізвище':
                $propertyName = 'lastName';
                break;
            case "Ім'я":
                $propertyName = 'firstName';
                break;
            case 'Контактний номер телефону ':
                $propertyName = 'phoneNumber';
                break;
            case 'Електронна адреса (якою ти найчастіше користуєшся)':
                $propertyName = 'email';
                break;
            case 'Вкажи свою спеціалізацію:':
                $propertyName = 'specialization';
                break;
            case 'Вкажіть назву закладу, в якому ти навчаєшся:':
                $propertyName = 'studyAt';
                break;
            case 'На якому курсі ти навчаєшся?':
                $propertyName = 'course';
                break;
            case 'Під що любиш програмувати:':
                $propertyName = 'favorite';
                break;
            case 'Приклад коду (посилання на GitHub), якщо є':
                $propertyName = 'codeSample';
                break;
            case 'Чи хотів б ти пройти стажування цього літа?':
                $propertyName = 'internship';
                break;
            case 'Чи працюєш ти в  IT компанії на даний момент?':
                $propertyName = 'employment';
                break;
        }

        return $propertyName;
    }
}
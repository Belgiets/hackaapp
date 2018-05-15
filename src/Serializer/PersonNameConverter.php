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
            case 'Вкажи назву закладу, в якому ти навчаєшся:':
                $propertyName = 'studyAt';
                break;
            case 'На якому курсі ти навчаєшся?':
                $propertyName = 'course';
                break;
            case 'Приклад коду (посилання на GitHub), якщо є':
                $propertyName = 'codeSample';
                break;
            case 'Чи хотілось би тобі пройти стажування цього літа?':
                $propertyName = 'internship';
                break;
            case 'Чи працюєш ти зараз в  IT компанії? ':
                $propertyName = 'employment';
                break;
        }

        return $propertyName;
    }
}
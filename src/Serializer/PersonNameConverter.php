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
        $test = null;
        switch ($propertyName) {
            case 'test':
                $propertyName = 'test';
                break;
        }

        return $propertyName;
    }
}
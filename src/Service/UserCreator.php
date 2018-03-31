<?php


namespace App\Service;


use App\Entity\User\AdminUser;
use App\Entity\User\SuperAdminUser;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserCreator
{
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ManagerRegistry $doctrine, UserPasswordEncoderInterface $userPasswordEncoder, ValidatorInterface $validatorInterface)
    {
        $this->doctrine = $doctrine;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->validator = $validatorInterface;
    }

    /**
     * @param $email
     * @param $password
     *
     * @return array
     */
    public function create($email, $password, $role)
    {
        $user = ('admin' === $role) ? new AdminUser() : new SuperAdminUser();
        $user->setPlainPassword($password);
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $user->getPlainPassword()));

        $out = ['user' => $user, 'errors' => []];
        if (count($errors = $this->validator->validate($user)) > 0) {
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $out['errors'][] = [
                    'field' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                    'invalidValue' => $error->getInvalidValue(),
                ];
            }
        } else {
            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $out;
    }
}
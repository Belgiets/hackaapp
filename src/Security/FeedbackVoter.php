<?php


namespace App\Security;


use App\Entity\Feedback;
use App\Entity\User\BaseUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class FeedbackVoter extends Voter
{
    const ACTIONS = [
        'FB_EDIT' => 'FB_EDIT'
    ];

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, self::ACTIONS)) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Feedback) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof BaseUser) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if ($this->security->isGranted(BaseUser::ROLE_SUPER_ADMIN)) {
            return true;
        }

        /** @var Feedback $feedback */
        $feedback = $subject;

        switch ($attribute) {
            case self::ACTIONS['FB_EDIT']:
                return $this->canEdit($feedback, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Feedback $feedback, BaseUser $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $feedback->getOwner();
    }
}
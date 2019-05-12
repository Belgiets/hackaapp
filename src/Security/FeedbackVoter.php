<?php


namespace App\Security;


use App\Entity\Feedback;
use App\Entity\Participant;
use App\Entity\User\BaseUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class FeedbackVoter extends Voter
{
    const ACTIONS = [
        'FB_EDIT' => 'FB_EDIT',
        'FB_NEW' => 'FB_NEW'
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

        if ($subject instanceof Feedback || $subject instanceof Participant) {
            return true;
        }

        return false;
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

        switch ($attribute) {
            case self::ACTIONS['FB_EDIT']:
                /** @var Feedback $feedback */
                $feedback = $subject;

                return $this->canEdit($feedback, $user);
            case self::ACTIONS['FB_NEW']:
                /** @var Participant $participant */
                $participant = $subject;

                return $this->canNew($participant, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Feedback $feedback, BaseUser $user)
    {
        return $user === $feedback->getOwner();
    }

    private function canNew(Participant $participant, BaseUser $user)
    {
        $team = $participant->getTeam();
        $mentors = $team->getMentors();

        return $mentors->contains($user);
    }
}
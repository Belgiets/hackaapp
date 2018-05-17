<?php

namespace App\Command;

use App\Service\UserCreator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppCreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    /**
     * @var UserCreator
     */
    private $creator;

    public function __construct(UserCreator $creator)
    {
        parent::__construct();
        $this->creator = $creator;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create superadmin|admin user')
            ->addArgument('email', InputArgument::REQUIRED, 'The email')
            ->addArgument('password', InputArgument::REQUIRED, 'The password')
            ->addArgument('role', InputArgument::OPTIONAL, 'User role admin(superadmin by default)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $role = empty($input->getArgument('role')) ? 'superadmin' : 'admin';

        $status = $this->creator->create($email, $password, $role);

        $io = new SymfonyStyle($input, $output);
        if (count($status['errors'])) {
            foreach ($status['errors'] as $error) {
                $io->error('Error: ' . json_encode($error, true));
            }
        } else {
            $io->success('User created: ' . json_encode($status['user'], true));
        }
    }
}

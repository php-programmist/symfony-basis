<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreateCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $password_encoder;
    
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $password_encoder)
    {
        parent::__construct(null);
        $this->em = $em;
        $this->password_encoder = $password_encoder;
    }
    
    protected static $defaultName = 'user:create';

    protected function configure()
    {
        $this
            ->setDescription('Create an admin')
            ->setDefinition(
                [
                    new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                    new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                    new InputArgument('roles', InputArgument::IS_ARRAY, 'The roles'),
                ]
            );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $roles = $input->getArgument('roles');
        if (empty($roles)) {
            $roles = ['ROLE_ADMIN'];
        }
        $user = new User();
        $user->setUsername($username)
             ->setPassword($this->password_encoder->encodePassword($user, $password));
    
        $user->setRoles($roles);
        $this->em->persist($user);
        $this->em->flush();
    
        $output->writeln(sprintf('User <comment>%s</comment> created', $username));
        return 0;
    }
}

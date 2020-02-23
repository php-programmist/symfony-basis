<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserChangePasswordCommand extends Command
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
    
    protected static $defaultName = 'user:change-password';

    protected function configure()
    {
        $this
            ->setDescription('Change admin password')
            ->setDefinition(
                [
                    new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                    new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                ]
            );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
    
        if (null === ($user = $this->em->getRepository(User::class)->findOneBy(['username'=>$username]))) {
            $output->writeln(sprintf('<error>User %s was not found</error>', $username));
            return 1;
        }
        $user->setPassword($this->password_encoder->encodePassword($user, $password));
        $this->em->flush();
        $output->writeln(sprintf('User <comment>%s</comment> password changed', $username));
        return 0;
    }
}

<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MakeUserCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:make:user';

    private $passwordEncoder;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct();
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create a new user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $question = (new Question('What is the user\'s email address? '))
            ->setNormalizer(
                function ($value) {
                    return $value ? trim($value) : '';
                }
            )
            ->setValidator(
                function ($email) {
                    if (!is_string($email) || !filter_var($email, \FILTER_VALIDATE_EMAIL)) {
                        throw new \RuntimeException('Please enter a valid email address');
                    }

                    $previous_user = $this
                                    ->getContainer()
                                    ->get('doctrine')
                                    ->getRepository(User::class)
                                    ->findOneBy(
                                        array(
                                            'email' => $email,
                                        )
                                    )
                    ;

                    if($previous_user){
                        throw new \RuntimeException('A user with this email address already exists');
                    }


                    return $email;
                }
            )
        ;

        $email = $helper->ask($input, $output, $question);


        $question = new Question('What is the user\'s new password? ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $question);

        $user = new User();
        $user->setEmail($email);

        // encode the plain password
        $user->setPassword(
            $this
                ->passwordEncoder
                ->encodePassword(
                    $user,
                    $password
                )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success("Successfully create new user {$email}");
    }
}

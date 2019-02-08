<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MakeUserCommand extends Command
{
    protected static $defaultName = 'app:make:user';

    private $passwordEncoder;
    private $entityManager;

    private $input;
    private $output;

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
        //We need these stored for the helpers
        $this->input = $input;
        $this->output = $output;

        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $question = (new Question('What is the user\'s email address? '))

            //Trim extra whitespace
            ->setNormalizer(
                function ($value) {
                    return $value ? trim($value) : '';
                }
            )

            //Force a valid email address
            ->setValidator(
                function ($email) {
                    if (!is_string($email) || !filter_var($email, \FILTER_VALIDATE_EMAIL)) {
                        throw new \RuntimeException('Please enter a valid email address');
                    }

                    return $email;
                }
            )
        ;

        $email = $helper->ask($input, $output, $question);

        //Try getting the current user
        $user = $this->get_user_by_email($email);

        $is_new_user = is_null($user);
        $is_dirty = false;
        if($is_new_user){
            $user = new User();
            $user->setEmail($email);
            $is_dirty = true;
        }

        $methods = [
            'perform_email_change_question',
            'perform_password_question',
        ];

        foreach($methods as $method){
            if($this->$method($user, $is_new_user)){
                $is_dirty = true;
            }
        }

        if(!$is_dirty){
            $io->note('No changes to the user\'s information were detected so no work was done');
            return;
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        if($is_new_user){
            $io->success("Successfully created new user {$email}");
        }else{
            $io->success("Successfully updated user {$email}");
        }
    }

    protected function get_user_by_email(string $email) : ?User
    {
        return $this
                    ->entityManager
                    ->getRepository(User::class)
                    ->findOneBy(
                        array(
                            'email' => $email,
                        )
                    )
        ;

    }

    protected function perform_email_change_question(User $user, bool $is_new_user) : bool
    {
        if($is_new_user){
            //Don't offer for new users, that's just stupid
            return false;
        }

        while(true){

            if(!$this->ask_yes_or_no_question('Would you like to change the user\'s email? ', false)){
                return false;
            }

            $helper = $this->getHelper('question');
            $question = (new Question('What is the user\'s new email address? '))

                //Trim extra whitespace
                ->setNormalizer(
                    function ($value) {
                        return $value ? trim($value) : '';
                    }
                )

                //Force a valid email address
                ->setValidator(
                    function ($email) {
                        if (!is_string($email) || !filter_var($email, \FILTER_VALIDATE_EMAIL)) {
                            throw new \RuntimeException('Please enter a valid email address');
                        }

                        if($this->get_user_by_email($email)){
                            throw new \RuntimeException('That email address is already in use');
                        }

                        return $email;
                    }
                )
            ;

            $email = $helper->ask($this->input, $this->output, $question);
            $user->setEmail($email);

            return true;

        }


    }

    protected function perform_password_question(User $user, bool $is_new_user) : bool
    {
        if(!$is_new_user){
            if(!$this->ask_yes_or_no_question('Would you like to change the user\'s password? ', false)){
                return false;
            }
        }

        //Setup the question
        $question = new Question('What is the user\'s new password? ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);

        //Ask it
        $password = $helper->ask($this->input, $this->output, $question);

        $user->setPassword(
            $this
                ->passwordEncoder
                ->encodePassword(
                    $user,
                    $password
                )
        );

        return true;
    }

    protected function ask_yes_or_no_question(string $question, bool $default_value) : bool
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion( $question, $default_value );
        return $helper->ask($this->input, $this->output, $question);
    }
}

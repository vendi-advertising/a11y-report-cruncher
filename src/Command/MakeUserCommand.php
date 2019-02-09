<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
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

    private const CLIENT_OPTION_ADD       = 'Add';
    private const CLIENT_OPTION_ERASE_ALL = 'Erase all';
    private const CLIENT_OPTION_REMOVE    = 'Remove';
    private const CLIENT_OPTION_VIEW      = 'View';
    private const CLIENT_OPTION_CANCEL    = 'Cancel (undo any client changes)';
    private const CLIENT_OPTION_DONE      = 'Done (no more client changes)';

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
            ->addArgument('email', InputArgument::OPTIONAL, 'User\'s email address')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //We need these stored for the helpers
        $this->input = $input;
        $this->output = $output;

        $io = new SymfonyStyle($input, $output);

        //Allow this to come in on the actual command line
        $email = $input->getArgument('email');
        if(!$email){

            //If we didn't pass one in, prompt here
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

            $email = $this->getHelper('question')->ask($input, $output, $question);
        }

        //Try getting the current user
        $user = $this->get_user_by_email($email);

        $is_new_user = is_null($user);
        $is_dirty = false;
        if ($is_new_user) {
            $user = new User();
            $user->setEmail($email);
            $is_dirty = true;
        }

        $methods = [
            'perform_email_change_question',
            'perform_password_question',
            'perform_role_question',
            'perform_client_question',
        ];

        foreach ($methods as $method) {
            if ($this->$method($user, $is_new_user)) {
                $is_dirty = true;
            }
        }

        if (!$is_dirty) {
            $io->note('No changes to the user\'s information were detected so no work was done');
            return;
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        if ($is_new_user) {
            $io->success("Successfully created new user {$email}");
        } else {
            $io->success("Successfully updated user {$email}");
        }
    }

    protected function get_all_client_options() : array
    {
        return [
                    'A' => self::CLIENT_OPTION_ADD,
                    'E' => self::CLIENT_OPTION_ERASE_ALL,
                    'R' => self::CLIENT_OPTION_REMOVE,
                    'V' => self::CLIENT_OPTION_VIEW,
                    'C' => self::CLIENT_OPTION_CANCEL,
                    'D' => self::CLIENT_OPTION_DONE,
            ]
        ;
    }

    protected function get_user_by_email(string $email) : ?User
    {
        return $this
                    ->entityManager
                    ->getRepository(User::class)
                    ->findOneBy(
                        [
                            'email' => $email,
                        ]
                    )
        ;
    }

    protected function get_client_by_name(string $name) : ?Client
    {
        return $this
                    ->entityManager
                    ->getRepository(Client::class)
                    ->findOneBy(
                        [
                            'name' => $name,
                        ]
                    )
        ;
    }

    protected function get_all_client_names() : array
    {
        $clients =  $this
                     ->entityManager
                        ->getRepository(Client::class)
                        ->findAll()
        ;

        $names = [];
        array_walk(
            $clients,
            function(Client $client) use (&$names)
            {
                $names[] = $client->getName();
            }
        )
        ;

        return $names;
    }

    protected function perform_email_change_question(User $user, bool $is_new_user) : bool
    {
        if ($is_new_user) {
            //Don't offer for new users, that's just stupid
            return false;
        }

        while (true) {

            if (!$this->ask_yes_or_no_question('Would you like to change the user\'s email?', false)) {
                return false;
            }

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

                                    if ($this->get_user_by_email($email)) {
                                        throw new \RuntimeException('That email address is already in use');
                                    }

                                    return $email;
                                }
                            )
            ;

            $email = $this->getHelper('question')->ask($this->input, $this->output, $question);
            $user->setEmail($email);

            return true;
        }
    }

    protected function perform_role_question(User $user, bool $is_new_user) : bool
    {
        if (!$is_new_user) {
            if (!$this->ask_yes_or_no_question('Would you like to change the user\'s role?', false)) {
                return false;
            }
        }

        $all_roles = array_diff(User::get_all_possible_roles(), [User::get_required_role()]);

        $question = new ChoiceQuestion(
            sprintf('User roles (the role %1$s is required and cannot be unset)', User::get_required_role()),
            $all_roles,
            ''
        );
        $question->setMultiselect(true);

        $user_roles = $this->getHelper('question')->ask($this->input, $this->output, $question);
        $user_roles[] = User::get_required_role();
        $user->setRoles($user_roles);

        return true;
    }

    protected function do_client_action__erase(User $user)
    {
        foreach($user->getClients() as $c){
            $user->removeClient($c);
        }
    }

    protected function do_client_action__view(User $user)
    {
        $clients = $user->getClients();
        if(!count($clients)){
            $io = new SymfonyStyle($this->input, $this->output);
            $io->note('No clients assigned to user');
            return;
        }

        $client_data = [];
        foreach($clients as $c){
            $client_data[] = [$c->getId(), $c->getName()];
        }

        (new Table($this->output))
            ->setHeaders(['Id', 'Client',])
            ->setRows($client_data)
            ->render()
        ;
    }

    protected function do_client_action__add_or_remove(User $user, string $add_or_remove)
    {
        switch($add_or_remove){
            case 'add':
                $client_names = $this->get_all_client_names();
                break;

            case 'remove':
                $client_names = array_map(
                    function($v){
                        return $v->getName();
                    },
                    \iterator_to_array($user->getClients())
                );
                break;

            default:
                throw new \RuntimeException('Unhandled switch value ' . $add_or_remove);
        }


        $question = (new Question('Please enter the client name or press return to cancel'))
                        ->setAutocompleterValues($client_names)
                        ->setValidator
                            (function ($client_name) use ($client_names) {
                                if(!$client_name){
                                    return '';
                                }

                                if(!in_array($client_name, $client_names)){
                                    throw new \RuntimeException('Client not found ');
                                }

                                return $client_name;
                            }
                        )
        ;

        $client_name = $this->getHelper('question')->ask($this->input, $this->output, $question);

        if(!$client_name){
            return;
        }

        $client = $this->get_client_by_name($client_name);
        if(!$client){
            $io = new SymfonyStyle($this->input, $this->output);
            $io->caution('Could not find the supplied client');
            return;
        }

        switch($add_or_remove){
            case 'add':
                $user->addClient($client);
                break;

            case 'remove':
                $user->removeClient($client);
                break;

            default:
                throw new \RuntimeException('Unhandled switch value ' . $add_or_remove);
        }
    }

    protected function perform_client_question(User $user, bool $is_new_user) : bool
    {
        if ($is_new_user) {
            $text = 'Would you like to assign clients now? (This is not needed for Global Admins)';
        }else{
            $text = 'Would you like to change the user\'s clients? (This is not needed for Global Admins)';
        }

        if (!$this->ask_yes_or_no_question($text, false)) {
            return false;
        }

        //We're going to work off of a cloned user so that we can use cancel
        //as one of the options. However, since the clone is shallow, we instead
        //need to manually copy the properties that we're interested in. At this
        //I realize that I could probably just rename the variable instead of
        //appending this comment, but, well, you know, I already started typing
        //and all.
        $cloned_user = new User();
        foreach($user->getClients() as $c){
            $cloned_user->addClient($c);
        }

        while(true){

            $question = (new ChoiceQuestion( 'What would you like to do', self::get_all_client_options()))
                            ->setNormalizer(
                                function ($value) {
                                    $value = strtoupper(trim($value ?? '')) . '!';
                                    $char = $value[0];
                                    return $char;
                                }
                            )
                            ->setValidator
                                (function ($answer) {
                                    $client_options = self::get_all_client_options();

                                    if(!array_key_exists($answer, $client_options)){
                                        throw new \RuntimeException('Please enter one of the supplied options');
                                    }

                                    return $client_options[$answer];
                                }
                            )
            ;

            $what_to_do = $this->getHelper('question')->ask($this->input, $this->output, $question);
            switch($what_to_do){
                case self::CLIENT_OPTION_ADD:
                    $this->do_client_action__add_or_remove($cloned_user, 'add');
                    break;

                case self::CLIENT_OPTION_REMOVE:
                    $this->do_client_action__add_or_remove($cloned_user, 'remove');
                    break;

                case self::CLIENT_OPTION_ERASE_ALL:
                    $this->do_client_action__erase($cloned_user);
                    break;

                case self::CLIENT_OPTION_VIEW:
                    $this->do_client_action__view($cloned_user);
                    break;

                case self::CLIENT_OPTION_CANCEL:
                    //Nothing was changed
                    return false;

                case self::CLIENT_OPTION_DONE:
                    //Reset the user object
                    $this->do_client_action__erase($user);

                    //Copy from the clone
                    foreach($cloned_user->getClients() as $c){
                        $user->addClient($c);
                        $c->addUser($user);
                    }
                    return true;

                default:
                    throw new \RuntimeException('Unhandled switch value: ' . $what_to_do);
                    dump($what_to_do);
            }
        }

        return true;

    }

    protected function perform_password_question(User $user, bool $is_new_user) : bool
    {
        if (!$is_new_user) {
            if (!$this->ask_yes_or_no_question('Would you like to change the user\'s password?', false)) {
                return false;
            }
        }

        //Setup the question
        $question = new Question('What is the user\'s new password? ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);

        //Ask it
        $password = $this->getHelper('question')->ask($this->input, $this->output, $question);

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
        $y_or_n = $default_value ? '[Y/n] ' : '[y/N] ';
        $question = new ConfirmationQuestion($question . ' ' . $y_or_n, $default_value);
        return $this->getHelper('question')->ask($this->input, $this->output, $question);
    }
}

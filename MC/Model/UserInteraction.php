<?php
    declare(strict_types=1);
    
    namespace MVC\Model\UserInteraction;

    require_once($_SERVER['DOCUMENT_ROOT'] . '/MC/Model/DatabaseInteractor.php');
    
    use MVC\Model\DatabaseInteractor;
    
    // Я намудрил в целях увеличить гибкость функциональности авторизации.
    abstract class AuthorizationStrategy
    {
        protected DatabaseInteractor $interactor;
        protected string $argument;
        protected string $password;
        protected User $user;

        public function __construct(string $arg, string $pass)
        {
            $this->interactor = DatabaseInteractor::getInstance();
            $this->argument = $arg;
            $this->password = $pass;
        }

        protected function startSession() : void
        {
            session_start();
            $_SESSION['user'] = serialize($this->user);
        }

        // Поиск пользователя по аргументу $arg
        abstract protected function getInformation() : ?array;

        public function authorize() : bool
        {
            $userInformation = $this->getInformation();
            if (isset($userInformation) && $userInformation['password'] == md5($this->password))
            {
                $this->user = new User($userInformation);
                $this->startSession();
                return true;
            }
            return false;
        }
    }

    class LoginStrategy extends AuthorizationStrategy
    {
        protected function getInformation() : ?array
        {
            $sqlCommand = "SELECT * FROM `user_login_information` WHERE `login` = '{$this->argument}'";
            return $this->interactor->fetch_hash_table($sqlCommand);
        }
    }

    class EmailStrategy extends AuthorizationStrategy
    {
        protected function getInformation() : ?array
        {
            $sqlCommand = "SELECT * FROM `user_login_information` WHERE `email` = '{$this->argument}'";
            return $this->interactor->fetch_hash_table($sqlCommand);
        }
    }

    class AuthorizationFactory
    {
        public static function authMethod(string $by, string $arg, string $pass) : AuthorizationStrategy
        {
            if ($by == 'email')
                return new EmailStrategy($arg, $pass);
            else if ($by == 'login')
                return new LoginStrategy($arg, $pass);
        }
    }
    
    class User
    {
        public int $id;
        public string $login;
        public string $password;
        public string $email;

        public function __construct(array $userInformation)
        {
            $this->login = $userInformation['login'];
            $this->password = $userInformation['password'];
            $this->email = $userInformation['email'];
            $this->id = (int) $userInformation['id'];
        }
    }
?>
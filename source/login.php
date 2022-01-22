<?php 
    require_once 'source/template.php';
    require_once 'source/db(PDO).php';

    /**
     * Class for working with the login and register forms
     *
     * @author Maksim Mikhalkov
     * @version 1.0
     */
	class LogOrReg {
        /**
         * @var object database link
         */
        private object $sql;

        /**
         * Creating a database connection
         */
		public function __construct() {
            $this->sql = new DataBase();
		}

        /**
         * Delete a sql copy
         */
		public function __destruct() {
            unset($this->sql);
		}

        /**
         * Try login by cookie
         * 
         * @return bool login result
         */
        public function tryLogin(): bool {
            // Remember function
            if (isset($_COOKIE['remember'])) {
                $info = $this->sql->getUserInfoByCookie($_COOKIE['remember']);
    
                if ($info) {
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['id'] = $info['id'];
                    $_SESSION['name'] = $info['name'];
                    $_SESSION['avatar'] = $info['avatar'];
        
                    return true;
                }
                else {
                    $_SESSION['loggedIn'] = false;
                    return false; 
                }
            }    
            else {
                $_SESSION['loggedIn'] = false;
                return false;
            }
        }
        
        /**
         * Get login or register page
         * 
         * @return string final page
         */
        public function getPage(): string {
            // Create a page
            $page = new Template();
            $page->setTemplate('templates/index.tpl');

            // Try login by login and password
            if (isset($_POST['letLog'])) {
                if ((isset($_SESSION['loggedIn'])) && ($_SESSION['loggedIn'] === true)) {
                    header('Location: view.php');
                }
                else {
                    $login = (isset($_POST['login'])) ? $_POST['login'] : '';
                    $password = (isset($_POST['password'])) ? $_POST['password'] : '';
                    $remember = (isset($_POST['rememberMe'])) ? true : false;
        
                    $result = $this->login($login, $password, $remember);
                    if ($result === true) {
                        header('Location: view.php');
                    } else {
                        // Create a login page
                        return $page->getLogOrRegPage('log', '<font color="red">Error! Check your information...</font>');
                    }
                }
            }
            // Try register 
            elseif (isset($_POST['letReg'])) {
                $name = (isset($_POST['name'])) ? $_POST['name'] : '';
                $email = (isset($_POST['email'])) ? $_POST['email'] : '';
                $login = (isset($_POST['login'])) ? $_POST['login'] : '';
                $password = (isset($_POST['password'])) ? $_POST['password'] : '';
                $captcha = (isset($_POST['captcha'])) ? $_POST['captcha'] : '';
                
                $result = $this->register($name, $email, $login, $password, $captcha);
                if ($result === true) {
                    header('Location: index.php');
                } else {
                    // Create a login page
                    return $page->getLogOrRegPage('reg', '<font color="red">Error! Check your information...</font>');
                }
            }
            
            if (isset($_POST['reg'])) {
                // Create a reg page
                return $page->getLogOrRegPage('reg');
            }
            else {
                // Create a login page
                return $page->getLogOrRegPage('log');
            } 
        }

        /**
         * Login by login and password
         * 
         * @param string $login - user login
         * @param string $password - user password
         * @param bool $remember - for remember function
         * 
         * @return bool login result
         */
        private function login(string $login, string $password, bool $remember): bool {
            $info = $this->sql->getUserInfoByLogin($login, $password);

            if ($info) {
                $_SESSION['loggedIn'] = true;
                $_SESSION['id'] = $info['id'];
                $_SESSION['name'] = $info['name'];
                $_SESSION['avatar'] = $info['avatar'];
    
                if ($remember === true) {
                    $auth = sha1(mt_rand(1, 2000000000) . mt_rand(1000000, 9999999) . mt_rand(1, 2000000000));
                    $this->sql->updateAuth($auth, $login, $password);
                    setcookie('remember', $auth, time() + 1209600);
                }
                return true;
            }
            else {
                $_SESSION['loggedIn'] = false;
                return false; 
            }
        }

        /**
         * Register
         * @param string $name - user name
         * @param string $email - user e-mail
         * @param string $login - user login
         * @param string $password - user password
         * @param string $captcha - math result
         * 
         * @return bool register result
         */
        private function register(string $name, string $email, string $login, string $password, string $captcha): bool {
            if (($captcha == $_SESSION['randNumber']) && !empty($name) && !empty($email) && !empty($login) && !empty($password)) {
                return $this->sql->regNewUser($name, $email, $login, $password);
            }
            else {
                return false;
            }
        }
	}

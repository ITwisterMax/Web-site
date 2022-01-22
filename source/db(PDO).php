<?php
    require_once 'source/logs.php';

    /**
     * Class for working with the MySQL database (PDO)
     *
     * @author Maksim Mikhalkov
     * @version 2.0
     */
	class DataBase {
        /**
         * @var object connection link
         */
		private object $link;

        /**
         * @var object errors
         */
        private object $logs;

        /**
         * @var array parameters for connecting to the database
         */
        private array $dbArray = array(
            'ip' => '127.0.0.1',
            'login' => 'root',
            'password' => '12345678',
            'db' => 'courses'
        );

        /**
         * Creating a database connection
         */
		public function __construct() {
            $this->logs = new Errors();
            
            // Database connection
            try {
                $this->link = new PDO(
                    "mysql:host={$this->dbArray['ip']};dbname={$this->dbArray['db']}", 
                    $this->dbArray['login'],
                    $this->dbArray['password']);
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->dbError($e->getMessage());
            }
		}

        /**
         * Close a database connection
         */
		public function __destruct() {
            unset($this->link);
		}

        /**
         * Executing select query
         * 
         * @param int $offset - offset in database
         * @param int $limit - posts limit
         * 
         * @return array posts list
         */
        public function selectPosts(int $offset, int $limit) : array {
            try {
                $result = $this->link->query("SELECT `posts_list`.*, `categories_list`.`category_name`
                                            FROM `posts_list`
                                            LEFT JOIN `categories_list` ON  `posts_list`.`category` = `categories_list`.`category_id`
                                            LIMIT $limit OFFSET $offset");
                $result = $result->fetchAll();

                foreach ($result as &$element) {
                    // Error processing (decode)
                    $element[1] = htmlspecialchars_decode($element[1]);
                    $element[2] = htmlspecialchars_decode($element[2]);
                    $element[5] = htmlspecialchars_decode($element[5]);
                }

                return $result;
            }
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }

        /**
         * Executing select query
         * 
         * @return int posts count
         */
        public function getPostsCount(): int {
            try {
                $result = $this->link->query("SELECT COUNT(`id`) FROM `posts_list`");
                return $result->fetchColumn(); 
            }
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }

        /**
         * Executing select query
         * 
         * @param string $auth - specifical token
         * 
         * @return array|bool query result
         */
        public function getUserInfoByCookie(string $auth): array|bool {
            try {
                $auth = $this->link->quote(trim($auth));

                $result = $this->link->query("SELECT `id`, `name`, `avatar` FROM `users` WHERE `remember` = $auth");
                return $result->fetch();
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }

        /**
         * Executing select query
         * 
         * @param string $login - user login
         * @param string $password - user password
         * 
         * @return array|bool query result
         */
        public function getUserInfoByLogin(string $login, string $password): array|bool {
            try {
                $login = $this->link->quote(trim($login));
                $password = $this->link->quote(sha1(trim($password)));

                $result = $this->link->query("SELECT `id`, `name`, `avatar` FROM `users` WHERE `login`= $login AND `password`= $password");
                return $result->fetch();
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }
        
        /**
         * Executing update query
         * 
         * @param string $auth - specifical token
         * @param string $login - user login
         * @param string $password - user password
         * 
         * @return object query result
         */
        public function updateAuth(string $auth, string $login, string $password): object {
            try {
                $auth = $this->link->quote(trim($auth));
                $login = $this->link->quote(trim($login));
                $password = $this->link->quote(sha1(trim($password)));

                return $this->link->query("UPDATE `users` SET `remember`= $auth WHERE `login`= $login AND `password`= $password");
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }

        /**
         * Executing insert query
         * 
         * @param string $name - specifical token
         * @param string $email - user e-mail
         * @param string $login - user login
         * @param string $password - user password
         * 
         * @return bool query result
         */
        public function regNewUser(string $name, string $email, string $login, string $password): bool {
            try {
                $name = $this->link->quote(trim($name));
                $email = $this->link->quote(trim($email));
                $login = $this->link->quote(trim($login));
                $password = $this->link->quote(sha1(trim($password)));

                $query = $this->link->query("SELECT * FROM `users` WHERE `login` = $login");
                $res = $query->fetchAll();

                if (!$res)
                {
                    $result = $this->link->prepare("INSERT INTO `users` SET `name` = $name, `email` = $email,
                                                `login` = $login, `password` = $password");
                    return $result->execute();
                }

                return false;
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }

        /**
         * Executing update query
         * 
         * @param string $login - user login
         * @param string $email - user e-mail
         * 
         * @return bool query result
         */
        public function updatePassword(string $login, string $email): bool {
            try {
                $login = $this->link->quote(trim($login));
                $email = $this->link->quote(trim($email));

                $query = $this->link->query("SELECT * FROM `users` WHERE `login` = $login AND `email` = $email");
                $res = $query->fetchAll();

                if ($res)
                {
                    $password = mt_rand(1, 2000000000) . mt_rand(1000000, 9999999) . mt_rand(1, 2000000000);
                    $_SESSION['newPassword'] = $password;
                    $password = $this->link->quote(sha1($password));

                    $result = $this->link->prepare("UPDATE `users` SET `password` = $password WHERE `login`= $login AND `email`= $email");
                    return $result->execute();
                }

                return false;
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }

        /**
         * Executing update query
         * 
         * @param int $id - specifical user id
         * 
         * @return object query result
         */
        public function removeRemember(int $id): object {
            try {
                return $this->link->query("UPDATE `users` SET `remember`='' WHERE `id`= '$id'");
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }
        
        /**
         * Executing update query
         * 
         * @param int $id - specifical user id
         * @param string $avatar - specifical user avatar
         * 
         * @return object query result
         */
        public function updateAvatar(int $id, string $avatar): object {
            try {
                $avatar = $this->link->quote(trim($avatar));
                return $this->link->query("UPDATE `users` SET `avatar`=$avatar WHERE `id`= '$id'");
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }

        /**
         * Executing select query
         * 
         * @return array|bool query result
         */
        public function getCategories(): array|bool {
            try {
                $result = $this->link->query("SELECT * FROM `categories_list`");
                return $result = $result->fetchAll();
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }

        /**
         * Executing select query
         * 
         * @param int $id - specifical post id
         * @return array|bool query result
         */
        public function getPost(int $id): array|bool {
            try {
                $result = $this->link->query("SELECT * FROM `posts_list` WHERE `id` = {$id}");
                return $result->fetch();
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }

        /**
         * Executing update query (posts_list)
         *
         * @param array $infoArray - array with information about the post
         * @return object query result
         */
        public function updatePost(array &$infoArray): object {
            try {
                // Adding necessary information
                $infoArray['category'] = $infoArray['category'][0];
                $infoArray['time'] = date("Y-m-d H:i:s");

                // Injection protection
                $infoArray['title'] = htmlspecialchars($this->link->quote(trim($infoArray['title'])));
                $infoArray['description'] = htmlspecialchars($this->link->quote(trim($infoArray['description'])));
                $infoArray['author'] = htmlspecialchars($this->link->quote(trim($infoArray['author'])));

                return $this->link->query("UPDATE `posts_list` SET `title` = {$infoArray['title']}, `description` = {$infoArray['description']},
                                        `updated_at` = '{$infoArray['time']}', `author` = {$infoArray['author']}, `category` = '{$infoArray['category']}' 
                                        WHERE `id` = '{$infoArray['id']}'");
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }

        /**
         * Executing delete query
         * 
         * @param int $id - specifical post id
         * @return object query result
         */
        public function deletePost(int $id): object {
            try {
                return $this->link->query("DELETE FROM `posts_list` WHERE `id` = {$id}");
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }

         /**
         * Executing insert query
         *
         * @param array $infoArray - array with information about the post
         * @return object query result
         */
        public function AddNewPost(array &$infoArray): object {
            try {
                // Injection protection
                $infoArray['title'] = htmlspecialchars($this->link->quote(trim($infoArray['title'])));
                $infoArray['description'] = htmlspecialchars($this->link->quote(trim($infoArray['description'])));
                $infoArray['author'] = htmlspecialchars($this->link->quote(trim($infoArray['author'])));
                $infoArray['category'] = $infoArray['category'][0];

                return $this->link->query("INSERT INTO `posts_list` SET `title` = {$infoArray['title']}, `description` = {$infoArray['description']},
                `author` = {$infoArray['author']}, `category` = '{$infoArray['category']}'");
            }
            // Error processing
            catch (PDOException $e) {
                $this->logs->requestError($e->getMessage());
            }
        }
	}

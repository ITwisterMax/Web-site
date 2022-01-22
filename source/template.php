<?php
    require_once 'source/db(PDO).php';
    require_once 'source/loadFiles.php';
    require_once 'source/logs.php';

    /**
     * Class for working with the templates
     *
     * @author Maksim Mikhalkov
     * @version 1.0
     */
    class Template{
        /**
         * @var string initial template
         */
        private string $template;

        /**
         * @var object errors
         */
        private object $logs;

        /**
         * Creating a errors copy
         */
        public function __construct() {
            $this->logs = new Errors();
		}

        /**
         * Delete a logs copy
         */
		public function __destruct() {
            unset($this->logs);
		}

        /**
         * Set main template
         *
         * @param string $mainTemplateFilename - path to file
         */
        public function setTemplate(string $mainTemplateFilename) {
            if (!is_file($mainTemplateFilename)) {
                $this->logs->templateError($mainTemplateFilename);
            }

            $this->template = file_get_contents($mainTemplateFilename);
        }

        /**
         * Get view posts page
         * 
         * @param int $offset - offset in database
         * @param int $limit - posts limit
         * 
         * @return string final page
         */
        public function getViewPage(int $offset = 0, int $limit = 5): string {
            // Create a batabase connection
            $sql = new DataBase();
            $postsList = $sql->selectPosts($offset, $limit);
            $elements = '';

            // Get final posts list
            foreach($postsList as $element) {
                $elements .= "<li><div class=\"left\"><img src=\"images/news.png\"></div>
                            <div class=\"right\">Title: {$element[1]}<br>Description: {$element[2]}<br>Created at: {$element[3]}
                            <br>Updated at: {$element[4]}<br>Author: {$element[5]}<br>Category: {$element[7]}<br>
                            <a href=\"edit.php?id={$element[0]}\">Edit...</a>
                            <a href=\"files.php?id={$element[0]}\">Files...</a></div></li>";
            }
            $result = preg_replace("/{ELEMENTS}/", $elements, $this->template);
            
            // Get info about user
            $result = preg_replace("/{IMAGE}/", $_SESSION['avatar'], $result);
            $result = preg_replace("/{NAME}/", $_SESSION['name'], $result);

            // Get navigation
            $total = ceil($sql->getPostsCount() / LIMIT);
            $last = (($total - 1) * LIMIT >= 0) ? (($total - 1) * LIMIT) : 0;
            $prevOffset = ($offset - LIMIT >= 0) ? $offset - LIMIT : $offset;
            $nextOffset = ($offset + LIMIT <= $last) ? $offset + LIMIT : $offset;

            $navigation = "<a href=\"view.php?offset=0\">First</a> <a href=\"view.php?offset=$prevOffset\">Prev</a> {OTHER}
                            <a href=\"view.php?offset=$nextOffset\">Next</a> <a href=\"view.php?offset=$last\">Last</a>";
            
            // Calculation a page numbers
            $pages = '';
            
            //If total page count > limit count
            if ($total >= LIMIT) {
                if ($offset / LIMIT + LIMIT - 1 <= $total) {
                    for ($i = 1 - intdiv(LIMIT, 2); $i <= LIMIT - intdiv(LIMIT, 2); $i++) {
                        $number = $offset / LIMIT + $i;
                        
                        // Other pages
                        if ($number > 0) {
                            $position = ($number - 1) * LIMIT;
                            
                            if ($position == $offset) {
                                $pages .= "<a href=\"view.php?offset=$position\" style=\"color: red\">$number</a> ";
                            }
                            else {
                                $pages .= "<a href=\"view.php?offset=$position\">$number</a> ";
                            }
                        }
                        // First 5 pages
                        else {
                            $current = 0;
                            for ($i = 1; $i <= LIMIT; $i++) {
                                $number = $current + $i;
                                $position = ($number - 1) * LIMIT;
    
                                if ($position == $offset) {
                                    $pages .= "<a href=\"view.php?offset=$position\" style=\"color: red\">$number</a> ";
                                }
                                else {
                                    $pages .= "<a href=\"view.php?offset=$position\">$number</a> ";
                                }
                            }
                        }
                    }
                }
                // Last 5 pages
                else {
                    $current = $total - LIMIT;
                    for ($i = 1; $i <= LIMIT; $i++) {
                        $number = $current + $i;
                        $position = ($number - 1) * LIMIT;
    
                        if ($position == $offset) {
                            $pages .= "<a href=\"view.php?offset=$position\" style=\"color: red\">$number</a> ";
                        }
                        else {
                            $pages .= "<a href=\"view.php?offset=$position\">$number</a> ";
                        }
                    }
                }
            }
            else {
                // If total page count <= limit count
                $current = 0;
                for ($i = 1; $i <= $total; $i++) {
                    $number = $current + $i;
                    $position = ($number - 1) * LIMIT;

                    if ($position == $offset) {
                        $pages .= "<a href=\"view.php?offset=$position\" style=\"color: red\">$number</a> ";
                    }
                    else {
                        $pages .= "<a href=\"view.php?offset=$position\">$number</a> ";
                    }
                }
            }
            
            // Get final navigation string
            $navigation = preg_replace("/{OTHER}/", $pages, $navigation);
            $result = preg_replace("/{NAVIGATION}/", $navigation, $result);

            return $result;
        }

        /**
         * Get login / register page
         * 
         * @param string $action - log or reg form
         * @param string $message - error message
         * 
         * @return string final page
         */
        public function getLogOrRegPage(string $action, string $message = ''): string {
            // Login page
            if ($action === 'log') {
                $form = file_get_contents('templates/log.tpl');
            }
            // Register page
            elseif ($action === 'reg') {
                $form = file_get_contents('templates/reg.tpl');
            }
            
            $result = preg_replace("/{FORM}/", $form, $this->template);
            if (!empty($message)) {
                $result = preg_replace("/{MESSAGE}/", $message . '<br>', $result);
            }
            else {
                $result = preg_replace("/{MESSAGE}/", $message, $result);
            }

            return $result;
        }

        /**
         * Get reset page
         *
         * @param string $message - error message
         * 
         * @return string final page
         */
        public function getResetPage(string $message = ''): string {            
            if (empty($message)) {
                $result = preg_replace("/{MESSAGE}/", $message, $this->template);
            }
            else {
                $result = preg_replace("/{MESSAGE}/", '<br>' . $message . '<br>', $this->template);
            }

            return $result;
        }

        /**
         * Get edit or create page
         *
         * @param string $id - post id
         * @param bool $flag - create(0) or edit(1) page
         * 
         * @return string final page
         */
        public function getEditOrCreatePage(int $id = 0, bool $flag = false): string {
            $sql = new DataBase();
            $categoriesList = $sql->getCategories();
            $elements = '';
            $result = '';
            
            // If we want to load and selected category
            if ($categoriesList) {
                if ($flag) {
                    $post = $sql->getPost($id);

                    if ($post) {
                        // Get final categories list
                        foreach($categoriesList as $element) {
                            if ($element[0] === $post[6]) {
                                $elements .= "<option selected>{$element[0]}. {$element[1]}</option>";
                            }
                            else {
                                $elements .= "<option>{$element[0]}. {$element[1]}</option>";
                            }
                        }

                        // Get final information about specifical post
                        $categoty = $categoriesList[$post[6] - 1][1];
                        $result = preg_replace("/{TITLE}/", $post[1], $this->template);
                        $result = preg_replace("/{DESCRIPTION}/", $post[2], $result);
                        $result = preg_replace("/{AUTHOR}/", $post[5], $result);
                        $result = preg_replace("/{OPTIONS}/", $elements, $result);
                        $result = preg_replace("/{CREATED_AT}/", $post[3], $result);
                        $result = preg_replace("/{UPDATED_AT}/", $post[4], $result);
                        $result = preg_replace("/{CATEGORY}/", $categoty, $result);
                    }
                }
                // Another situation
                else {
                    // Get final categories list
                    foreach($categoriesList as $element)  {
                        $elements .= "<option>{$element[0]}. {$element[1]}</option>";
                    }
    
                    $result = preg_replace("/{OPTIONS}/", $elements, $this->template);
                }
            }

            return $result;
        }

        /**
         * Get files page
         *
         * @param int $id - post's id
         * @param string $message - result message
         * 
         * @return string final page
         */
        public function getFilesPage(int $id, string $message): string { 
            $files = new Files($id);
            $temp = $files->getFiles();

            $result = preg_replace("/{ID}/", $id, $this->template);
            if ($message === '') {
                $result = preg_replace("/{MESSAGE}/", $message, $result);
            }
            else {
                $result = preg_replace("/{MESSAGE}/", $message . '<br><br>', $result);
            }
            $result = preg_replace("/{FILES}/", $temp, $result);

            return $result;
        }
    }

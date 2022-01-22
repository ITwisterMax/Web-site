<?php 
    /**
     * Class for working with files
     *
     * @author Maksim Mikhalkov
     * @version 1.0
     */
	class Files {
        /**
         * @var string|bool path to the directory
         */
        private string|bool $dir;

        /**
         * @var int post's id
         */
        private int $id;

        /**
         * Create a path to the directory
         */
		public function __construct(int $id) {
            $dir = "./files/$id";

            if (is_dir($dir)) {
                $this->dir = $dir;
            }
            else {
                $this->dir = false;
                $this->id = $id;
            }
		}

        /**
         * Destruct a path to the directory
         */
		public function __destruct() {
            unset($this->dir);
		}
        
        /**
         * Get files list
         *
         * @return string final files list
         */
        public function getFiles(): string {
            if ($this->dir) {
                // открываем директорию
                $openDir = opendir($this->dir);

                $files = array();
                // читаем директорию
                while (false !== ($file = readdir($openDir)))
                {
                    if ($file != '.' && $file != '..') {
                        $files[] = $file;
                    }
                }
                
                // закрываем директорию
                closedir($openDir);

                sort($files, SORT_FLAG_CASE | SORT_NATURAL);

                $result = '';
                foreach ($files as $key => $value) {
                    $result .= "<img src=\"./images/file.png\" class=\"data\">
                            <a href=\"{$this->dir}/{$files[$key]}\" download>{$files[$key]}</a><br>";
                }

                return $result;
            }
            else {
                return 'Files not found!';
            }
        }

        /**
         * Get files page
         * 
         * @return string result message
         */
        public function loadFile(): string {
                if (!$this->dir) {
                    $this->dir = "./files/{$this->id}";
                    mkdir($this->dir);
                }

                $file = $this->dir . '/' . $_FILES['userfile']['name'];
                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $file)) {
                    return '<font color="green">The file was uploaded successfully!</font>';
                } 
                else {
                    return '<font color="red">Error! The file was not uploaded...</font>';
                }
        }

        /**
         * Delete a specifical directory
         * 
         * @param string $dir - specifical directory
         */
        private function delete(string $dir) {
            $curr = glob($dir . '/*');
            if (count($curr) > 0) {
                foreach ($curr as $item) {
                    if (is_dir($item)) {
                        delete_dir($item);
                        rmdir($item);
                    }
                    else {
                        unlink($item);
                    }
                }
            }
        }

        /**
         * Delete specifical directory
         */
        public function deleteDir() { 
            $this->delete($this->dir);
            rmdir($this->dir);
        }
	}

<?php 
    /**
     * Class for working with errors
     *
     * @author Maksim Mikhalkov
     * @version 1.0
     */
	class Errors {
        /**
         * Template errors
         * 
         * @param string $mainTemplateFilename - path to file
         */
        public function templateError(string $mainTemplateFilename) {
            $date = date('Y-m-d H:i:s');
            file_put_contents(
                'logs/errors.txt', 
                "{$date} | Template error (File \"{$mainTemplateFilename}\" not found)\n",
                FILE_APPEND
            );
            die("Template error (File \"{$mainTemplateFilename}\" not found)\n");
        }

        /**
         * DB errors
         * 
         * @param string $error - error text
         */
        public function dbError(string $error) {
            $date = date('Y-m-d H:i:s');
            file_put_contents(
                'logs/errors.txt', 
                "{$date} | Connection error ({$error})\n",
                FILE_APPEND
            );
            die("Connection error ({$error})");
        }

        /**
         * Request errors
         * 
         * @param string $error - error text
         */
        public function requestError(string $error) {
            $date = date('Y-m-d H:i:s');
            file_put_contents(
                'logs/errors.txt', 
                "{$date} | Error while executing request ({$error})\n",
                FILE_APPEND
            );
            die("Error while executing request ({$error})");
        }
	}

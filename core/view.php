<?php

namespace Core;
require "helper.php";

class View
{
    private function __construct(private readonly string $file, private array $data = [])
    {
    }

    /**
     * @param string $name - name of the view
     * @param array $data - optional data to pass to the view
     * @param bool $systemView - false by default
     * @return View
     */
    public static function make(string $name, array $data = [], bool $systemView = false): View
    {
        $viewDirectory = !$systemView ? App::$VIEW_DIRECTORY : App::$SYSTEM_VIEW_DIRECTORY;

        /**
         * Check if defined views directory exists
         */
        if (!is_dir($viewDirectory)) {
            dd("Views directory doesn't exist at the following location:", $viewDirectory);
        }

        $file = $viewDirectory . $name . '.view.php';
        /**
         * Check if requested view file exists at base directory
         */
        if (!file_exists($file)) {
            dd("Requested view doesn't exist", $name);
        }

        return new View($file, $data);
    }

    /**
     * Show a view
     * @return void
     */
    public function show(): void
    {
        (function () {
            if (!empty($this->data)) {
                extract($this->data);
            }
            include $this->file;
        })();
    }
}
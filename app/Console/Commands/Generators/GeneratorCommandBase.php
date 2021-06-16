<?php

namespace App\Console\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

abstract class GeneratorCommandBase extends Command
{
    /** @var Filesystem */
    protected $files;

    protected $type = '';

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $params = $this->getTargetName();
        if (empty($params['name'])){
            dump("name not found");
            return false;
        }
        $name = $params['name'];
        if (!empty($params['folder'])){
            $folder = $params['folder'];
        }else{
            $folder = '';
        }

        if (!empty($params['database'])){
            $database = $params['database'];
        }else{
            $database = '';
        }

        $params['name'] = $this->parseName($name, $folder, $database);


        $this->generate($params);

        return true;
    }

    /**
     * Get the stub file for the generator.
     *
     * @param string $name
     *
     * @return bool
     */
    abstract protected function generate($name);

    /**
     * Determine if the class already exists.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function alreadyExists($path)
    {
        return $this->files->exists($path);
    }

    /**
     * @param $stub
     * @param $key
     * @param $value
     *
     * @return $this
     */
    protected function replaceTemplateVariable(&$stub, $key, $value)
    {
        $stub = str_replace('%%'.$key.'%%', $value, $stub);

        return $this;
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @param string $folder
     *
     * @return string
     */
    protected function getPath($name, $folder)
    {

        $name = str_replace($this->laravel->getNamespace(), '', $name);

        if (!empty($folder)){
            $arrName = explode('\\', $name);
            if (!empty($arrName)){
                $endArr = $arrName[count($arrName) - 1];
                unset($arrName[count($arrName) - 1]);
                array_push($arrName, $folder, $endArr);

                return $this->laravel['path'].'/'.str_replace('\\', '/', implode('\\', $arrName)).'.php';
            }
        }

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Parse the name and format according to the root namespace.
     *
     * @param string $name
     * @param string $folder
     * @param string $database
     *
     * @return string
     */
    protected function parseName($name, $folder, $database)
    {
        $rootNamespace = $this->laravel->getNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        if (Str::contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
        }

        return $this->parseName($this->getDefaultNamespace(trim($rootNamespace, '\\'), $folder, $database).'\\'.$name, '', '');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @param string $folder
     * @param string $database
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace, $folder, $database)
    {
        if (!empty($folder) && !empty($database)){
            return $rootNamespace.$database.$folder;
        }
        if (!empty($folder)){
            return $rootNamespace.$folder;
        }

        return $rootNamespace;
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     *
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * Get the full namespace name for a given class.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getClassName($name)
    {
        return str_replace($this->getNamespace($name).'\\', '', $name);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getTargetName()
    {
        $name = $this->argument('name');
        $folder = $this->argument('folder');
        $database = $this->argument('database');

        $params = [
            'name' => $name,
            'folder' => $folder,
            'database' => $database
        ];

        return $params;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['folder', InputArgument::OPTIONAL, 'The name of the folder'],
            ['database', InputArgument::OPTIONAL, 'The name of the database'],
        ];
    }
}

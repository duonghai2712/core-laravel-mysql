<?php

namespace App\Console\Commands\Generators;

class ServiceMakeCommand extends GeneratorCommandBase
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';
    protected $nameSpace = 'App\Services';

    protected function generate($params)
    {
        if (empty($params['name'])){
            dump("name not found");
            return false;
        }

        $name = $params['name'];
        $folder = $params['folder'];
        $database = $params['database'];

        if (!$this->generateInterface($name, $folder, $database)) {
            return false;
        }
        if (!$this->generateService($name, $folder, $database)) {
            return false;
        }
        if (!$this->generateUnitTest($name, $folder, $database)) {
            return false;
        }

        return $this->bindInterface($name, $folder, $database);
    }

    /**
     * @param string $name
     * @param string $folder
     * @param string $database
     * @return bool
     */
    protected function generateInterface($name, $folder, $database)
    {
        $interfacePath = $this->getInterfacePath($name, $folder, $database);
        if ($this->alreadyExists($interfacePath)) {
            $this->error($name.' interface already exists.');

            return false;
        }

        $nameSpace = $this->getNameSpaceServiceInterFace($folder, $database);

        $this->makeDirectory($interfacePath);

        $className = $this->getClassName($name);

        $interfaceStub = $this->files->get($this->getStubForInterface());
        $this->replaceTemplateVariable($interfaceStub, 'NAMESPACE', $nameSpace);
        $this->replaceTemplateVariable($interfaceStub, 'CLASS', $className);
        $this->files->put($interfacePath, $interfaceStub);

        return true;
    }

    protected function generateService($name, $folder, $database)
    {
        $path = $this->getServicePath($name, $folder, $database);
        if ($this->alreadyExists($path)) {
            $this->error($name.' already exists.');

            return false;
        }

        $nameSpace = $this->getNameSpaceService($folder, $database);
        $newPath = $this->getNameSpaceServiceInterFace($folder, $database);

        $this->makeDirectory($path);
        $className = $this->getClassName($name);

        $stub = $this->files->get($this->getStub());
        $this->replaceTemplateVariable($stub, 'NAMESPACE', $nameSpace);
        $this->replaceTemplateVariable($stub, 'PATH', $newPath);
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     * @param string $folder
     * @param string $database
     *
     * @return bool
     */
    protected function generateUnitTest($name, $folder, $database)
    {
        $path = $this->getUnitTestPath($name, $folder, $database);
        if ($this->alreadyExists($path)) {
            $this->error($name.' already exists.');

            return false;
        }

        $nameSpace = $this->getNameSpaceService($folder, $database);
        $newPath = $this->getNameSpaceServiceInterFace($folder, $database);

        $this->makeDirectory($path);

        $className = $this->getClassName($name);

        $stub = $this->files->get($this->getStubForUnitTest());
        $this->replaceTemplateVariable($stub, 'NAMESPACE', $nameSpace);
        $this->replaceTemplateVariable($stub, 'PATH', $newPath);
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     * @param string $folder
     * @param string $database
     *
     * @return bool
     */
    protected function bindInterface($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        $bindService = $this->files->get($this->getBindServiceProviderPath());
        $key = '/* NEW BINDING */';
        if (!empty($folder) && !empty($database)){
            $bind = '$this->app->singleton('.PHP_EOL."            \\App\\Services\\".$database."\\".$folder."\\".$className."Interface::class,".PHP_EOL."            \\App\\Services\\".$database."\\".$folder."\\Production\\".$className."::class".PHP_EOL.'        );'.PHP_EOL.PHP_EOL.'        '.$key;
        }else if(!empty($folder)){
            $bind = '$this->app->singleton('.PHP_EOL."            \\App\\Services\\".$folder."\\".$className."Interface::class,".PHP_EOL."            \\App\\Services\\".$folder."\\Production\\".$className."::class".PHP_EOL.'        );'.PHP_EOL.PHP_EOL.'        '.$key;
        }else{
            $bind = '$this->app->singleton('.PHP_EOL.'            \\App\\Services\\'.$className.'Interface::class,'.PHP_EOL.'            \\App\\Services\\Production\\'.$className.'::class'.PHP_EOL.'        );'.PHP_EOL.PHP_EOL.'        '.$key;
        }
        $bindService = str_replace($key, $bind, $bindService);
        $this->files->put($this->getBindServiceProviderPath(), $bindService);

        return true;
    }
    protected function getNameSpaceServiceInterFace($folder, $database)
    {
        $nameSpace = $this->nameSpace;
        if (!empty($folder) && !empty($database)){
            $nameSpace = $nameSpace . '\\' .$database . '\\'. $folder;
            return $nameSpace;
        }

        if (!empty($folder)){
            $nameSpace = $nameSpace . '\\'. $folder;
            return $nameSpace;
        }

        return $nameSpace;
    }

    protected function getNameSpaceService($folder, $database)
    {
        $nameSpace = $this->nameSpace;
        if (!empty($folder) && !empty($database)){
            $nameSpace = $nameSpace . '\\' .$database . '\\'. $folder . '\Production';
            return $nameSpace;
        }

        if (!empty($folder)){
            $nameSpace = $nameSpace . '\\'. $folder . '\Production';
            return $nameSpace;
        }

        return $nameSpace . '\Production';
    }


    protected function getInterfacePath($name, $folder, $database)
    {
        $className = $this->getClassName($name);
        if (!empty($folder) && !empty($database)){
            return $this->laravel['path'].'/Services/'.$database.'/'.$folder.'/'.$className.'Interface.php';
        }

        if (!empty($folder)){
            return $this->laravel['path'].'/Services/'.$folder.'/'.$className.'Interface.php';
        }


        return $this->laravel['path'].'/Services/'.$className.'Interface.php';
    }

    protected function getServicePath($name, $folder, $database)
    {
        $className = $this->getClassName($name);
        if (!empty($folder) && !empty($database)){
            return $this->laravel['path'].'/Services/'.$database.'/'.$folder.'/Production/'.$className.'.php';
        }

        if (!empty($folder)){
            return $this->laravel['path'].'/Services/'.$folder.'/Production/'.$className.'.php';
        }


        return $this->laravel['path'].'/Services/Production/'.$className.'.php';
    }

    protected function getPath($name, $folder)
    {
        $className = $this->getClassName($name);

        if (!empty($folder)){
            return $this->laravel['path'].'/Services/Production/'.$folder.'/'.$className.'.php';
        }

        return $this->laravel['path'].'/Services/Production/'.$className.'.php';
    }

    protected function getStub()
    {
        return __DIR__.'/stubs/service.stub';
    }

    protected function getStubForInterface()
    {
        return __DIR__.'/stubs/service-interface.stub';
    }

    protected function getUnitTestPath($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        if (!empty($folder) && !empty($database)){
            return $this->laravel['path'].'/../tests/Services/'.$database.'/'.$folder.'/'.$className.'Test.php';
        }

        if (!empty($folder)){
            return $this->laravel['path'].'/../tests/Services/'.$folder.'/'.$className.'Test.php';
        }


        return $this->laravel['path'].'/../tests/Services/'.$className.'Test.php';
    }

    protected function getStubForUnitTest()
    {
        return __DIR__.'/stubs/service-unittest.stub';
    }

    protected function getTableName($name)
    {
        $className = $this->getClassName($name);

        return $className;
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
            return $rootNamespace.'\Services' . '\\' . $database . '\\' . $folder;
        }

        if (!empty($folder)){
            return $rootNamespace.'\Services'.$folder;
        }

        return $rootNamespace.'\Services';
    }


    protected function getBindServiceProviderPath()
    {
        return $this->laravel['path'].'/Providers/ServiceBindServiceProvider.php';
    }
}

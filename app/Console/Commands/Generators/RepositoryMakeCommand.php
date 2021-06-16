<?php

namespace App\Console\Commands\Generators;

class RepositoryMakeCommand extends GeneratorCommandBase
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';
    protected $nameSpace = 'App\Repositories';

    protected function generate($params)
    {
        if (empty($params['name'])){
            dump("name not found");
            return false;
        }

        $name = $params['name'];
        $folder = $params['folder'];
        $database = $params['database'];

        $this->generateInterface($name, $folder, $database);
        $this->generateRepository($name, $folder, $database);
        $this->generateUnitTest($name, $folder, $database);

        return $this->bindInterface($name, $folder, $database);
    }

    /**
     * @param string $name
     * @param string $folder
     * @param string $database
     *
     * @return bool
     */
    protected function generateInterface($name, $folder, $database)
    {
        $interfacePath = $this->getInterfacePath($name, $folder, $database);
        if ($this->alreadyExists($interfacePath)) {
            $this->error($name.' interface already exists.');

            return false;
        }

        $this->makeDirectory($interfacePath);
        $nameSpace = $this->getNameSpaceRepositoryInterFace($folder, $database);

        $className = $this->getClassName($name);

        $interfaceStub = $this->files->get($this->getStubForInterface($name, $folder, $database));
        $this->replaceTemplateVariable($interfaceStub, 'NAMESPACE', $nameSpace);
        $this->replaceTemplateVariable($interfaceStub, 'CLASS', $className);
        $this->replaceTemplateVariable($interfaceStub, 'MODEL', $this->getModel($className, $folder, $database));
        $this->files->put($interfacePath, $interfaceStub);

        return true;
    }

    /**
     * @param string $name
     * @param string $folder
     * @param string $database
     *
     * @return bool
     */
    protected function generateRepository($name, $folder, $database)
    {
        $repositoryPath = $this->getRepositoryPath($name, $folder, $database);
        if ($this->alreadyExists($repositoryPath)) {
            $this->error($name.' already exists.');

            return false;
        }

        $nameSpace = $this->getNameSpaceRepository($folder, $database);
        $newModel = $this->getNameNewModel($name, $folder, $database);
        $path = $this->getNameSpaceRepositoryInterFace($folder, $database);

        $this->makeDirectory($repositoryPath);
        $className = $this->getClassName($name);

        $repositoryStab = $this->files->get($this->getStubForRepository($name, $folder, $database));
        $this->replaceTemplateVariable($repositoryStab, 'NAMESPACE', $nameSpace);
        $this->replaceTemplateVariable($repositoryStab, 'PATH', $path);
        $this->replaceTemplateVariable($repositoryStab, 'NEWMODEL', $newModel);
        $this->replaceTemplateVariable($repositoryStab, 'CLASS', $className);
        $this->replaceTemplateVariable($repositoryStab, 'MODEL',
            str_replace('Repository', '', $className));
        $this->files->put($repositoryPath, $repositoryStab);

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
            $bind = '$this->app->singleton('.PHP_EOL."            \\App\\Repositories\\".$database."\\".$folder."\\".$className."Interface::class,".PHP_EOL."            \\App\\Repositories\\".$database."\\".$folder."\\Eloquent\\".$className."::class".PHP_EOL.'        );'.PHP_EOL.PHP_EOL.'        '.$key;
        }else if(!empty($folder)){
            $bind = '$this->app->singleton('.PHP_EOL."            \\App\\Repositories\\".$folder."\\".$className."Interface::class,".PHP_EOL."            \\App\\Repositories\\".$folder."\\Eloquent\\".$className."::class".PHP_EOL.'        );'.PHP_EOL.PHP_EOL.'        '.$key;
        }else{
            $bind = '$this->app->singleton('.PHP_EOL."            \\App\\Repositories\\".$className."Interface::class,".PHP_EOL."            \\App\\Repositories\\Eloquent\\".$className."::class".PHP_EOL.'        );'.PHP_EOL.PHP_EOL.'        '.$key;
        }

        $bindService = str_replace($key, $bind, $bindService);
        $this->files->put($this->getBindServiceProviderPath(), $bindService);

        return true;
    }

    protected function getInterfacePath($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        if (!empty($folder) && !empty($database)){
            return $this->laravel['path'].'/Repositories/'.$database.'/'.$folder.'/'.$className.'Interface.php';
        }

        if (!empty($folder)){
            return $this->laravel['path'].'/Repositories/'.'/'.$folder.'/'.$className.'Interface.php';
        }

        return $this->laravel['path'].'/Repositories/'.$className.'Interface.php';
    }

    protected function getRepositoryPath($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        if (!empty($folder) && !empty($database)){
            return $this->laravel['path'].'/Repositories/'.$database.'/'.$folder.'/Eloquent/'.$className.'.php';
        }

        if (!empty($folder)){
            return $this->laravel['path'].'/Repositories/'.$folder.'/Eloquent/'.$className.'.php';
        }

        return $this->laravel['path'].'/Repositories/Eloquent/'.$className.'.php';
    }

    protected function getStubForInterface($name, $folder, $database)
    {
        $className = $this->getClassName($name);
        $model = $this->getModel($className, $folder, $database);
        $instance = new $model();

        return is_array($instance->primaryKey) ? __DIR__.'/stubs/composite-key-model-repository-interface.stub' : __DIR__.'/stubs/single-key-model-repository-interface.stub';
    }

    protected function getStubForRepository($name, $folder, $database)
    {
        $className = $this->getClassName($name);
        $model = $this->getModel($className, $folder, $database);
        $instance = new $model();

        return is_array($instance->primaryKey) ? __DIR__.'/stubs/composite-key-model-repository.stub' : __DIR__.'/stubs/single-key-model-repository.stub';
    }

    protected function getNameSpaceRepositoryInterFace($folder, $database)
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

    protected function getNameSpaceRepository($folder, $database)
    {
        $nameSpace = $this->nameSpace;
        if (!empty($folder) && !empty($database)){
            $nameSpace = $nameSpace . '\\' .$database . '\\'. $folder . '\Eloquent';
            return $nameSpace;
        }

        if (!empty($folder)){
            $nameSpace = $nameSpace . '\\'. $folder . '\Eloquent';
            return $nameSpace;
        }

        return $nameSpace . '\Eloquent';
    }

    protected function getNameNewModel($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        $newModel = str_replace('Repository', '', $className);

        if (!empty($folder) && !empty($database)){
            $newModel = $database . '\\'. $folder . '\\' . $newModel;
            return $newModel;
        }

        if (!empty($folder)){
            $newModel = $folder . '\\' . $newModel;
            return $newModel;
        }

        return $newModel;

    }

    protected function getBindServiceProviderPath()
    {
        return $this->laravel['path'].'/Providers/RepositoryBindServiceProvider.php';
    }

    protected function generateUnitTest($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        $path = $this->getUnitTestPath($name, $folder, $database);
        if ($this->alreadyExists($path)) {
            $this->error($path.' already exists.');

            return false;
        }

        $nameSpace = $this->getNameSpaceRepository($folder, $database);
        $newModel = $this->getNameNewModel($name, $folder, $database);
        $newPath = $this->getNameSpaceRepositoryInterFace($folder, $database);

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStubForUnitTest());

        $model = $this->getModelClass($className);
        $this->replaceTemplateVariable($stub, 'NAMESPACE', $nameSpace);
        $this->replaceTemplateVariable($stub, 'PATH', $newPath);
        $this->replaceTemplateVariable($stub, 'NEWMODEL', $newModel);
        $this->replaceTemplateVariable($stub, 'MODEL', $model);
        $this->replaceTemplateVariable($stub, 'model', strtolower(substr($model, 0, 1)).substr($model, 1));
        $this->replaceTemplateVariable($stub, 'models',
            \StringHelper::pluralize(strtolower(substr($model, 0, 1)).substr($model, 1)));

        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     *  @param string $folder
     * @param string $database
     * @return string
     */
    protected function getUnitTestPath($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        if (!empty($folder) && !empty($database)){
            return $this->laravel['path'].'/../tests/Repositories/'.$database.'/'.$folder.'/'.$className.'Test.php';
        }

        if (!empty($folder)){
            return $this->laravel['path'].'/../tests/Repositories/'.$folder.'/'.$className.'Test.php';
        }

        return $this->laravel['path'].'/../tests/Repositories/'.$className.'Test.php';
    }

    /**
     * @return string
     */
    protected function getStubForUnitTest()
    {
        return __DIR__.'/stubs/single-key-model-repository-unittest.stub';
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
            return $rootNamespace.'\Repositories' . '\\' . $database . '\\' . $folder;
        }

        if (!empty($folder)){
            return $rootNamespace.'\Repositories'.'\\'.$folder;
        }

        return $rootNamespace.'\Repositories';
    }

    /**
     * @param string $className
     * @param string $folder
     * @param string $database
     *
     * @return \App\Models\Base
     */
    protected function getModel($className, $folder, $database)
    {
        $modelName = str_replace('Repository', '', $className);

        if (!empty($folder) && !empty($database)){
            return '\\App\\Models\\'.$database.'\\'.$folder.'\\'.$modelName;
        }

        if (!empty($folder)){
            return '\\App\\Models\\'.$folder.'\\'.$modelName;
        }

        return '\\App\\Models\\'.$modelName;
    }

    /**
     * @param string $className
     *
     * @return \App\Models\Base
     */
    protected function getModelClass($className)
    {
        $modelName = str_replace('Repository', '', $className);

        return $modelName;
    }
}

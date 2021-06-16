<?php

namespace App\Console\Commands\Generators;

use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends GeneratorCommandBase
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:new-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new  model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';
    protected $nameSpaceObserver = 'App\Observers';
    protected $nameSpacePresenter = 'App\Presenters';
    protected $nameSpaceModelTest= 'tests\models';

    protected function generate($params)
    {
        if (empty($params['name'])){
            dump("name not found");
            return false;
        }

        $name = $params['name'];
        $folder = $params['folder'];
        $database = $params['database'];
        $this->generateModel($name, $folder, $database);
        $this->generateObserver($name, $folder, $database);
        $this->generatePresenter($name, $folder, $database);
        $this->addModelFactory($name, $folder, $database);
        $this->generateUnitTest($name, $folder, $database);
    }

    /**
     * @param  string $name
     * @param string $folder
     * @param string $database
     * @return bool
     */
    protected function generateModel($name, $folder, $database)
    {
        $path = $this->getGenerateModelPath($name, $folder, $database);
        if ($this->alreadyExists($path)) {
            $this->error($name.' already exists.');

            return false;
        }

        $this->makeDirectory($path);
        $className = $this->getClassName($name);
        $tableName = $this->getTableName($name);
        $nameSpace = $this->getNameSpaceModel($name);
        $preAndOb = $this->getPathObserversAndPresenters($name, $folder, $database);

        $stub = $this->files->get($this->getStub());
        $this->replaceTemplateVariable($stub, 'NAMESPACE', $nameSpace);
        $this->replaceTemplateVariable($stub, 'PREANDOB', $preAndOb);
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->replaceTemplateVariable($stub, 'TABLE', $tableName);

        $columns = $this->getFillableColumns($tableName);
        $fillables = count($columns) > 0 ? "'".implode("',".PHP_EOL."        '", $columns)."'," : '';
        $this->replaceTemplateVariable($stub, 'FILLABLES', $fillables);

        $api = count($columns) > 0 ? implode(','.PHP_EOL.'            ', array_map(function($column) {
                return "'".$column."'".' => $this->'.$column;
            }, $columns)).',' : '';
        $this->replaceTemplateVariable($stub, 'API', $api);

        $relations = $this->detectRelations($name);
        $this->replaceTemplateVariable($stub, 'RELATIONS', $relations);

        $columns = $this->getDateTimeColumns($tableName);
        $datetimes = count($columns) > 0 ? "'".implode("','", $columns)."'" : '';
        $this->replaceTemplateVariable($stub, 'DATETIMES', $datetimes);

        $hasSoftDelete = $this->hasSoftDeleteColumn($tableName);
        $this->replaceTemplateVariable($stub, 'SOFT_DELETE_CLASS_USE',
            $hasSoftDelete ? 'use Illuminate\Database\Eloquent\SoftDeletes;'.PHP_EOL : PHP_EOL);
        $this->replaceTemplateVariable($stub, 'SOFT_DELETE_USE', $hasSoftDelete ? 'use SoftDeletes;'.PHP_EOL : PHP_EOL);

        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param  string $name
     * @param  string $folder
     * @return string
     */
    protected function getPath($name, $folder)
    {
        $className = $this->getClassName($name);

        if (!empty($folder)){
            return $this->laravel['path'].'/Models/'.$folder.'/'.$className.'.php';
        }

        return $this->laravel['path'].'/Models/'.$className.'.php';
    }

    protected function getGenerateModelPath($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        if (!empty($folder) && !empty($database)){
            return $this->laravel['path'].'/Models/'.$database.'/'.$folder.'/'.$className.'.php';
        }

        if (!empty($folder)){
            return $this->laravel['path'].'/Models/'.$folder.'/'.$className.'.php';
        }

        return $this->laravel['path'].'/Models/'.$className.'.php';
    }

    protected function getPathObserversAndPresenters($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        if (!empty($folder) && !empty($database)){
            $className = $database . '\\' . $folder . '\\' . $className;
        }else if (!empty($folder)){
            $className = $folder . '\\' . $className;
        }

        return $className;
    }


    /**
     * @param  string $name
     * @return string
     */
    protected function getNameSpaceModel($name)
    {
        $arrName = explode('\\', $name);
        array_pop($arrName);
        return implode('\\', $arrName);
    }

    /**
     * @param  string $folder
     * @param  string $database
     * @param  string $type
     * @return string
     */
    protected function getNameSpaceObserverAndPresenter($folder, $database, $type)
    {
        if ($type === 'OB'){
            $nameSpace = $this->nameSpaceObserver;
        }else if ($type === 'PRE'){
            $nameSpace = $this->nameSpacePresenter;
        }else{
            $nameSpace = $this->nameSpaceModelTest;
        }

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

    /**
     * @param  string $name
     * @param  string $folder
     * @param  string $database
     * @return string
     */
    protected function getModelTest($name, $folder, $database)
    {
        $nameModel = $this->getClassName($name);
        if (!empty($folder) && !empty($database)){
            $nameModel = $database . '\\'. $folder . '\\' . $nameModel;
            return $nameModel;
        }

        if (!empty($folder)){
            $nameModel = $folder . '\\' . $nameModel;
            return $nameModel;
        }

        return $nameModel;
    }


    /**
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/model.stub';
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
            return $rootNamespace.'\Models' . '\\' . $database . '\\' . $folder;
        }

        if (!empty($folder)){
            return $rootNamespace.'\Models'.'\\'.$folder;
        }

        return $rootNamespace.'\Models';
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function getModel($className)
    {
        return $className;
    }

    /**
     * @param  string $tableName
     * @return array
     */
    protected function getFillableColumns($tableName)
    {
        $hasDoctrine = interface_exists('Doctrine\DBAL\Driver');
        if (!$hasDoctrine) {
            return [];
        }
        $ret = [];
        $schema = \DB::getDoctrineSchemaManager();
        $columns = $schema->listTableColumns($tableName);
        if ($columns) {
            foreach ($columns as $column) {
                if ($column->getAutoincrement()) {
                    continue;
                }
                $columnName = $column->getName();
                if (!in_array($columnName, ['created_at', 'updated_at', 'deleted_at'])) {
                    $ret[] = $columnName;
                }
            }
        }

        return $ret;
    }

    /**
     * @param  string $tableName
     * @return array
     */
    protected function getDateTimeColumns($tableName)
    {
        $hasDoctrine = interface_exists('Doctrine\DBAL\Driver');
        if (!$hasDoctrine) {
            return [];
        }
        $ret = [];
        $schema = \DB::getDoctrineSchemaManager();
        $columns = $schema->listTableColumns($tableName);
        if ($columns) {
            foreach ($columns as $column) {
                if ($column->getType() != 'DateTime') {
                    continue;
                }
                $columnName = $column->getName();
                if (!in_array($columnName, ['created_at', 'updated_at'])) {
                    $ret[] = $columnName;
                }
            }
        }

        return $ret;
    }

    /**
     * @param  string $tableName
     * @return bool
     */
    protected function hasSoftDeleteColumn($tableName)
    {
        $columns = $this->getTableColumns($tableName, false);
        if ($columns) {
            foreach ($columns as $column) {
                $columnName = $column->getName();
                if ($columnName == 'deleted_at') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param  string $name
     * @param string $folder
     * @param string $database
     * @return bool
     */
    protected function generateObserver($name, $folder, $database)
    {
        $className = $this->getClassName($name);
        $tableName = $this->getTableName($name);

        $path = $this->getObserverPath($name, $folder, $database);
        if ($this->alreadyExists($path)) {
            $this->error($path.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $nameSpace = $this->getNameSpaceObserverAndPresenter($folder, $database, 'OB');

        $stub = $this->files->get($this->getStubForObserver());
        $this->replaceTemplateVariable($stub, 'NAMESPACE', $nameSpace);
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->replaceTemplateVariable($stub, 'TABLE', $tableName);

        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param  string $name
     * @param string $folder
     * @param string $database
     * @return bool
     */
    protected function generatePresenter($name, $folder, $database)
    {
        $className = $this->getClassName($name);
        $tableName = $this->getTableName($name);

        $path = $this->getPresenterPath($name, $folder, $database);
        if ($this->alreadyExists($path)) {
            $this->error($path.' already exists.');

            return false;
        }

        $nameSpace = $this->getNameSpaceObserverAndPresenter($folder, $database, 'PRE');
        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStubForPresenter());

        $columns = $this->getFillableColumns($tableName);
        $multilingualKeys = [];
        foreach ($columns as $column) {
            if (preg_match('/^(.*)_gb$/', $column, $matches)) {
                $multilingualKeys[] = $matches[1];
            }
        }
        $multilingualKeyString = count($multilingualKeys) > 0 ? "'".join("','",
                array_unique($multilingualKeys))."'" : '';
        $this->replaceTemplateVariable($stub, 'MULTILINGUAL_COLUMNS', $multilingualKeyString);

        $imageFields = [];
        foreach ($columns as $column) {
            if (preg_match('/^(.*_image)_id$/', $column, $matches)) {
                $imageFields[] = $matches[1];
            }
        }
        $imageFieldString = count($imageFields) > 0 ? "'".join("','", array_unique($imageFields))."'" : '';
        $this->replaceTemplateVariable($stub, 'NAMESPACE', $nameSpace);
        $this->replaceTemplateVariable($stub, 'IMAGE_COLUMNS', $imageFieldString);

        $this->replaceTemplateVariable($stub, 'CLASS', $className);

        $relation = $this->generateRelationFunctions($name);
        $this->replaceTemplateVariable($stub, 'RELATION_CLASS', $relation['class']);
        $this->replaceTemplateVariable($stub, 'RELATION_FUNCTION', $relation['functions']);

        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param  string $name
     * @param  string $folder
     * @param  string $database
     * @return string
     */
    protected function getObserverPath($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        if (!empty($folder) && !empty($database)){
            return $this->laravel['path'].'/Observers/'.$database.'/'.$folder.'/'.$className.'Observer.php';
        }

        if (!empty($folder)){
            return $this->laravel['path'].'/Observers/'.$folder.'/'.$className.'Observer.php';
        }

        return $this->laravel['path'].'/Observers/'.$className.'Observer.php';
    }

    /**
     * @param  string $name
     * @param  string $folder
     * @param  string $database
     * @return string
     */
    protected function getPresenterPath($name, $folder, $database)
    {
        $className = $this->getClassName($name);
        if (!empty($folder) && !empty($database)){
            return $this->laravel['path'].'/Presenters/'.$database.'/'.$folder.'/'.$className.'Presenter.php';
        }

        if (!empty($folder)){
            return $this->laravel['path'].'/Presenters/'.$folder.'/'.$className.'Presenter.php';
        }


        return $this->laravel['path'].'/Presenters/'.$className.'Presenter.php';
    }

    /**
     * @return string
     */
    protected function getStubForObserver()
    {
        return __DIR__.'/stubs/observer.stub';
    }

    /**
     * @return string
     */
    protected function getStubForPresenter()
    {
        return __DIR__.'/stubs/presenter.stub';
    }


    /**
     * @return array[ functions, class ]
     * */
    protected function generateRelationFunctions($name)
    {
        $tableName = $this->getTableName($name);
        $columns = $this->getTableColumns($tableName);

        $result['class'] = "";
        $result['functions'] = "";

        foreach ($columns as $column) {
            $columnName = $column->getName();
            if (preg_match('/^(.*_image)_id$/', $columnName, $matches)) {
                $relationName = \StringHelper::snake2Camel($matches[1]);

                $result['functions'] .= '/**' . PHP_EOL . '    ' .
                    '* @return \App\Models\Image' . PHP_EOL . '    ' .
                    '* */' . PHP_EOL . '    ' .
                    'public function ' . $relationName . '()' . PHP_EOL . '    ' .
                    '{' . PHP_EOL . '        ' .
                        'if( \CacheHelper::cacheRedisEnabled() ) {' . PHP_EOL . '            ' .
                            '$cacheKey = \CacheHelper::keyForModel(\'ImageModel\');' . PHP_EOL . '            ' .
                            '$cached = Redis::hget($cacheKey, $this->entity->' . $columnName . ');' . PHP_EOL . PHP_EOL . '            ' .

                            'if( $cached ) {' . PHP_EOL . '                ' .
                                '$image = new Image(json_decode($cached, true));' . PHP_EOL . '                ' .
                                '$image[\'id\'] = json_decode($cached, true)[\'id\'];' . PHP_EOL . '                ' .
                                'return $image;' . PHP_EOL . '            ' .
                            '} else {' . PHP_EOL . '                ' .
                                '$image = $this->entity->' . $relationName . ';' . PHP_EOL . '                ' .
                                'Redis::hsetnx($cacheKey, $this->entity->' . $columnName . ', $image);' . PHP_EOL . '                ' .
                                'return $image;' . PHP_EOL . '            ' .
                            '}' . PHP_EOL . '        ' .
                        '}' . PHP_EOL . PHP_EOL . '        ' .

                        '$image = $this->entity->' . $relationName . ';' . PHP_EOL . '        ' .
                        'return $image;' . PHP_EOL . '    ' .

                    '}' . PHP_EOL . PHP_EOL . '    ';

                $result['class'] .= 'use App\Models\Image;' . PHP_EOL;
            } elseif (preg_match('/^(.*)_id$/', $columnName, $matches)) {
                $relationName = \StringHelper::snake2Camel($matches[1]);
                $className = ucfirst($relationName);
                if (!$this->getPath($className, '')) {
                    continue;
                }

                $result['functions'] .= '/**' . PHP_EOL . '    ' .
                    '* @return \App\Models\\' . $className . PHP_EOL . '    ' .
                    '* */' . PHP_EOL . '    ' .
                    'public function ' . $relationName . '()' . PHP_EOL . '    ' .
                    '{' . PHP_EOL . '        ' .
                        'if( \CacheHelper::cacheRedisEnabled() ) {' . PHP_EOL . '            ' .
                            '$cacheKey = \CacheHelper::keyForModel(\'' . $className . 'Model\');' . PHP_EOL . '            ' .
                            '$cached = Redis::hget($cacheKey, $this->entity->' . $columnName . ');' . PHP_EOL . PHP_EOL . '            ' .

                            'if( $cached ) {' . PHP_EOL . '                ' .
                                '$' . $relationName . ' = new ' . $className . '(json_decode($cached, true));' . PHP_EOL . '                ' .
                                '$' . $relationName . '[\'id\'] = json_decode($cached, true)[\'id\'];' . PHP_EOL . '                ' .
                                'return $' . $relationName . ';' . PHP_EOL . '            ' .
                            '} else {' . PHP_EOL . '                ' .
                                '$' . $relationName . ' = $this->entity->' . $relationName . ';' . PHP_EOL . '                ' .
                                'Redis::hsetnx($cacheKey, $this->entity->' . $columnName . ', $' . $relationName . ');' . PHP_EOL . '                ' .
                                'return $' . $relationName . ';' . PHP_EOL . '            ' .
                            '}' . PHP_EOL . '        ' .
                        '}' . PHP_EOL . PHP_EOL . '        ' .

                        '$' . $relationName . ' = $this->entity->' . $relationName . ';' . PHP_EOL . '        ' .
                        'return $' . $relationName . ';' . PHP_EOL . '    ' .

                    '}' . PHP_EOL . PHP_EOL . '    ';

                $result['class'] .= 'use App\Models\\' . $className . ';' . PHP_EOL;
            }
        }

        return $result;
    }

    /**
     * @param  string $name
     * @param string $folder
     * @param string $database
     * @return bool
     */
    protected function addModelFactory($name, $folder, $database)
    {
        $className = $this->getClassName($name);
        $tableName = $this->getTableName($name);

        $columns = $this->getTableColumns($tableName);

        $factory = $this->files->get($this->getFactoryPath());
        $key = '/* NEW MODEL FACTORY */';

        if (!empty($folder) && !empty($database)){
            $data = '$factory->define(App\Models\\'.$database.'\\'.$folder.'\\'.$className.'::class, function (Faker\Generator $faker) {'.PHP_EOL.'    return ['.PHP_EOL;
        }elseif (!empty($folder)){
            $data = '$factory->define(App\Models\\'.$folder.'\\'.$className.'::class, function (Faker\Generator $faker) {'.PHP_EOL.'    return ['.PHP_EOL;
        }else{
            $data = '$factory->define(App\Models\\'.$className.'::class, function (Faker\Generator $faker) {'.PHP_EOL.'    return ['.PHP_EOL;
        }
        foreach ($columns as $column) {
            $data .= "        '".$column->getName()."' => '',".PHP_EOL;
        }
        $data .= '    ];'.PHP_EOL.'});'.PHP_EOL.PHP_EOL.$key;

        $factory = str_replace($key, $data, $factory);
        $this->files->put($this->getFactoryPath(), $factory);

        return true;
    }

    /**
     * @return string
     */
    protected function getFactoryPath()
    {
        return $this->laravel['path'].'/../database/factories/ModelFactory.php';
    }

    /**
     * @param  string $name
     * @param string $folder
     * @param string $database
     * @return bool
     */
    protected function generateUnitTest($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        $path = $this->getUnitTestPath($name, $folder, $database);
        if ($this->alreadyExists($path)) {
            $this->error($path.' already exists.');

            return false;
        }

        $this->makeDirectory($path);

        $nameSpace = $this->getNameSpaceObserverAndPresenter($folder, $database, '');
        $modelTest = $this->getModelTest($name, $folder, $database);


        $stub = $this->files->get($this->getStubForUnitTest());

        $this->replaceTemplateVariable($stub, 'NAMESPACE', $nameSpace);
        $this->replaceTemplateVariable($stub, 'MODELTEST', $modelTest);
        $this->replaceTemplateVariable($stub, 'CLASS', $className);
        $this->replaceTemplateVariable($stub, 'class', strtolower(substr($className, 0, 1)).substr($className, 1));

        $this->files->put($path, $stub);

        return true;
    }

    /**
     * @param string $name
     * @param  string $folder
     * @param  string $database
     *
     * @return string
     */
    protected function getUnitTestPath($name, $folder, $database)
    {
        $className = $this->getClassName($name);

        if (!empty($folder) && !empty($database)){
            return $this->laravel['path'].'/../tests/Models/'.$database.'/'.$folder.'/'.$className.'Test.php';
        }

        if (!empty($folder)){
            return $this->laravel['path'].'/../tests/Models/'.$folder.'/'.$className.'Test.php';
        }


        return $this->laravel['path'].'/../tests/Models/'.$className.'Test.php';
    }

    /**
     * @return string
     */
    protected function getStubForUnitTest()
    {
        return __DIR__.'/stubs/model-unittest.stub';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['table', '-t', InputOption::VALUE_OPTIONAL, 'Table Name', null],
        ];
    }

    protected function detectRelations($name)
    {
        $tableName = $this->getTableName($name);
        $columns = $this->getTableColumns($tableName);

        $relations = "";

        foreach ($columns as $column) {
            $columnName = $column->getName();
            if (preg_match('/^(.*_image)_id$/', $columnName, $matches)) {
                $relationName = \StringHelper::snake2Camel($matches[1]);
                $relations .= 'public function ' . $relationName . '()' . PHP_EOL . '    {' . PHP_EOL . '        return $this->hasOne(\App\Models\Image::class, \'id\', \'' . $columnName . '\');' . PHP_EOL . '    }' . PHP_EOL . PHP_EOL . '    ';
            } elseif (preg_match('/^(.*)_id$/', $columnName, $matches)) {
                $relationName = \StringHelper::snake2Camel($matches[1]);
                $className = ucfirst($relationName);
                if (!$this->getPath($className, '')) {
                    continue;
                }
                $relations .= 'public function ' . $relationName . '()' . PHP_EOL . '    {' . PHP_EOL . '        return $this->belongsTo(\App\Models\\' . $className . '::class, \'' . $columnName . '\', \'id\');' . PHP_EOL . '    }' . PHP_EOL . PHP_EOL . '    ';
            }
        }

        return $relations;
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function getTableName($name)
    {
        $options = $this->option();
        if (array_key_exists('name', $options)) {
            return $optionName = $this->option('name');
        }

        $className = $this->getClassName($name);

        $name = \StringHelper::pluralize(\StringHelper::camel2Snake($className));
        $columns = $this->getTableColumns($name);
        if (count($columns)) {
            return $name;
        }

        $name = \StringHelper::singularize(\StringHelper::camel2Snake($className));
        $columns = $this->getTableColumns($name);
        if (count($columns)) {
            return $name;
        }

        return \StringHelper::pluralize(\StringHelper::camel2Snake($className));
    }

    /**
     * @param string $tableName
     * @param bool   $removeDefaultColumn
     *
     * @return \Doctrine\DBAL\Schema\Column[]
     */
    protected function getTableColumns($tableName, $removeDefaultColumn = true)
    {
        $hasDoctrine = interface_exists('Doctrine\DBAL\Driver');
        if (!$hasDoctrine) {
            return [];
        }

        $platform = \DB::getDoctrineConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('json', 'string');

        $schema = \DB::getDoctrineSchemaManager();

        $columns = $schema->listTableColumns($tableName);

        if (!$removeDefaultColumn) {
            return $columns;
        }

        $ret = [];
        foreach ($columns as $column) {
            if (!in_array($column->getName(), ['created_at', 'updated_at', 'deleted_at'])) {
                $ret[] = $column;
            }
        }

        return $ret;
    }
}

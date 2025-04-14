<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MakeSelfModel extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:self-model';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create self define Model.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$name = $this->argument('name');

		$dir = app_path().'/models';
		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}

		$filepath = $dir.'/'.$name.'.php';
    
		if (file_exists($filepath)) {
			return $this->error('Model already exists!');
		}

		$stub = $this->getModelStub();
        $stub = str_replace('{{name}}', $name, $stub);
		$stub = str_replace('{{column}}', strtolower($name), $stub);
        $stub = str_replace('{{table}}', strtolower($name).'s', $stub);


		file_put_contents($filepath, $stub);
		$this->info('Model created successfully at ' . $name);
	}

	public function getModelStub()
    {
        return '<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

class {{name}} extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = \'{{table}}\';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = \'{{column}}_id\';

}';
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
            array('name', InputArgument::REQUIRED, 'The name of the model to create'),
        );
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}

<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MakeSelfController extends Command {
	
	/**
	* The console command name.
	*
	* @var string
	*/
	protected $name = 'command:self-controller';
	
	/**
	* The console command description.
	*
	* @var string
	*/
	protected $description = 'Create self define Controller (CRUD, basic, or single method)';
	
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
		$basic = $this->option('basic');
		$single = $this->option('single');
		
		$dir = app_path().'/controllers';
		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}
		
		$filepath = $dir.'/'.$name.'.php';
    
		if (file_exists($filepath)) {
			return $this->error('Controller already exists!');
		}
		
		$stub = $basic ? $this->getBasicStub() : ($single ? $this->getSingleStub() : $this->getResourceStub());
		$stub = str_replace('{{class}}', $name, $stub);
		
		file_put_contents($filepath, $stub);
		$this->info('Controller created successfully at ' . $name);
	}
	
	protected function getResourceStub()
	{
		return '<?php
		
class {{class}} extends BaseController {
				
	public function index()
	{
		// Show all items
	}
				
	public function create()
	{
		// Show create form
	}
				
	public function store()
	{
		// Handle form submission
	}
				
	public function show($id)
	{
		// Show single item
	}
				
	public function edit($id)
	{
		// Show edit form
	}
				
	public function update($id)
	{
		// Handle update
	}
				
	public function destroy($id)
	{
		// Handle deletion
	}
}';
	}
	
	protected function getBasicStub()
	{
		return '<?php
		
class {{class}} extends BaseController {
				
	// Add your methods here
}';
	}
	
	protected function getSingleStub()
	{
		return '<?php
		
class {{class}} extends BaseController
{				
	public function index()
	{
		// Show all items
	}
}';
	}
	
	/**
	* Get the console command arguments.
	*
	* @return array
	*/
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'The name of the controller'],
		];
	}
	
	/**
	* Get the console command options.
	*
	* @return array
	*/
	protected function getOptions()
	{
		return [
			['basic', 'b', InputOption::VALUE_NONE, 'Create a basic empty controller'],
			['single', 's', InputOption::VALUE_NONE, 'Create controller with only index method'],
		];
	}
	
}

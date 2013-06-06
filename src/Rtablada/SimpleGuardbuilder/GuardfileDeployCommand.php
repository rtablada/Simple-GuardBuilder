<?php namespace Rtablada\SimpleGuardbuilder;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GuardfileDeployCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'guard:deploy';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Builds Simple Guardfile from configuration.';

	/**
	 * Guardbuilder instance
	 *
	 * @var GuardBuilder
	 */
	protected $builder;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(GuardBuilder $builder)
	{
		parent::__construct();
		$this->builder = $builder;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->builder->buildGuardFile();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
	}

}

<?php namespace Rtablada\SimpleGuardBuilder;

use Mockery as m;

function base_path()
{
	return '';
}

use Rtablada\SimpleGuardbuilder\GuardBuilder;

class SimpleGuardbuilderTest extends \PHPUnit_Framework_Testcase
{
	protected $guardfilePath = '/Guardfile';
	protected $testPipelines = array(
		'application' => array(
			'styles' => array(
				'lib/bootstrap',
				'main'
			),
			'scripts' => array(
				'lib/jquery'
			)
		),
		'test' => array(
			'styles' => array(
				'main'
			),
			'scripts' => array(
				'main'
			)
		)
	);

	public function __construct()
	{
		$file = new \Illuminate\Filesystem\Filesystem;
		$this->fs = $file;
		$this->concatStub = $file->get(__DIR__ . '/stubs/concat.stub');
		$this->resultConcatStub = $file->get(__DIR__ . '/stubs/resultConcat.stub');
	}

	public function setup()
	{
		$this->file = m::mock('Illuminate\Filesystem\Filesystem');
		$this->config = m::mock('Illuminate\Config\Repository');
		$this->builder = new GuardBuilder($this->file, $this->config);
	}

	public function tearDown()
    {
        m::close();
    }

    public function testGetGuardfilePath()
    {
    	$this->assertEquals($this->guardfilePath, $this->builder->getGuardfilePath());
    }

	public function testCreatesConcatStubs()
	{
		$this->config->shouldReceive('get')->once()
			->with('simple-guardbuilder::pipelines')
			->andReturn($this->testPipelines);
		$this->file->shouldReceive('get')->once()
			->with(m::type('string'))
			->andReturn($this->concatStub);

		$stub = $this->builder->getConcatStubs();

		$this->fs->put(__DIR__ . '/test.stub', $stub);

		$this->assertEquals($this->resultConcatStub, $stub);
	}

	public function testBuildsAFile()
	{
		$this->config->shouldReceive('get')->once()
			->with('simple-guardbuilder::pipelines')
			->andReturn($this->testPipelines);

		$builder = new GuardBuilder($this->fs, $this->config);

		$stub = $builder->buildGuardFile(__DIR__.'/Guardfile');

		$this->fs->put(__DIR__ . '/test.stub', $stub);
	}
}

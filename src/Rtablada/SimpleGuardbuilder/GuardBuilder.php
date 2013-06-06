<?php namespace Rtablada\SimpleGuardbuilder;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository as Config;

/**
* Builds Guardfile from config
*/
class GuardBuilder
{
	protected $cachedStubs = array();

	public function __construct(Filesystem $file, Config $config)
	{
		$this->file = $file;
		$this->config = $config;
	}

	public function buildGuardFile($path = null)
	{

		$stub = $this->getCoffeeStub();
		$stub .= $this->getLessStub();
		$stub .= $this->getConcatStubs();

		$this->writeGuardFile($path, $stub);
	}

	public function writeGuardFile($path, $stub)
	{
		$this->file->put($this->getGuardfilePath($path), $stub);
	}

	public function getConcatStubs()
	{
		$pipelines = $this->getPipelinesConfig();
		$stub = '';

		foreach ($pipelines as $name => $options) {
			$stub .= $this->parseConcatStub($name, $options);
		}

		return $stub;
	}

	public function getCoffeeStub()
	{
		return $this->getStub('coffee');
	}

	public function getLessStub()
	{
		return $this->getStub('less');
	}

	protected function getPipelinesConfig()
	{
		return $this->config->get('simple-guardbuilder::pipelines');
	}

	public function getGuardfilePath($path = null)
	{
		return $path ? $path : base_path() . '/Guardfile';
	}

	protected function parseConcatStub($name, $options)
	{
		$stub = $this->getStub('concat');

		$styles = $options['styles'];
		$scripts = $options['scripts'];

		$this->parseScriptsFileArray($stub, $scripts);
		$this->parseStylesFileArray($stub, $styles);
		$this->parseVar('pipelineName', $stub, $name);

		return $stub;
	}

	protected function parseScriptsFileArray(&$stub, array $files)
	{
		$this->parseFileArray('scriptFiles', $stub, $files);
	}

	protected function parseStylesFileArray(&$stub, array $files)
	{
		$this->parseFileArray('styleFiles', $stub, $files);
	}

	protected function parseFileArray($name, &$stub, array $files)
	{
		$filesVar = '';
		foreach ($files as $file) {
			$filesVar .= $file . ' ';
		}

		$filesVar = trim($filesVar);

		$this->parseVar($name, $stub, $filesVar);
	}

	protected function parseVar($name, &$stub, $value)
	{
		$handlebar = '{{' . $name . '}}';
		$stub = str_replace($handlebar, $value, $stub);
	}

	protected function getStub($name)
	{
		if(! isset($this->cachedStubs[$name])) {
			$this->cachedStubs[$name] = $this->file->get(__DIR__ . "/stubs/{$name}.stub");
		}
		return $this->cachedStubs[$name];
	}
}

<?php

namespace Queue\Test\TestCase\Shell;

use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Queue\Shell\MongoQueueShell;
use Tools\TestSuite\ConsoleOutput;

use CakeMonga\MongoCollection\CollectionRegistry;

class QueueShellTest extends TestCase {

	/**
	 * @var \Queue\Shell\MongoQueueShell|\PHPUnit_Framework_MockObject_MockObject
	 */
	public $QueueShell;

	/**
	 * @var \Tools\TestSuite\ConsoleOutput
	 */
	public $out;

	/**
	 * @var \Tools\TestSuite\ConsoleOutput
	 */
	public $err;

	/**
	 * Fixtures to load
	 *
	 * @var array
	 */
	public $fixtures = [
		'plugin.Queue.QueuedJobs',
	];

	/**
	 * Setup Defaults
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->out = new ConsoleOutput();
		$this->err = new ConsoleOutput();
		$io = new ConsoleIo($this->out, $this->err);

		$this->QueueShell = $this->getMockBuilder(MongoQueueShell::class)
			->setMethods(['in', 'err', '_stop'])
			->setConstructorArgs([$io])
			->getMock();

		$this->QueueShell->initialize();
		$this->QueueShell->loadTasks();

		Configure::write('Queue', [
			'sleeptime' => 2,
			'gcprob' => 10,
			'defaultworkertimeout' => 3,
			'defaultworkerretries' => 1,
			'workermaxruntime' => 5,
			'cleanuptimeout' => 10,
			'exitwhennothingtodo' => false,
			'pidfilepath' => TMP . 'queue' . DS,
			'log' => false,
		]);
	}

	public function tearDown() {
		CollectionRegistry::setNamespace("Queue\\Model\\MongoCollection\\");
		// TODO Take connection name from configuration
		//$config = TableRegistry::exists('QueuedJobs') ? [] : ['className' => QueuedJobsTable::class];
		$queuedJobs = CollectionRegistry::get('QueuedJobs', ['connection' => 'mongo_db']);
		$queuedJobs->remove([]);
	}

	/**
	 * QueueShellTest::testObject()
	 *
	 * @return void
	 */
	public function testObject() {
		$this->assertTrue(is_object($this->QueueShell));
		$this->assertInstanceOf(MongoQueueShell::class, $this->QueueShell);
	}

	/**
	 * QueueShellTest::testStats()
	 *
	 * @return void
	 */
	public function testStats() {
		$this->markTestSkipped("Stats not implemented");
		//$this->QueueShell->stats();
		////debug($this->out->output());
		//$this->assertContains('Total unfinished Jobs      : 0', $this->out->output());
	}

	/**
	 * QueueShellTest::testSettings()
	 *
	 * @return void
	 */
	public function testSettings() {
		$this->QueueShell->settings();
		$this->assertContains('* cleanuptimeout: 10', $this->out->output());
	}

	/**
	 * QueueShellTest::testAddInexistent()
	 *
	 * @return void
	 */
	public function testAddInexistent() {
		$this->QueueShell->args[] = 'Foo';
		$this->QueueShell->add();
		$this->assertContains('Error: Task not found: Foo', $this->out->output());
	}

	/**
	 * QueueShellTest::testAdd()
	 *
	 * @return void
	 */
	public function testAdd() {
		$this->QueueShell->args[] = 'Example';
		$this->QueueShell->add();

		$this->assertContains('OK, job created, now run the worker', $this->out->output(), print_r($this->out->output, true));
	}

	/**
	 * QueueShellTest::testRetry()
	 *
	 * @return void
	 */
	public function testRetry() {
		$this->QueueShell->args[] = 'RetryExample';
		$this->QueueShell->add();

		$expected = 'This is a very simple example of a QueueTask and how retries work';
		$this->assertContains($expected, $this->out->output());

		$this->QueueShell->runworker();

		$this->assertContains('Job did not finish, requeued.', $this->out->output());
	}

}

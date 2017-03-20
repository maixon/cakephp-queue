<?php
namespace Queue\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

/**
 */
class QueueControllerTest extends IntegrationTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = [
		'plugin.queue.queued_jobs'
	];

	/**
	 * Test index method
	 *
	 * @return void
	 */
	public function testIndex() {
		$this->markTestSkipped("Stats aren't implemented and the controller has not been migrated to Mongo yet");
		$this->get(['prefix' => 'admin', 'plugin' => 'Queue', 'controller' => 'Queue', 'action' => 'index']);

		$this->assertResponseCode(200);
	}

}

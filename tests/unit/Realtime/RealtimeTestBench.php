<?php

ini_set('memory_limit', '-1');

use Appwrite\Database\Document;
use Appwrite\Realtime\Realtime;

/**
 * @Iterations(10)
 * @Revs(8)
 */
class RealtimeTestBench
{
    /**
     * Configures how many Connections the Test should Mock.
     */
    public $connectionsPerChannel = 100;

    public $connections = [];
    public $subscriptions = [];
    public $connectionsCount = 0;
    public $connectionsAuthenticated = 0;
    public $connectionsGuest = 0;
    public $connectionsTotal = 0;
    public $allChannels = [
        'files',
        'files.1',
        'collections',
        'collections.1',
        'collections.1.documents',
        'collections.2',
        'collections.2.documents',
        'documents',
        'documents.1',
        'documents.2',
    ];

    public function setUp(): void
    {
        /**
         * Setup global Counts
         */
        $this->connectionsAuthenticated = count($this->allChannels) * $this->connectionsPerChannel;
        $this->connectionsGuest = count($this->allChannels) * $this->connectionsPerChannel;
        $this->connectionsTotal = $this->connectionsAuthenticated + $this->connectionsGuest;

        /**
         * Add Authenticated Clients
         */
        for ($i = 0; $i < $this->connectionsPerChannel; $i++) {
            foreach ($this->allChannels as $index => $channel) {
                Realtime::setUser(new Document([
                    '$id' => 'user' . $this->connectionsCount,
                    'memberships' => [
                        [
                            'teamId' => 'team' . $i,
                            'roles' => [
                                empty($index % 2) ? 'admin' : 'member'
                            ]
                        ]
                    ]
                ]));

                Realtime::subscribe(
                    '1',
                    $this->connectionsCount,
                    Realtime::getRoles(),
                    $this->subscriptions,
                    $this->connections,
                    Realtime::parseChannels([0 => $channel])
                );

                $this->connectionsCount++;
            }
        }

        /**
         * Add Guest Clients
         */
        for ($i = 0; $i < $this->connectionsPerChannel; $i++) {
            foreach ($this->allChannels as $index => $channel) {
                Realtime::setUser(new Document([
                    '$id' => ''
                ]));

                Realtime::subscribe(
                    '1',
                    $this->connectionsCount,
                    Realtime::getRoles(),
                    $this->subscriptions,
                    $this->connections,
                    Realtime::parseChannels([0 => $channel])
                );

                $this->connectionsCount++;
            }
        }
    }

    public function setUp10() {
        $this->connectionsPerChannel = 1;
        $this->setUp();
    }
    public function setUp100() {
        $this->connectionsPerChannel = 10;
        $this->setUp();
    }
    public function setUp1000() {
        $this->connectionsPerChannel = 100;
        $this->setUp();
    }
    public function setUp10000() {
        $this->connectionsPerChannel = 1000;
        $this->setUp();
    }
    public function setUp100000() {
        $this->connectionsPerChannel = 10000;
        $this->setUp();
    }
    public function setUp1000000() {
        $this->connectionsPerChannel = 100000;
        $this->setUp();
    }

    public function tearDown(): void
    {
        $this->connections = [];
        $this->subscriptions = [];
        $this->connectionsCount = 0;
    }

    /**
     * @BeforeMethods("setUp10")
     * @AfterMethods("tearDown")
     */
    public function benchWildCard10 () {
        $this->runWildcardPermission();
    }
    /**
     * @BeforeMethods("setUp100")
     * @AfterMethods("tearDown")
     */
    public function benchWildCard100 () {
        $this->runWildcardPermission();
    }
    /**
     * @BeforeMethods("setUp1000")
     * @AfterMethods("tearDown")
     */
    public function benchWildCard1000 () {
        $this->runWildcardPermission();
    }
    /**
     * @BeforeMethods("setUp10000")
     * @AfterMethods("tearDown")
     */
    public function benchWildCard10000 () {
        $this->runWildcardPermission();
    }
    /**
     * @BeforeMethods("setUp100000")
     * @AfterMethods("tearDown")
     */
    public function benchWildCard100000 () {
        $this->runWildcardPermission();
    }
    /**
     * @BeforeMethods("setUp1000000")
     * @AfterMethods("tearDown")
     */
    public function benchWildCard1000000 () {
        $this->runWildcardPermission();
    }

    public function runWildcardPermission()
    {
        $event = [
            'project' => '1',
            'permissions' => ['*'],
            'data' => [
                'channels' => [
                    0 => 'collections.2.documents',
                ]
            ]
        ];

        Realtime::identifyReceivers(
            $event,
            $this->subscriptions
        );
    }

    /**
     * @BeforeMethods("setUp10")
     * @AfterMethods("tearDown")
     */
    public function benchRole10 () {
        $this->runRolePermission();
    }
    /**
     * @BeforeMethods("setUp100")
     * @AfterMethods("tearDown")
     */
    public function benchRole100 () {
        $this->runRolePermission();
    }
    /**
     * @BeforeMethods("setUp1000")
     * @AfterMethods("tearDown")
     */
    public function benchRole1000 () {
        $this->runRolePermission();
    }
    /**
     * @BeforeMethods("setUp10000")
     * @AfterMethods("tearDown")
     */
    public function benchRole10000 () {
        $this->runRolePermission();
    }
    /**
     * @BeforeMethods("setUp100000")
     * @AfterMethods("tearDown")
     */
    public function benchRole100000 () {
        $this->runRolePermission();
    }
    /**
     * @BeforeMethods("setUp1000000")
     * @AfterMethods("tearDown")
     */
    public function benchRole1000000 () {
        $this->runRolePermission();
    }

    public function runRolePermission()
    {
        $event = [
            'project' => '1',
            'permissions' => ['role:member'],
            'data' => [
                'channels' => [
                    0 => 'collections.2.documents',
                ]
            ]
        ];

        Realtime::identifyReceivers(
            $event,
            $this->subscriptions
        );
    }


    /**
     * @BeforeMethods("setUp10")
     * @AfterMethods("tearDown")
     */
    public function benchUser10 () {
        $this->runUserPermission();
    }
    /**
     * @BeforeMethods("setUp100")
     * @AfterMethods("tearDown")
     */
    public function benchUser100 () {
        $this->runUserPermission();
    }
    /**
     * @BeforeMethods("setUp1000")
     * @AfterMethods("tearDown")
     */
    public function benchUser1000 () {
        $this->runUserPermission();
    }
    /**
     * @BeforeMethods("setUp10000")
     * @AfterMethods("tearDown")
     */
    public function benchUser10000 () {
        $this->runUserPermission();
    }
    /**
     * @BeforeMethods("setUp100000")
     * @AfterMethods("tearDown")
     */
    public function benchUser100000 () {
        $this->runUserPermission();
    }
    /**
     * @BeforeMethods("setUp1000000")
     * @AfterMethods("tearDown")
     */
    public function benchUser1000000 () {
        $this->runUserPermission();
    }

    public function runUserPermission()
    {
        $event = [
            'project' => '1',
            'permissions' => ['user:user10'],
            'data' => [
                'channels' => [
                    0 => 'collections.2.documents',
                ]
            ]
        ];

        Realtime::identifyReceivers(
            $event,
            $this->subscriptions
        );
    }
}
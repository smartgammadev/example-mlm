<?php
namespace Success\MemberBundle\Traits;

use Predis\Client;

trait SetRedisClientTrait
{

    /**
     *
     * @var \Predis\Client $redisClient
     */
    private $redisClient;

    public function setRedisClient(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

}

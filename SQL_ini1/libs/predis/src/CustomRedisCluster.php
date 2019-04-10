<?php

use Predis\Cluster\Distributor\DistributorInterface;
use Predis\Cluster\Hash\HashGeneratorInterface;
use Predis\Cluster\PredisStrategy;
use Predis\Connection\Aggregate\PredisCluster;

class NaiveDistributor implements DistributorInterface, HashGeneratorInterface
{
    private $nodes;
    private $nodesCount;

    public function __construct()
    {
        $this->nodes = array();
        $this->nodesCount = 0;
    }

    public function add($node, $weight = null)
    {
        $this->nodes[] = $node;
        ++$this->nodesCount;
    }

    public function remove($node)
    {
        $this->nodes = array_filter($this->nodes, function ($n) use ($node) {
            return $n !== $node;
        });

        $this->nodesCount = count($this->nodes);
    }

    public function getSlot($hash)
    {
        return $this->nodesCount > 1 ? abs($hash % $this->nodesCount) : 0;
    }

    public function getBySlot($slot)
    {
        return isset($this->nodes[$slot]) ? $this->nodes[$slot] : null;
    }

    public function getByHash($hash)
    {
        if (!$this->nodesCount) {
            throw new RuntimeException('No connections.');
        }

        $slot = $this->getSlot($hash);
        $node = $this->getBySlot($slot);

        return $node;
    }

    public function get($value)
    {
        $hash = $this->hash($value);
        $node = $this->getByHash($hash);

        return $node;
    }

    public function hash($value)
    {
        return crc32($value);
    }

    public function getHashGenerator()
    {
        return $this;
    }
}

$options = array(
    'cluster' => function () {
        $distributor = new NaiveDistributor();
        $strategy = new PredisStrategy($distributor);
        $cluster = new PredisCluster($strategy);

        return $cluster;
    },
);

<?php

namespace Hch\ElasticMLBundle\Service;

abstract class AbstractMLService
{
    abstract public function createJob(array $params);

    abstract public function getResults(string $jobId);
}

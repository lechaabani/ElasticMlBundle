<?php

namespace Hch\ElasticMLBundle\Tests\Service;

use Hch\ElasticMLBundle\Service\ElasticMLService;
use PHPUnit\Framework\TestCase;

class ElasticMLServiceTest extends TestCase
{
    public function testCreateJob()
    {
        $service = $this->createMock(ElasticMLService::class);
        $service->expects($this->once())
            ->method('createJob')
            ->with($this->isType('array'));

        $service->createJob(['job_id' => 'test']);
    }
}

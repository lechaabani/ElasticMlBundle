<?php

namespace Hch\ElasticMLBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Hch\ElasticMLBundle\Service\ElasticMLService;

#[AsCommand(
    name: 'elastic:ml:create-job',
    description: 'Creates a Machine Learning job in Elasticsearch.'
)]
class CreateMLJobCommand extends Command
{
    private $elasticMLService;

    public function __construct(ElasticMLService $elasticMLService)
    {
        parent::__construct();
        $this->elasticMLService = $elasticMLService;
    }

    protected function configure()
    {
        $this
            ->addOption('jobId', null, InputOption::VALUE_REQUIRED, 'The job ID')
            ->addOption('index', null, InputOption::VALUE_REQUIRED, 'The data index');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $jobId = $input->getOption('jobId');
        $index = $input->getOption('index');

        $params = [
            'job_id' => $jobId,
            'analysis_config' => [ /* job config here */ ],
            'data_description' => ['time_field' => '@timestamp'],
            'datafeed_config' => ['indices' => [$index]],
        ];

        $this->elasticMLService->createJob($params);

        $output->writeln('Job created successfully!');

        return Command::SUCCESS;
    }
}

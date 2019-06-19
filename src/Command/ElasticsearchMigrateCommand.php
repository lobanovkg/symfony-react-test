<?php

namespace App\Command;

use App\Model\ElasticsearchMigration\ESMigration;
use App\Service\ElasticSearch;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ElasticsearchMigrateCommand extends Command
{
    const OPTION_ES_INDEX_NAME = 'es-index';

    protected static $defaultName = 'app:elasticsearch-migrate';

    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setDescription('Migrate mysql data to Elasticsearch by index.')
            ->addOption('es-index', null, InputOption::VALUE_REQUIRED, 'Elasticsearch index name.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $em = $this->container->get('doctrine')->getManager();

        $indexName = $input->getOption(self::OPTION_ES_INDEX_NAME);

        $esMigration = new ESMigration($indexName, $em, new ElasticSearch());

        $errors = $esMigration->getErrors();

        if (isset($errors)) {
            $io->error($errors);

            return;
        }

        $io->success('Your index migrated successful.');
    }
}

<?php

namespace App\Command;

use App\Model\FileReader\FileReader;
use App\Model\Processor\FileHeader;
use App\Model\Processor\FileProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

class CsvMigrateCommand extends Command
{
    const OPTION_FILE_PATH_NAME = 'file-path';

    private $container;

    protected static $defaultName = 'app:csv-migrate';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setDescription('This command for migrate data from file to DB')
            ->addOption(self::OPTION_FILE_PATH_NAME, 'fp', InputOption::VALUE_REQUIRED, 'File path for migrate data', '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $path = $input->getOption(self::OPTION_FILE_PATH_NAME);

        $filesystem = new Filesystem();

        if (!$filesystem->isAbsolutePath($path)) {
            $io->error('Your path must be absolute!');

            return;
        }

        $pathParts = pathinfo($path);
        if ('csv' !== strtolower($pathParts['extension'])) {
            $io->error('Your path must have .csv type!');

            return;
        }

        $em = $this->container->get('doctrine')->getManager();

        $fileReader = new FileReader();
        $fileReader->setProcessor(new FileProcessor(new FileHeader(), $em));

        $fileReader->setFilePath($path);
        $fileReader->runMigration();
        $processorLog = $fileReader->getProcessorsResult();

        $table = new Table($output);
        $table->setHeaders(
            [
                [new TableCell(sprintf('%s', $path), ['colspan' => 5])],
                ['New Rows', 'Exist', 'Errors', 'Total', 'Error Message'],
            ]
        )
            ->setRows(
                [
                    [
                        $processorLog['newRowsCount'],
                        $processorLog['existRowsCount'],
                        $processorLog['errorsCount'],
                        $processorLog['rowsCount'],
                        $processorLog['errorMessage'],
                    ],
                ]
            );
        $table->render();
    }
}

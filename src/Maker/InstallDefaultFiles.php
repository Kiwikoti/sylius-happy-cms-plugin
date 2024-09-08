<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

final class InstallDefaultFiles extends AbstractMaker
{
    public const TPL_FILES = [
        'entity' => __DIR__ . '/../../Resources/skeleton/default/entity.tpl.php',
        'translation' => __DIR__ . '/../../Resources/skeleton/cms/translation.tpl.php',
        'repository' => __DIR__ . '/../../Resources/skeleton/cms/repository.tpl.php',
        'admin' => __DIR__ . '/../../Resources/skeleton/cms/admin.tpl.php',
    ];

    public static function getCommandName(): string
    {
        return 'make:happy-cms:install';
    }

    public static function getCommandDescription(): string
    {
        return 'Install default Entities, Translations, Repositories and Admin classes';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription(self::getCommandDescription())
        ;
    }

    /**
     * @throws \Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $happyCMSDefaultPackageParameters = [
            'sylius_happy_cms:',
        ];
        $this->generatePage($happyCMSDefaultPackageParameters, $io, $generator);
        $this->generateConfig($happyCMSDefaultPackageParameters, $io, $generator);

        $io->newLine();
        $io->success('Success!');
        $io->newLine();
        $choice = $io->confirm('Do you want to get to configuration lines to add into config/packages/sylius_happy_cms.yaml file ?');

        // Add
        if ($choice) {
            $io->writeln($happyCMSDefaultPackageParameters);
        }
    }

    /**
     * @param string[] $happyCMSDefaultPackageParameters
     */
    private function generatePage(array &$happyCMSDefaultPackageParameters, ConsoleStyle $io, Generator $generator): void
    {
        $scope = 'page';
        $files = [
            ['prefix' => 'Entity', 'suffix' => ''],
            ['prefix' => 'Entity', 'suffix' => 'Translation'],
            ['prefix' => 'Repository', 'suffix' => 'Repository'],
            ['prefix' => 'Admin', 'suffix' => 'Admin'],
        ];
        $this->generateScope($scope, $files, $io, $generator);
        $this->getHappyCMSDefaultPackageParameters($happyCMSDefaultPackageParameters, $scope);
    }

    /**
     * @param string[] $happyCMSDefaultPackageParameters
     */
    private function generateConfig(array &$happyCMSDefaultPackageParameters, ConsoleStyle $io, Generator $generator): void
    {
        $scope = 'config';
        $files = [
            ['prefix' => 'Entity', 'suffix' => ''],
            ['prefix' => 'Entity', 'suffix' => 'Translation'],
            ['prefix' => 'Repository', 'suffix' => 'Repository'],
            ['prefix' => 'Admin', 'suffix' => 'Admin'],
        ];
        $this->generateScope($scope, $files, $io, $generator);
        $this->getHappyCMSDefaultPackageParameters($happyCMSDefaultPackageParameters, $scope);
    }

    /**
     * @param array<int, array<string, string>> $files
     */
    private function generateScope(string $scope, array $files, ConsoleStyle $io, Generator $generator): void
    {
        foreach ($files as $data) {
            $namespacePrefix = $data['prefix'] . '\HappyCMS\\' . ucfirst($scope);
            $classNameDetail = $generator->createClassNameDetails($scope, $namespacePrefix, $data['suffix']);

            try {
                if (class_exists($classNameDetail->getFullName())) {
                    $io->comment(sprintf(
                        '%s: %s',
                        '<fg=yellow>warning</>',
                        $classNameDetail->getFullName() . ' already exists',
                    ));
                } else {
                    $generator->generateClass(
                        $classNameDetail->getFullName(),
                        __DIR__ . '/../Resources/skeleton/default/' . strtolower($data['prefix']) . '.tpl.php',
                        [
                            'classNameDetail' => $classNameDetail,
                        ],
                    );
                    $generator->writeChanges();
                }
            } catch (\Exception $exception) {
                $io->error($exception->getMessage());
            }
        }
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        // No dependencies needed
    }

    /**
     * @param string[] $happyCMSDefaultPackageParameters
     */
    private function getHappyCMSDefaultPackageParameters(array &$happyCMSDefaultPackageParameters, string $scope): void
    {
        $happyCMSDefaultPackageParameters = array_merge($happyCMSDefaultPackageParameters, [
            '   ' . $scope . ':',
            '       page_class: App\Entity\HappyCMS\\' . ucfirst($scope) . '\\' . ucfirst($scope),
            '       page_repository: App\Repository\HappyCMS\\' . ucfirst($scope) . '\\' . ucfirst($scope) . 'Repository',
            '       page_admin: App\Admin\HappyCMS\\' . ucfirst($scope) . '\\' . ucfirst($scope) . 'Admin',
        ]);
    }
}

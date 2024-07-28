<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Maker\CMS;

use Adeliom\SyliusEasyCrudPlugin\Services\CrudMakerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class MakeHappyCMS extends AbstractMaker
{
    public const BLOG_SCOPE = 'Blog';

    public const FAQ_SCOPE = 'Faq';

    public const SCOPES = [
        self::BLOG_SCOPE,
        self::FAQ_SCOPE,
    ];

    public const TPL_FILES = [
        'entity' => __DIR__ . '/../../Resources/skeleton/cms/entity.tpl.php',
        'translation' => __DIR__ . '/../../Resources/skeleton/cms/translation.tpl.php',
        'repository' => __DIR__ . '/../../Resources/skeleton/cms/repository.tpl.php',
        'admin' => __DIR__ . '/../../Resources/skeleton/cms/admin.tpl.php',
    ];

    public function __construct(
        protected ManagerRegistry $managerRegistry,
        protected ParameterBagInterface $parameterBag,
    ) {
    }

    public static function getCommandName(): string
    {
        return 'make:happy-cms';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates FAQ/Blog Entities, Translations, Repositories and Admin classes';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription(self::getCommandDescription())
            ->addArgument('scope', InputArgument::OPTIONAL, 'Scope of classes to create')
            ->addArgument(
                'entryClassName',
                InputArgument::OPTIONAL,
                'Entity name for %s entries',
                'Entry',
            )
            ->addArgument(
                'categoryClassName',
                InputArgument::OPTIONAL,
                'Entity name for %s categories',
                'Category',
            )
        ;
        $inputConfig->setArgumentAsNonInteractive('scope');
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        $argument = $command->getDefinition()->getArgument('scope');
        $scope = $io->choice($argument->getDescription(), self::SCOPES);

        $input->setArgument('scope', $scope);

        foreach (['entryClassName', 'categoryClassName'] as $argName) {
            $arg = $command->getDefinition()->getArgument($argName);
            $question = sprintf($arg->getDescription(), $scope);
            $default = $arg->getDefault();
            if (is_string($default) || null === $default) {
                $input->setArgument(
                    $arg->getName(),
                    $io->ask($question, $default),
                );
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $entryClassName = Str::asClassName($input->getArgument('entryClassName'));
        $scope = $input->getArgument('scope');

        if (!\class_exists($entryClassName)) {
            $entryClassName = $generator->createClassNameDetails($entryClassName, 'Entity\\HappyCMS\\')->getFullName();
        }

        $namespace = \trim($generator->getRootNamespace(), '\\');

        $categoryClassName = Str::asClassName($input->getArgument('categoryClassName'));

        [$entity, $entityTranslation, $repository] = CrudMakerService::getEntity(
            $entryClassName,
            $generator,
            $this->managerRegistry,
        );

        $projectDir = $this->parameterBag->get('kernel.project_dir');

        try {
            $resourceConfigGenerator = new CrudMakerService(
                is_string($projectDir) ? $projectDir : '',
                $generator,
                $namespace,
                $entity,
                $repository,
                $entityTranslation,
            );

            // TODO : get template and commented vars below
            $resourceConfigGenerator->generateEntity($entryClassName, self::TPL_FILES['entity'], [
                        'scope' => $scope,
                        //'entityClassName' => $entryClassName,
                        //'relationClassName' => $categoryClassName,
                        //'repository' => [
                        //    'name' => $entryClassName . 'Repository',
                        //    'FQCN' => $namespaces['repository'] . $entryClassName . 'Repository',
                        //],
                        //'options' => [
                        //    'isOwningSide' => true,
                        //    'hasRouting' => false,
                        //],
                    ]);
            // TODO : get template and commented vars below
            $resourceConfigGenerator->generateEntityTranslation($entryClassName);

            // TODO : get template and commented vars below
            $resourceConfigGenerator->generateRepository($entryClassName);

            // TODO : get template and commented vars below
            $resourceConfigGenerator->generateMenuListener($entryClassName);

            // TODO : get template and commented vars below
            $resourceConfigGenerator->generateAdmin();

            // TODO : get template and commented vars below
            $resourceConfigGenerator->generateController();

            $route = $resourceConfigGenerator->generateRoute();

            $io->comment(sprintf(
                '%s: %s',
                '<fg=yellow>updated</>',
                $route,
            ));

            $resource = $resourceConfigGenerator->generateResource();

            $io->comment(sprintf(
                '%s: %s',
                '<fg=yellow>updated</>',
                $resource,
            ));

            $this->writeSuccessMessage($io);
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
        }

        //$scope = $input->getArgument('scope');
        //$namespaces = [
        //    'entity' => 'App\\Entity\\HappyCMS\\' . $scope . '\\',
        //    'repository' => 'App\\Repository\\HappyCMS\\' . $scope . '\\',
        //    'admin' => 'App\\Admin\\HappyCMS\\' . $scope . '\\',
        //];
        //
        //// ENTITY
        //$generator->generateClass(
        //    $namespaces['entity'] . $entryClassName,
        //    self::TPL_FILES['entity'],
        //    [
        //        'scope' => $scope,
        //        'namespace' => $namespaces['entity'],
        //        'entityClassName' => $entryClassName,
        //        'relationClassName' => $categoryClassName,
        //        'repository' => [
        //            'name' => $entryClassName . 'Repository',
        //            'FQCN' => $namespaces['repository'] . $entryClassName . 'Repository',
        //        ],
        //        'options' => [
        //            'isOwningSide' => true,
        //            'hasRouting' => false,
        //        ],
        //    ],
        //);
        //// TRANSLATION
        //$generator->generateClass(
        //    $namespaces['entity'] . $entryClassName . 'Translation',
        //    self::TPL_FILES['translation'],
        //    [
        //        'scope' => $scope,
        //        'namespace' => $namespaces['entity'],
        //        'entityClassName' => $entryClassName,
        //        'withFlexibleContent' => self::BLOG_SCOPE === $scope,
        //        'extraFields' => self::FAQ_SCOPE === $scope ?
        //            [
        //                ['name' => 'answer', 'columnType' => \Doctrine\DBAL\Types\Types::TEXT, 'phpType' => 'string'],
        //            ] :
        //            null,
        //    ],
        //);
        ////REPOSITORY
        //$generator->generateClass(
        //    $namespaces['repository'] . $entryClassName . 'Repository',
        //    self::TPL_FILES['repository'],
        //    [
        //        'scope' => $scope,
        //        'namespace' => $namespaces['repository'],
        //        'entityClassName' => $entryClassName,
        //        'relationClassName' => $categoryClassName,
        //    ],
        //);
        ////ADMIN
        //$generator->generateClass(
        //    $namespaces['admin'] . $entryClassName . 'Admin',
        //    self::TPL_FILES['admin'],
        //    [
        //        'scope' => $scope,
        //        'namespace' => $namespaces['admin'],
        //        'entityClassName' => $entryClassName,
        //        'relationClassName' => $categoryClassName,
        //        'withFlexibleContent' => self::BLOG_SCOPE === $scope,
        //        'extraFields' => self::FAQ_SCOPE === $scope ?
        //            [
        //                ['name' => 'answer'],
        //            ] :
        //            null,
        //    ],
        //);
        //
        ////ENTITY
        //$generator->generateClass(
        //    $namespaces['entity'] . $categoryClassName,
        //    self::TPL_FILES['entity'],
        //    [
        //        'scope' => $scope,
        //        'namespace' => $namespaces['entity'],
        //        'entityClassName' => $categoryClassName,
        //        'relationClassName' => $entryClassName,
        //        'repository' => [
        //            'name' => $categoryClassName . 'Repository',
        //            'FQCN' => $namespaces['repository'] . $categoryClassName . 'Repository',
        //        ],
        //        'options' => [
        //            'isOwningSide' => false,
        //            'hasRouting' => true,
        //        ],
        //    ],
        //);
        ////TRANSLATION
        //$generator->generateClass(
        //    $namespaces['entity'] . $categoryClassName . 'Translation',
        //    self::TPL_FILES['translation'],
        //    [
        //        'scope' => $scope,
        //        'namespace' => $namespaces['entity'],
        //        'entityClassName' => $categoryClassName,
        //    ],
        //);
        ////REPOSITORY
        //$generator->generateClass(
        //    $namespaces['repository'] . $categoryClassName . 'Repository',
        //    self::TPL_FILES['repository'],
        //    [
        //        'scope' => $scope,
        //        'namespace' => $namespaces['repository'],
        //        'entityClassName' => $categoryClassName,
        //        'relationClassName' => $entryClassName,
        //    ],
        //);
        ////ADMIN
        //$generator->generateClass(
        //    $namespaces['admin'] . $categoryClassName . 'Admin',
        //    self::TPL_FILES['admin'],
        //    [
        //        'scope' => $scope,
        //        'namespace' => $namespaces['admin'],
        //        'entityClassName' => $categoryClassName,
        //    ],
        //);
        //
        //$generator->writeChanges();
        //
        //$resourceConfigGenerator = new ResourceConfigGeneratorService($scope, $namespaces);
        //$updatedFiles = $resourceConfigGenerator->generateConfig(
        //    $entryClassName,
        //    $categoryClassName,
        //);
        //$io->writeln('Updated : ' . implode(', ', $updatedFiles));

        $this->writeSuccessMessage($io);
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        // No dependencies needed
    }
}

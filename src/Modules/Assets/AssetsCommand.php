<?php

namespace Nameless\Modules\Assets;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Base AdminController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AssetsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('assets:compile')
            ->setDescription('Compiled assets')
            ->addOption('package', 'p', InputOption::VALUE_REQUIRED, 'Compile package by name');;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start compiling assets: ' . date('Y-m-d H:i:s'));

        /** @var \Nameless\Core\Application $app */
        $app = $this->getApplication();
        /** @var \Nameless\Core\Container $app */
        $container = $app->getContainer();

        $container['environment'] = 'test';

        $assets     = $container['assets'];
        $dispatcher = $container['assets.dispatcher'];

        $altPackage = $input->getOption('package');
        if ($altPackage) {
            $this->compilePackage($altPackage, $assets['packages'][$altPackage], $assets, $dispatcher, $output);
        } else {
            foreach ($assets['packages'] as $package => $libs) {
                $this->compilePackage($package, $libs, $assets, $dispatcher, $output);
            }
        }

        $output->writeln("End compiling assets.\n");

        return null;
    }

    /**
     * @param string           $package
     * @param array            $libs
     * @param array            $assets
     * @param AssetsDispatcher $dispatcher
     * @param OutputInterface  $output
     */
    protected function compilePackage($package, $libs, $assets, $dispatcher, $output)
    {
        $css_package = [];
        $js_package  = [];

        $output->writeln("\tPackage: " . $package);

        foreach ($libs as $lib) {
            if (isset($assets['libs'][$lib]['css'])) {
                $output->writeln("\t\tAdded file: " . $assets['libs'][$lib]['css']);
                $css_package[] = $assets['libs'][$lib]['css'];
            }
            if (isset($assets['libs'][$lib]['less'])) {
                $output->writeln("\t\tAdded file: " . $assets['libs'][$lib]['less']);
                $css_package[] = $assets['libs'][$lib]['less'];
            }
            if (isset($assets['libs'][$lib]['js'])) {
                $output->writeln("\t\tAdded file: " . $assets['libs'][$lib]['js']);
                $js_package[] = $assets['libs'][$lib]['js'];
            }
        }

        $dispatcher->getAssets($package, $css_package);
        $dispatcher->getAssets($package, $js_package);
    }
}

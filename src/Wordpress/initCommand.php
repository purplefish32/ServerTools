<?php
namespace Wordpress;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class InitCommand extends Command
{

  	/**
   	* Configures the current command.
   	*/
  	protected function configure()
  	{
    	$this
    		->setName('wordpress:init')
      		->setDescription('Download and install the latest version of WordPress in the current directory.');
 	}

  	/**
   	* Execute the command
   	*
   	* @param InputInterface $input
   	* @param OutputInterface $output
   	*/
  	protected function execute(InputInterface $input, OutputInterface $output)
  	{
    	// Want to make sure we really want to run this stuff
    	if (!$this->getDialog()->askConfirmation($output,'<question>This will download the latest version of WorPress, Do you want to continue? (default: no)</question> ', false)){
        	$output->writeln('aborted');
    	}
    	$batchProcesses = array(
          	// Clone the symfony standard repo. This is bleeding edge =)
          	// @todo in the future can checkout the version of symfony we want then
          	// then delete the git directory and ignore file
          	// @todo What if user does not have git installed?
          	new Process('git clone git://github.com/WordPress/WordPress.git .'),
          	// We won't need this any more
          	new Process('rm -rf .git/ .gitignore'),
        );

        foreach ($batchProcesses as $process) {
            $output->writeln(sprintf('Executing Command: %s',$process->getCommandLine()));
            $process->run(function($type, $buffer) use($output) {$output->write($buffer);});
            if (!$process->isSuccessful()){
                $output->writeln(sprintf('<error>%s</error>',$process->getErrorOutput()));
            }
        }
  	}

  	/**
	*
	* @return Symfony\Component\Console\Helper\DialogHelper
	*/
    protected function getDialog()
    {
        return $this->getHelperSet()->get('dialog');
    }
}
<?php

namespace NdB\PhpDocCheck;

use GetOpt\ArgumentException;
use GetOpt\GetOpt;
use GetOpt\Operand;
use GetOpt\Option;

class ApplicationArgumentsProvider
{
    protected $getOpt;
    public function __construct()
    {
        $this->getOpt = new GetOpt([
            Option::create('x', 'exclude', GetOpt::MULTIPLE_ARGUMENT)
                ->setDescription('Directories to exclude, without slash'),
            Option::create('f', 'format', GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('Output format [text, json] [default: text]')
                ->setDefaultValue('text'),
            Option::create('o', 'reportFile', GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('Send report output to a file')
                ->setDefaultValue(''),
            Option::create('m', 'metric', GetOpt::MULTIPLE_ARGUMENT)
                ->setDescription(
                    'Metric to use for determining complexity [metrics.complexity.cognitive, '.
                    'metrics.complexity.cyclomatic, metrics.deprecated.category, metrics.deprecated.subpackage, '.
                    'metrics.complexity.length] [default: metrics.complexity.cognitive]'
                )
                ->setDefaultValue('metrics.complexity.cognitive'),
            Option::create('w', 'complexity-warning-threshold', GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('Cyclomatic complexity score which is the lower bound for a warning [default: 4]')
                ->setDefaultValue(4)->setValidation('is_numeric'),
            Option::create('e', 'complexity-error-threshold', GetOpt::REQUIRED_ARGUMENT)
                ->setDescription('Cyclomatic complexity score which is the lower bound for an error [default: 6]')
                ->setDefaultValue(6)->setValidation('is_numeric'),
            Option::create('$', 'file-extension', GetOpt::MULTIPLE_ARGUMENT)
                ->setDescription('Valid file extensions to scan [default: php]')
                ->setDefaultValue('php'),
            Option::create('g', 'grouping-method', GetOpt::REQUIRED_ARGUMENT)
                ->setDescription(
                    'Allows different grouping of the results list '.
                    '[file, none, metric, severity, fileline] [default: file]'
                )
                ->setDefaultValue('file'),
            Option::create('s', 'sorting-method', GetOpt::REQUIRED_ARGUMENT)
                ->setDescription(
                    'Sorting for the results. Natural sorts by name for groups and line for findings. '.
                    'Value uses the cumulative group score, and finding score as sorting value. '.
                    '[natural, value] [default: natural]'
                )
                ->setDefaultValue('file'),
            Option::create('i', 'ignore-violations-on-exit', GetOpt::NO_ARGUMENT)
                ->setDescription('Will exit with a zero code, even if any violations are found'),
            Option::create('?', 'help', GetOpt::NO_ARGUMENT)
                ->setDescription('Show this help and quit'),
            Option::create('q', 'quiet', GetOpt::NO_ARGUMENT)
                ->setDescription('Don\'t show any output'),
        ]);
        $this->getOpt->addOperand(
            new Operand(
                'directory',
                Operand::MULTIPLE+Operand::REQUIRED
            )
        );
    }

    /**
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function getArguments($arguments = null):GetOpt
    {
        try {
            $this->getOpt->process($arguments);
        } catch (ArgumentException $e) {
            echo $e->getMessage() . "\n";
            echo $this->getOpt->getHelpText();
            exit;
        }
        return $this->getOpt;
    }
}

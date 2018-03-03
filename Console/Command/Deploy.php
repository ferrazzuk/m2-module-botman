<?php
namespace Lusiweb\Botman\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use React\EventLoop\Factory;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Slack\SlackRTMDriver;
use Lusiweb\Botman\Helper\Data;
use Lusiweb\Botman\Model\Conversations\Product\Create;

/**
 * Class Deploy
 * @package Lusiweb\Botman\Console\Command
 */
class Deploy extends Command
{
    /**
     * @var Data
     */
    protected $helper;
    protected $createConv;
    protected $appState;

    /**
     * Deploy constructor.
     * @param Data $helper
     * @param Create $createConv
     * @param State $appState
     */
    public function __construct(
        Data $helper,
        Create $createConv,
        State $appState
    ) {
        $this->helper = $helper;
        $this->createConv = $createConv;
        $this->appState = $appState;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('lusiweb:botman:start')
            ->setDescription('Starts Lusiweb botman');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appState->setAreaCode(Area::AREA_ADMINHTML);
        if ($this->helper->isEnabled() && $token = $this->helper->getBotToken()) {
            DriverManager::loadDriver(SlackRTMDriver::class);
            $loop   = Factory::create();
            $botMan = BotManFactory::createForRTM([
                'slack' => [
                    'token' => $token,
                ],
            ], $loop);

            $botMan->hears('test', function ($bot) {
                $bot->startConversation($this->createConv);
            });
            $botMan->fallback(function ($bot) {
                $bot->reply("Sorry I'm buggy now!");
            });

            $loop->run();
        }
    }
}

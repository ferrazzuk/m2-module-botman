<?php
namespace Lusiweb\Botman\Model\Conversations\Product;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use BotMan\BotMan\Messages\Incoming\Answer;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Create
 * @package Lusiweb\Botman\Model\Conversations\Product
 */
class Create extends Conversation
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepo;

    /**
     * @var ProductInterfaceFactory
     */
    protected $productFact;

    /**
     * @var string
     */
    protected $sku;

    /**
     * Create constructor.
     *
     * @param ProductRepositoryInterface $repository
     * @param ProductInterfaceFactory $factory
     */
    public function __construct(
        ProductRepositoryInterface $repository,
        ProductInterfaceFactory $factory
    ) {
        $this->productRepo = $repository;
        $this->productFact = $factory;
    }

    /**
     * Asks for sku.
     *
     * @return void
     */
    public function askSku()
    {
        // @todo 1. SKU syntax check
        // @todo 2. Add natural language

        $this->ask('SKU?', function (Answer $answer) {
            $sku = $answer->getText();
            try {
                $this->productRepo->get($sku);
                $this->say("`{$sku}` already exists!");
            } catch (NoSuchEntityException $exception) {
                $this->sku = $sku;
            }
        });
    }

    /**
     * {{@inheritdoc}}
     */
    public function run()
    {
        $this->askSku();
    }
}

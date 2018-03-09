<?php
namespace Lusiweb\Botman\Model\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;

/**
 * Class ConversationAbstract
 * @package Lusiweb\Botman\Model\Conversations
 */
abstract class ConversationAbstract extends Conversation
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
     * @var ProductInterface
     */
    protected $product;

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
     * If product data is missing and product can't
     * be saved then request the data.
     *
     * @return void
     */
    public function askForMissingData()
    {
        // @todo 1. logic
    }
}

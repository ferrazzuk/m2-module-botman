<?php
namespace Lusiweb\Botman\Model\Conversations\Product;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use BotMan\BotMan\Messages\Incoming\Answer;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Model\Product\Type;

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
     * Asks for sku.
     *
     * @return void
     */
    public function askSku()
    {
        // @todo 1. SKU syntax check
        // @todo 2. Add natural language

        $this->ask('Enter a product SKU', function (Answer $answer) {
            $sku = $answer->getText();
            try {
                $this->productRepo->get($sku);
                $this->say("`{$sku}` already exists!");
            } catch (NoSuchEntityException $exception) {
                $this->product->setSku($sku);
                $this->askName();
            }
        });
    }

    public function askName()
    {
        $this->ask('Enter a product name', function (Answer $answer) {
            $name = $answer->getText();
            $this->product->setName($name);
            $this->askPrice();
        });
    }

    public function askPrice()
    {
        $this->ask('Enter a product price', function (Answer $answer) {
            $price = $answer->getText();
            $this->product->setPrice(floatval($price));
            $this->askToSave();
        });
    }

    public function askToSave()
    {
        $this->ask("Would you like to save product?", function (Answer $answer) {
            $reply = boolval($answer->getText());
            if ($reply) {
                $this->product->setTypeId(Type::TYPE_SIMPLE);
                $this->product->setAttributeSetId(4);
                try {
                    $this->productRepo->save($this->product);
                    $this->say("Product saved.");
                } catch (CouldNotSaveException $exception) {
                    $this->say($exception->getMessage());
                }

            } else {
                $this->say("Sorry to hear that, goodbye.");
            }
        });
    }

    /**
     * {{@inheritdoc}}
     */
    public function run()
    {
        $this->product = $this->productFact->create();
        $this->askSku();
    }
}

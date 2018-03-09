<?php
namespace Lusiweb\Botman\Model\Conversations\Product;

use BotMan\BotMan\Messages\Incoming\Answer;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Model\Product\Type;
use Lusiweb\Botman\Model\Conversations\ConversationAbstract;
use Magento\Framework\Exception\StateException;

/**
 * Class Create
 * @package Lusiweb\Botman\Model\Conversations\Product
 */
class Create extends ConversationAbstract
{
    /**
     * Saves product type.
     *
     * @return void
     */
    public function askType()
    {
        // @todo 1. Logic
        // @todo 2. Send list of product types.
        // @todo 3. Capture user selection
    }

    /**
     * Saves product attributes set.
     *
     * @return void
     */
    public function askAttributeSet()
    {
        // @todo 1. logic
        // @todo 2. Send list of attributes sets.
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

    /**
     * Saves product name.
     *
     * @return void
     */
    public function askName()
    {
        // @todo 1. validation.
        $this->ask('Enter a product name', function (Answer $answer) {
            $name = $answer->getText();
            $this->product->setName($name);
            $this->askPrice();
        });
    }

    /**
     * Saves product price.
     *
     * @return void
     */
    public function askPrice()
    {
        // @todo 1. validation
        $this->ask('Enter a product price', function (Answer $answer) {
            $price = $answer->getText();
            $this->product->setPrice(floatval($price));
            $this->askToSave();
        });
    }

    /**
     * Saves product images.
     *
     * @return void
     */
    public function askForProductImages()
    {
        // @todo 1. logic
        // @todo 2. Image contains image attribute
        // @todo 3. Name format should match [Attribute|Order.jpg]
    }

    /**
     * Saves product.
     *
     * @return void
     */
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
                } catch (StateException $exception) {
                    $this->say($exception->getMessage());
                } catch (InputException $exception) {
                    $this->say($exception->getMessage());
                }

            } else {
                // @todo 1. proper message
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

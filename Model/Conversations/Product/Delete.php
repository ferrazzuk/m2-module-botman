<?php
namespace Lusiweb\Botman\Model\Conversations\Product;

use Lusiweb\Botman\Model\Conversations\ConversationAbstract;
use BotMan\BotMan\Messages\Incoming\Answer;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

/**
 * Class Delete
 * @package Lusiweb\Botman\Model\Conversations\Product
 */
class Delete extends ConversationAbstract
{
    /**
     * Deletes product.
     *
     * @used-by run
     * @return  void
     */
    public function askToDelete()
    {
        $this->ask('Enter a product SKU', function (Answer $answer) {
            $sku = $answer->getText();
            try {
                $this->productRepo->deleteById($sku);
                $this->say("Product deleted!");
            } catch (NoSuchEntityException $exception) {
                $this->say($exception->getMessage());
            } catch(StateException $exception) {
                $this->say($exception->getMessage());
            }
        });
    }

    /**
     * {{@inheritdoc}}
     *
     * @uses    askToDelete
     * @return  void
     */
    public function run()
    {
        $this->askToDelete();
    }
}

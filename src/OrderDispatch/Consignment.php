<?php

namespace OrderDispatch;

/**
 * Consignment
 * Data object representing an individual consignment
 * Also contains logic to send consignment through specified courier
 */
class Consignment
{
    /**
     * $id
     * Internal id of consignment
     * Auto increment id in mysql table, need a unique id for each consignment in case consignment numbers collide from separate couriers
     */
    private int $id;

    /**
     * $batchId
     * Id of the batch to which the consignment belongs
     */
    private int $batchId;

    /**
     * $products
     * Array of products the consignment should contain
     */
    public array $products;

    /**
     * $courier
     * Courier through which the consignment should be sent
     */
    private CourierInterface $courier;

    /**
     * $consignmentNumber
     * Courier-specific id for the consignment
     */
    public int $consignmentNumber;

    /**
     * $hasBeenSent
     * Boolean flag of whether the consignment has been sent yet or not
     */
    public bool $hasBeenSent;

    /**
     * Constructor
     *
     * @param array $products
     * @param CourierInterface $courier
     * @param integer $batchId
     * @param boolean $hasBeenSent
     * @return void
     */
    public function _construct(array $products, CourierInterface $courier, int $batchId, bool $hasBeenSent) {
        $this->products = $products;
        $this->courier = $courier;
        $this->consignmentNumber = $courier->getNextConsignmentNumber();
        $this->batchId = $batchId;
        $this->hasBeenSent = $hasBeenSent;
    }

    /**
     * send
     * Method to process a request to send the consignment
     *
     * @return bool success of send
     */
    public function send() : bool{
        try {
            $this->courier->processConsignment($this);
        } catch (\Exception $e) {
            // External service unavailable, complain as loudly as this is business critical
            return false;
        }
        $this->hasBeenSent = true;
        return true;
    }
}
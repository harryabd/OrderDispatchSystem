<?php

namespace OrderDispatch;

/**
 * Order Manager
 *
 * Class that should be used in client interface to manage orders
 */
class OrderManager
{
    /**
     * $dbh is a PDO that speaks to the storage of the app
     */
    private \PDO $dbh;

    /**
     * $currentBatch is an open batch
     */
    private Batch $currentBatch;

    /**
     * constructor
     *
     * @param \PDO $dbh
     */
    public function __construct(\PDO $dbh) {
        $this->dbh = $dbh;
    }

    /**
     * openBatch
     * Method to retrieve a batch object for order creation, must be called prior to createConsignment
     *
     * @return Batch
     */
    public function openBatch() : Batch {
        $latestBatch = Batch::getLatestBatch($this->dbh);
        if ($latestBatch->isOpen) {
            $batch = $latestBatch;
        } else {
            $batch = Batch::openNewBatch($this->dbh);

        }
        $this->currentBatch = $batch;
        return $batch;
    }

    /**
     * createConsignment
     * Method to store a consignment in a batch ready for processing
     *
     * @param array $products
     * @return void
     * @throws \Exception if current batch is not open
     */
    public function createConsignment(array $products) {
        if (!$this->currentBatch->isOpen) {
            throw new \Exception("Consignment creation cannot occur without an open batch");
        }
        // Get courier based on product attributes (size, weight, cost)?
        $courier = $this->courierCalculator($products);
        $consignment = new Consignment($products, $courier, $this->currentBatch->id);
        $this->currentBatch->addConsignment($this->dbh, $consignment);
    }

    /**
     * closeCurrentBatch
     * Method to process all consignments at the end of a dispatch period
     *
     * @return void
     * @throws \Exception if current batch not open
     */
    public function closeCurrentBatch() {
        if (!$this->currentBatch->isOpen) {
            throw new \Exception("Processing a batch is not possible without an open batch");
        }
        $this->currentBatch->processSends($this->dbh);
        $this->currentBatch = null;
    }

    /**
     * courierCalculator
     * Method to decide which courierr a consignment should be sent from
     *
     * @param array $products
     * @return CourierInterface
     */
    private function courierCalculator(array $products) : CourierInterface {
        // Logic that decides on the courier to send through and returns an Courier instance that implements CourierInterface
    }

}
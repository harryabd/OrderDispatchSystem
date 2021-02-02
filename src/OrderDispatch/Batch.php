<?php

namespace OrderDispatch;

/**
 * Batch
 * Data object representing a batch
 * Also contains logic to save and close itself and add consignments
 */
class Batch
{
    /**
     * $id
     * Id of batch
     */
    public int $id;

    /**
     * $consignments
     * Array of consignment objects
     */
    private array $consignments;

    /**
     * $isOpen
     * Boolean flag of whether batch is open or closed
     */
    public bool $isOpen;


    /**
     * __construct
     *
     * @param \PDO $dbh
     * @param integer $id
     * @param array $consignments
     * @param boolean $isOpen
     */
    private function __construct(\PDO $dbh, int $id, array $consignments, bool $isOpen) {
        $this->isOpen = $isOpen;
        $this->id = $id;
        $this->consignments = $consignments;
        // Save batch to table
    }

    /**
     * openNewBatch
     * Method to be called in order to create a new batch
     *
     * @param \PDO $dbh
     * @return Batch
     */
    public static function openNewBatch(\PDO $dbh) : Batch {
        $previousBatch = self::getLatestBatch($dbh);
        $previousBatchId = $previousBatch->id;
        $newBatchId = $previousBatchId++;
        return new Batch($dbh, $newBatchId, [], true);
    }

    /**
     * addConsignment
     * Method to add a consignment to the batch
     *
     * @param \PDO $dbh
     * @param Consignment $consignment
     * @return boolean
     */
    public function addConsignment(\PDO $dbh, Consignment $consignment) : bool {
        $this->consignments[] = $consignment;
        // Save consignment to table

        $success = true; // ($success = migration success)
        return $success;
    }

    /**
     * queueConsignmentSends
     * Method to be called when batch is to be processed
     * Queues all consignment
     *
     * @param \PDO $dbh
     */
    public function queueConsignmentSends(\PDO $dbh) {
        foreach ($this->consignments as $consignment) {
            $consignment->queue($dbh);
        }
    }

    /**
     * closeBatch
     * Method to close the batch
     *
     * @param \PDO $dbh
     * @return boolean
     */
    public function closeBatch(\PDO $dbh) : bool {
        $this->isOpen = false;
        // Save batch to table
        $success = true; //($success = migration success)
        return $success;
    }

    /**
     * getLatestBatch
     * Method to retrieve the most recent batch
     *
     * @param \PDO $dbh
     * @return Batch
     */
    public static function getLatestBatch(\PDO $dbh) : Batch {
        // Run select of batch attributes where batch ID is MAX
        $isOpen = false;
        $batchId = 0;
        $consignments = [];
        // Run select of consignments where batch id matches and instantiate consigments for each returned row, assign to array
        return new Batch($dbh, $batchId, $consignments, $isOpen);
    }
}
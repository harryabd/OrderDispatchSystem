<?php

namespace OrderDispatch;

/**
 * AbstractCourier
 *
 * All courier classes should extend this abstract classs
 * Defines required courier-specific methods as well as logic to send
 */
abstract class AbstractCourier
{
    /**
     * $consignmentList
     * Array of consignment numbers to be sent to the courier
     */
    private array $consignmentList;

    /**
     * $courierDataTransferrer
     * instance of data transferrer that can take an array of consignment numbers and send in the required format to the courier
     */
    private AbstractCourierDataTransferrer $courierDataTransferrer;

    /**
     * __construct
     * Constructor method sets $courierDataTransferrer
     *
     * @param AbstractCourierDataTransferrer $courierDataTransferrer
     */
    private function __construct(AbstractCourierDataTransferrer $courierDataTransferrer) {
        $this->$courierDataTransferrer = $courierDataTransferrer;
    }

    /**
     * getNextConsignmentNumber
     * Method that follows courier-specific algorithm/service lookup
     *
     * @return integer
     */
    abstract public function getNextConsignmentNumber() : int;

    /**
     * queueConsignment
     * Method to add a consignment to courier consignment list
     *
     * @param Consignment $consignment
     * @return boolean success
     */
    public function queueConsignment(Consignment $consignment) {
        $this->consignmentList[] = $consignment->consignmentNumber;
    }

    /**
     * sendQueuedConsignments
     * Method to send the consignment list to the courier
     * Empties consignment list on success
     *
     * @return boolean $success
     */
    public function sendQueuedConsignments() : bool {
        try {
            $this->courierDataTransferrer->send($this->consignmentList);

            $this->consignmentList = [];
            return true;
        } catch (CourierConsignmentProcessingException $e) {
            // Log error and return success false
            return false;
        }
    }
}
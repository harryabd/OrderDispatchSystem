<?php

namespace OrderDispatch;

/**
 * CourierDataTransferrerInterface
 * This interface defines a data transfer method. e.g. Email, SFTP, Web Service Endpoint
 * The send method should contain the logic to get an array of consignments
 */
abstract class AbstractCourierDataTransferrer
{
    /**
     * send
     * Method to send a array of consignment numbers to a courier
     *
     * @param array $consignmentList
     * @return boolean
     * @throws CourierConsignmentProcessingException on error
     */
    abstract public function send(array $consignmentList) : bool;
}